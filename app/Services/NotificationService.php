<?php

namespace App\Services;

use App\Enums\ClaimStatus;
use App\Models\AppNotification;
use App\Models\Claim;
use App\Models\User;
use Illuminate\Support\Facades\Route;

/**
 * Notifikasi dalam aplikasi (PR-07, FR-10).
 * Tidak mengirim push notification — di luar ruang lingkup versi awal.
 */
class NotificationService
{
    public function send(
        User $recipient,
        string $type,
        string $title,
        ?string $body = null,
        ?string $targetUrl = null,
        array $data = [],
    ): AppNotification {
        return AppNotification::create([
            'user_id' => $recipient->id,
            'type' => $type,
            'title' => $title,
            'body' => $body,
            'target_url' => $targetUrl,
            'data' => $data ?: null,
        ]);
    }

    /** Klaim baru masuk ke penemu. */
    public function claimSubmitted(Claim $claim): void
    {
        $finder = $claim->finder();

        if (! $finder) {
            return;
        }

        $this->send(
            $finder,
            'claim',
            'Pengajuan Klaim: '.$claim->report->title,
            ($claim->claimant?->name ?? 'Civitas').' mengajukan klaim kepemilikan atas barang temuan Anda.',
            $this->claimUrl($claim),
            ['claim_id' => $claim->id, 'report_id' => $claim->report_id],
        );
    }

    /** Pengingat 72 jam tanpa respons penemu (DEC-012). */
    public function claimReminder(Claim $claim): void
    {
        $claimant = $claim->claimant;

        if (! $claimant) {
            return;
        }

        $remaining = (int) max(0, config('findumb.claim.expiry_hours', 120) - config('findumb.claim.reminder_hours', 72));

        $this->send(
            $claimant,
            'claim_reminder',
            'Peringatan Masa Tunggu: '.$claim->report->title,
            'Klaim Anda telah '.config('findumb.claim.reminder_hours', 72).' jam tanpa respons dari penemu. Sisa '.$remaining.' jam sebelum kedaluwarsa.',
            $this->claimUrl($claim),
            ['claim_id' => $claim->id, 'report_id' => $claim->report_id],
        );
    }

    /** Klaim kedaluwarsa otomatis 120 jam. */
    public function claimExpired(Claim $claim): void
    {
        if ($claimant = $claim->claimant) {
            $this->send(
                $claimant,
                'claim_expired',
                'Klaim Kedaluwarsa: '.$claim->report->title,
                'Klaim Anda berakhir otomatis karena penemu tidak merespons hingga batas waktu. Anda dapat mengajukan klaim pada barang lain.',
                route('my.reports'),
                ['claim_id' => $claim->id, 'report_id' => $claim->report_id],
            );
        }
    }

    /** Penemu memilih klaim untuk serah terima. */
    public function handoverStarted(Claim $claim): void
    {
        if ($claimant = $claim->claimant) {
            $this->send(
                $claimant,
                'handover',
                'Klaim Disetujui: '.$claim->report->title,
                'Penemu menyetujui klaim Anda dan mengajak proses serah terima. Silakan diskusikan waktu dan lokasi melalui chat.',
                $this->claimUrl($claim),
                ['claim_id' => $claim->id, 'report_id' => $claim->report_id],
            );
        }
    }

    /** Klaim ditolak oleh penemu. */
    public function claimRejected(Claim $claim): void
    {
        if ($claimant = $claim->claimant) {
            $this->send(
                $claimant,
                'warning',
                'Klaim Ditolak: '.$claim->report->title,
                'Penemu menolak pengajuan klaim Anda. Anda tidak dapat mengajukan klaim ulang pada laporan yang sama.',
                route('my.reports'),
                ['claim_id' => $claim->id, 'report_id' => $claim->report_id],
            );
        }
    }

    /** Klaim dibatalkan (oleh pengklaim, penemu, admin, atau sistem). */
    public function claimCancelled(Claim $claim, ?User $actor = null): void
    {
        $recipients = collect([$claim->claimant, $claim->finder()])->filter();

        $actorName = $actor?->name ?? 'Sistem';

        foreach ($recipients as $recipient) {
            $this->send(
                $recipient,
                'warning',
                'Klaim Dibatalkan: '.$claim->report->title,
                'Proses klaim dihentikan oleh '.$actorName.'. Laporan kembali dapat menerima klaim lain.',
                route('my.reports'),
                ['claim_id' => $claim->id, 'report_id' => $claim->report_id],
            );
        }
    }

    /** Satu pihak mengonfirmasi serah terima; menunggu pihak lain. */
    public function handoverAwaitingOtherParty(Claim $claim, User $confirmer): void
    {
        $other = (int) $confirmer->id === (int) $claim->claimant_id
            ? $claim->finder()
            : $claim->claimant;

        if (! $other) {
            return;
        }

        $this->send(
            $other,
            'handover',
            'Menunggu Konfirmasi Anda: '.$claim->report->title,
            $confirmer->name.' telah mengonfirmasi serah terima. Konfirmasi Anda diperlukan agar proses selesai.',
            $this->claimUrl($claim),
            ['claim_id' => $claim->id, 'report_id' => $claim->report_id],
        );
    }

    /** Serah terima selesai (dua pihak konfirmasi). */
    public function handoverCompleted(Claim $claim): void
    {
        $recipients = collect([$claim->claimant, $claim->finder()])->filter();

        foreach ($recipients as $recipient) {
            $this->send(
                $recipient,
                'handover',
                'Serah Terima Selesai: '.$claim->report->title,
                'Kedua pihak telah mengonfirmasi serah terima. Laporan ditutup dan percakapan menjadi hanya-baca.',
                route('my.reports'),
                ['claim_id' => $claim->id, 'report_id' => $claim->report_id],
            );
        }
    }

    /** Pesan chat baru. */
    public function newMessage(Claim $claim, User $sender): void
    {
        $recipient = (int) $sender->id === (int) $claim->claimant_id
            ? $claim->finder()
            : $claim->claimant;

        if (! $recipient) {
            return;
        }

        $this->send(
            $recipient,
            'message',
            'Pesan Baru dari '.$sender->name,
            'Mengirim pesan terkait "'.$claim->report->title.'".',
            $this->claimUrl($claim),
            ['claim_id' => $claim->id, 'report_id' => $claim->report_id],
        );
    }

    private function claimUrl(Claim $claim): string
    {
        return Route::has('messages')
            ? route('messages', ['claim' => $claim->id])
            : url('/messages');
    }
}
