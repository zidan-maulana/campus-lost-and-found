<?php

namespace App\Console\Commands;

use App\Services\ClaimService;
use Illuminate\Console\Command;

class SendClaimReminders extends Command
{
    protected $signature = 'findumb:claim-reminders';

    protected $description = 'Mengirim pengingat 72 jam untuk klaim yang belum direspons penemu (DEC-012)';

    public function handle(ClaimService $claims): int
    {
        $count = $claims->sendReminders();

        $this->info("Pengingat terkirim: {$count}");

        return self::SUCCESS;
    }
}
