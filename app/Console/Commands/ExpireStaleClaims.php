<?php

namespace App\Console\Commands;

use App\Services\ClaimService;
use Illuminate\Console\Command;

class ExpireStaleClaims extends Command
{
    protected $signature = 'findumb:expire-claims';

    protected $description = 'Mengubah klaim yang melewati batas 120 jam tanpa respons menjadi expired (DEC-024)';

    public function handle(ClaimService $claims): int
    {
        $count = $claims->expireStale();

        $this->info("Klaim kedaluwarsa: {$count}");

        return self::SUCCESS;
    }
}
