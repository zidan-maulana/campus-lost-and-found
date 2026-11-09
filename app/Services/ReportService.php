<?php

namespace App\Services;

use App\Enums\ClaimStatus;
use App\Enums\ReportStatus;
use App\Enums\ReportType;
use App\Exceptions\BusinessRuleException;
use App\Models\Report;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

/**
 * Pembuatan, perubahan, dan pembatalan laporan (PR-02, FR-03, FR-04).
 */
class ReportService
{
    public function __construct(private readonly NotificationService $notifications)
    {
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function create(User $author, array $data, ?UploadedFile $photo = null): Report
    {
        if (! $author->isActive()) {
            throw new BusinessRuleException('Akun Anda sedang ditangguhkan sehingga tidak dapat membuat laporan.');
        }

        $type = $data['type'] instanceof ReportType
            ? $data['type']
            : ReportType::from((string) $data['type']);

        // DEC-027 — satu barang = satu laporan aktif. Cegah laporan temuan ganda
        // dari pengguna yang sama untuk barang yang sama.
        if ($type === ReportType::Found) {
            $this->assertNoDuplicateFoundReport($author, (string) $data['title']);
        }

        return DB::transaction(function () use ($author, $data, $type, $photo) {
            // DEC-027 — laporan temuan yang dibuat dari laporan hilang menutup
            // laporan hilang tersebut agar satu barang tidak tampil dua kali.
            $reference = $this->lockReferenceReport($data, $type);

            $report = Report::create([
                'user_id' => $author->id,
                'category_id' => $data['category_id'] ?? null,
                'reference_report_id' => $reference?->id,
                'type' => $type,
                'title' => $data['title'],
                'description' => $data['description'] ?? null,
                'proof_question' => $data['proof_question'] ?? null,
                'campus_location' => $data['campus_location'] ?? null,
                'location' => $data['location'] ?? null,
                'brand' => $data['brand'] ?? null,
                'storage_note' => $data['storage_note'] ?? null,
                'storage_location' => $data['storage_location'] ?? null,
                'security_post_id' => $data['security_post_id'] ?? null,
                'incident_date' => $data['incident_date'] ?? null,
                'incident_time' => $data['incident_time'] ?? null,
                'image_path' => $photo ? $this->storePhoto($photo) : null,
                'status' => ReportStatus::Open,
            ]);

            $this->notifications->send(
                $author,
                'report',
                'Laporan Berhasil Dipublikasikan',
                'Laporan "'.$report->title.'" telah tayang di katalog publik.',
                route('report.detail', $report->id),
                ['report_id' => $report->id],
            );

            if ($reference) {
                $this->closeReferenceReport($reference, $report, $author);
            }

            return $report;
        });
    }

    /**
     * Ambil laporan hilang yang dirujuk (dengan row lock) bila masih berjalan.
     *
     * @param  array<string, mixed>  $data
     */
    private function lockReferenceReport(array $data, ReportType $type): ?Report
    {
        $referenceId = $data['reference_report_id'] ?? null;

        if (! $referenceId || $type !== ReportType::Found) {
            return null;
        }

        $reference = Report::query()
            ->whereKey($referenceId)
            ->where('type', ReportType::Lost->value)
            ->lockForUpdate()
            ->first();

        // Hanya laporan hilang yang masih terbuka yang perlu ditutup.
        return $reference && $reference->status === ReportStatus::Open ? $reference : null;
    }

    /**
     * Tutup laporan hilang karena barangnya sudah ditemukan, lalu beri tahu
     * pemiliknya bahwa ada laporan temuan yang bisa diklaim (DEC-027).
     */
    private function closeReferenceReport(Report $reference, Report $found, User $author): void
    {
        $reference->update(['status' => ReportStatus::Resolved]);

        if ($reference->user && (int) $reference->user->id !== (int) $author->id) {
            $this->notifications->send(
                $reference->user,
                'system',
                'Kabar Baik: Barang Anda Kemungkinan Ditemukan',
                'Ada laporan temuan "'.$found->title.'" yang dibuat dari laporan kehilangan Anda. Buka detailnya untuk mengajukan klaim.',
                route('report.detail', $found->id),
                ['report_id' => $found->id, 'reference_report_id' => $reference->id],
            );
        }
    }

    /**
     * Buka kembali laporan hilang yang tertaut ketika laporan temuan dibatalkan,
     * supaya pemiliknya bisa mencari lagi (DEC-027).
     */
    private function reopenReferenceReport(Report $found): void
    {
        if (! $found->isFound() || ! $found->reference_report_id) {
            return;
        }

        $reference = Report::query()
            ->whereKey($found->reference_report_id)
            ->lockForUpdate()
            ->first();

        if ($reference && $reference->status === ReportStatus::Resolved) {
            $reference->update(['status' => ReportStatus::Open]);
        }
    }

    /** DEC-027 — tolak laporan temuan ganda untuk barang yang sama. */
    private function assertNoDuplicateFoundReport(User $author, string $title): void
    {
        $normalized = mb_strtolower(trim($title));

        if ($normalized === '') {
            return;
        }

        $exists = Report::query()
            ->where('user_id', $author->id)
            ->where('type', ReportType::Found->value)
            ->whereIn('status', [ReportStatus::Open->value, ReportStatus::InHandover->value])
            ->whereRaw('lower(title) = ?', [$normalized])
            ->exists();

        if ($exists) {
            throw new BusinessRuleException('Anda sudah punya laporan temuan aktif untuk barang ini. Selesaikan atau batalkan laporan tersebut sebelum membuat laporan baru.');
        }
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function update(Report $report, User $editor, array $data, ?UploadedFile $photo = null): Report
    {
        if (! $report->isOwnedBy($editor)) {
            throw new BusinessRuleException('Anda hanya dapat mengubah laporan milik Anda sendiri.', 403);
        }

        if ($report->status->isLocked()) {
            throw new BusinessRuleException('Laporan ini sedang dalam proses serah terima atau sudah berakhir sehingga tidak dapat diubah.');
        }

        return DB::transaction(function () use ($report, $data, $photo) {
            $locked = Report::whereKey($report->id)->lockForUpdate()->firstOrFail();

            if ($locked->status->isLocked()) {
                throw new BusinessRuleException('Status laporan berubah. Silakan muat ulang halaman.');
            }

            $payload = collect($data)
                ->only([
                    'category_id', 'title', 'description', 'campus_location',
                    'location', 'brand', 'storage_note', 'incident_date', 'incident_time',
                ])
                ->filter(fn ($value) => $value !== null)
                ->all();

            if ($photo) {
                if ($locked->image_path) {
                    \App\Support\FindumbStorage::delete($locked->image_path);
                }

                $payload['image_path'] = $this->storePhoto($photo);
            }

            $locked->update($payload);

            return $locked->fresh();
        });
    }

    /**
     * Pembatalan laporan oleh pemilik (FR-04).
     * Tidak diizinkan saat in_handover — klaim harus dibatalkan lebih dulu.
     */
    public function cancel(Report $report, User $owner, ?string $reason = null): Report
    {
        if (! $report->isOwnedBy($owner)) {
            throw new BusinessRuleException('Anda hanya dapat membatalkan laporan milik Anda sendiri.', 403);
        }

        return DB::transaction(function () use ($report, $owner, $reason) {
            $locked = Report::whereKey($report->id)->lockForUpdate()->firstOrFail();

            if ($locked->status === ReportStatus::InHandover) {
                throw new BusinessRuleException('Laporan sedang dalam proses serah terima. Selesaikan atau batalkan serah terima terlebih dahulu.');
            }

            if ($locked->status !== ReportStatus::Open) {
                throw new BusinessRuleException('Laporan ini sudah tidak dapat dibatalkan.');
            }

            $locked->update(['status' => ReportStatus::Cancelled]);

            // DEC-027 — laporan temuan batal: buka kembali laporan hilang yang
            // tertaut supaya pemiliknya bisa mencari lagi.
            $this->reopenReferenceReport($locked);

            // Klaim yang masih mengantre ikut diakhiri tanpa sanksi.
            $queued = $locked->claims()->where('status', ClaimStatus::Submitted->value)->get();

            foreach ($queued as $claim) {
                $claim->update([
                    'status' => ClaimStatus::Cancelled,
                    'resolved_at' => now(),
                    'cancellation_reason' => $reason ?? 'Laporan dibatalkan oleh pembuatnya.',
                ]);

                $claim->histories()->create([
                    'from_status' => ClaimStatus::Submitted->value,
                    'to_status' => ClaimStatus::Cancelled->value,
                    'changed_by' => $owner->id,
                    'reason' => 'Laporan dibatalkan oleh pembuatnya.',
                    'created_at' => now(),
                ]);

                $this->notifications->claimCancelled($claim, $owner);
            }

            return $locked->fresh();
        });
    }

    /**
     * Pemilik menutup laporan HILANG karena barangnya sudah ditemukan
     * (DEC-027) — jalur ini dipakai ketika barang kembali tanpa lewat
     * serah terima di aplikasi.
     */
    public function resolveByOwner(Report $report, User $owner): Report
    {
        if (! $report->isOwnedBy($owner)) {
            throw new BusinessRuleException('Anda hanya dapat menutup laporan milik Anda sendiri.', 403);
        }

        if (! $report->isLost()) {
            throw new BusinessRuleException('Laporan barang temuan ditutup lewat proses serah terima, bukan ditandai selesai.');
        }

        return DB::transaction(function () use ($report) {
            $locked = Report::whereKey($report->id)->lockForUpdate()->firstOrFail();

            if ($locked->status === ReportStatus::Resolved) {
                throw new BusinessRuleException('Laporan ini sudah ditutup.');
            }

            if ($locked->status !== ReportStatus::Open) {
                throw new BusinessRuleException('Laporan ini sudah tidak dapat ditutup.');
            }

            $locked->update(['status' => ReportStatus::Resolved]);

            return $locked->fresh();
        });
    }

    /**
     * Simpan foto laporan sesuai batas DEC-021 (1 file, maks 2 MB, JPEG/PNG/WebP).
     * Penyimpanan: Supabase Storage saat kredensial tersedia, selain itu disk lokal.
     */
    private function storePhoto(UploadedFile $photo): string
    {
        return $photo->store('reports', \App\Support\FindumbStorage::disk());
    }
}
