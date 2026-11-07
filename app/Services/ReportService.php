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

        return DB::transaction(function () use ($author, $data, $photo) {
            $report = Report::create([
                'user_id' => $author->id,
                'category_id' => $data['category_id'] ?? null,
                'type' => $data['type'],
                'title' => $data['title'],
                'description' => $data['description'] ?? null,
                'campus_location' => $data['campus_location'] ?? null,
                'location' => $data['location'] ?? null,
                'brand' => $data['brand'] ?? null,
                'storage_note' => $data['storage_note'] ?? null,
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

            return $report;
        });
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
     * Simpan foto laporan sesuai batas DEC-021 (1 file, maks 2 MB, JPEG/PNG/WebP).
     * Penyimpanan: Supabase Storage saat kredensial tersedia, selain itu disk lokal.
     */
    private function storePhoto(UploadedFile $photo): string
    {
        return $photo->store('reports', \App\Support\FindumbStorage::disk());
    }
}
