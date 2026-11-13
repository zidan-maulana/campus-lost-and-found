<?php

namespace Database\Seeders;

use App\Enums\ClaimStatus;
use App\Models\ChatMessage;
use App\Models\Claim;
use App\Models\ClaimStatusHistory;
use App\Models\HandoverConfirmation;
use App\Models\Report;
use App\Models\User;
use Illuminate\Database\Seeder;

class ClaimSeeder extends Seeder
{
    /**
     * Klaim demo untuk memperagakan seluruh status siklus hidup:
     * submitted (menunggu), in_handover (serah terima berjalan),
     * rejected (terminal).
     */
    public function run(): void
    {
        $user = fn (string $email) => User::where('email', $email)->firstOrFail();
        $report = fn (string $title) => Report::where('title', $title)->firstOrFail();

        // --- 1) Klaim menunggu: Earphone TWS (Rizky -> Dinda) ---
        $claim1 = $this->claim(
            $report('Earphone TWS Anker Soundcore'),
            $user('rizky.pratama@student.mercubuana.ac.id'),
            ClaimStatus::Submitted,
            now()->subHours(5),
        );

        // --- 2) Serah terima berjalan: Tas Laptop (Siti -> Ahmad) + chat ---
        $tasLaptop = $report('Tas Laptop Lenovo ThinkPad');
        $siti = $user('siti.rahma@student.mercubuana.ac.id');
        $ahmad = $user('ahmad.fauzi@student.mercubuana.ac.id');

        $claim2 = $this->claim($tasLaptop, $siti, ClaimStatus::InHandover, now()->subHours(30));
        $claim2->update([
            'response_notified_at' => now()->subHours(24),
            'resolved_at' => null,
        ]);

        // Riwayat: submitted -> in_handover
        ClaimStatusHistory::create([
            'claim_id' => $claim2->id,
            'from_status' => ClaimStatus::Submitted->value,
            'to_status' => ClaimStatus::InHandover->value,
            'changed_by' => $ahmad->id,
            'reason' => 'Klaim disetujui untuk serah terima.',
            'created_at' => now()->subHours(24),
        ]);

        $timeline = [
            [$siti, now()->subHours(23), 'Halo kak, saya Siti yang mengajukan klaim tas laptop ThinkPad. Apakah masih tersimpan di lab Tower?'],
            [$ahmad, now()->subHours(22), 'Halo Siti, iya masih aman di lab Tower. Bisa kita atur jadwal serah terima?'],
            [$siti, now()->subHours(21), 'Bisa kak. Gimana kalau Senin jam 10 di lobi Gedung Tower? Saya bisa bawa bukti pembelian.'],
            [$ahmad, now()->subHours(20), 'Siap, ketemu di lobi Tower jam 10. Nanti saya konfirmasi saat barang diserahkan.'],
        ];

        foreach ($timeline as [$sender, $at, $message]) {
            ChatMessage::create([
                'claim_id' => $claim2->id,
                'sender_id' => $sender->id,
                'message' => $message,
                'created_at' => $at,
                'updated_at' => $at,
            ]);
        }

        // Penemu (Ahmad) sudah mengonfirmasi; menunggu konfirmasi Siti.
        HandoverConfirmation::create([
            'claim_id' => $claim2->id,
            'user_id' => $ahmad->id,
            'confirmed_at' => now()->subHours(1),
            'created_at' => now()->subHours(1),
            'updated_at' => now()->subHours(1),
        ]);

        // --- 3) Klaim ditolak: Cincin Emas (Dinda -> Ahmad) ---
        $claim3 = $this->claim(
            $report('Cincin Emas Berlian'),
            $user('dinda.kirana@student.mercubuana.ac.id'),
            ClaimStatus::Rejected,
            now()->subDays(4),
        );

        ClaimStatusHistory::create([
            'claim_id' => $claim3->id,
            'from_status' => ClaimStatus::Submitted->value,
            'to_status' => ClaimStatus::Rejected->value,
            'changed_by' => $ahmad->id,
            'reason' => 'Ciri barang tidak sesuai dengan keterangan pemilik.',
            'created_at' => now()->subDays(3),
        ]);
    }

    private function claim(Report $report, User $claimant, ClaimStatus $status, $submittedAt): Claim
    {
        $payload = [
            'report_id' => $report->id,
            'claimant_id' => $claimant->id,
            'status' => $status,
            'submitted_at' => $submittedAt,
        ];

        if ($status->isTerminal()) {
            $payload['resolved_at'] = now()->subDays(1);
        }

        $claim = Claim::updateOrCreate(
            [
                'report_id' => $report->id,
                'claimant_id' => $claimant->id,
            ],
            $payload,
        );

        ClaimStatusHistory::updateOrCreate(
            [
                'claim_id' => $claim->id,
                'from_status' => null,
                'to_status' => $status->value,
            ],
            [
                'changed_by' => $claimant->id,
                'reason' => 'Klaim diajukan.',
                'created_at' => $submittedAt,
            ],
        );

        return $claim;
    }
}