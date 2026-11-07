<?php

namespace App\Console\Commands;

use App\Services\ChatService;
use Illuminate\Console\Command;

class PurgeChatMessages extends Command
{
    protected $signature = 'findumb:purge-chat';

    protected $description = 'Menghapus pesan chat 30 hari setelah serah terima selesai (BR-09)';

    public function handle(ChatService $chat): int
    {
        $count = $chat->purgeExpired();

        $this->info("Pesan dihapus: {$count}");

        return self::SUCCESS;
    }
}
