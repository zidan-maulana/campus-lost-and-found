<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class PingDbCommand extends Command
{
    protected $signature = 'findumb:ping {--n=5 : Jumlah query uji}';

    protected $description = 'Ukur latency dan jumlah query database aktif';

    public function handle(): int
    {
        $conn = DB::connection();
        $driver = $conn->getDriverName();
        $name = $conn->getDatabaseName();
        $config = config('database.connections.'.config('database.default'));
        $host = $config['host'] ?? null;
        $persistent = (bool) ($config['options'][\PDO::ATTR_PERSISTENT] ?? false);

        $this->info('=== Latency database ===');
        $this->line('Driver     : '.$driver);
        $this->line('Host       : '.($host ?: '-'));
        $this->line('DB         : '.$name);
        $this->line('Persistent : '.($persistent ? 'ya' : 'tidak'));
        $this->line('');

        $n = max(1, (int) $this->option('n'));

        // Query pertama memuat biaya buka koneksi (TLS handshake + auth).
        $t = microtime(true);
        try {
            $conn->select('select 1');
        } catch (\Throwable $e) {
            $this->error('Koneksi GAGAL: '.$e->getMessage());

            return self::FAILURE;
        }
        $connect = (microtime(true) - $t) * 1000;

        $samples = [];
        for ($i = 0; $i < $n; $i++) {
            $t = microtime(true);
            $conn->select('select 1');
            $samples[] = (microtime(true) - $t) * 1000;
        }

        sort($samples);
        $avg = array_sum($samples) / count($samples);
        $median = $samples[(int) floor(count($samples) / 2)];

        $this->table(
            ['query pertama (buka koneksi)', 'min', 'median', 'rata-rata', 'max'],
            [[
                round($connect).' ms',
                round($samples[0]).' ms',
                round($median).' ms',
                round($avg).' ms',
                round($samples[count($samples) - 1]).' ms',
            ]]
        );

        $this->line('');
        $this->line('Biaya buka koneksi: ~'.round(max(0, $connect - $median)).' ms per request.');

        if ($median < 50) {
            $this->info('Round-trip cepat. Aman untuk pengembangan sehari-hari.');
        } elseif ($median < 150) {
            $this->warn('Sedang. Masih wajar, tapi halaman dengan banyak query akan terasa.');
        } else {
            $this->error('Lambat. Region database terlalu jauh dari lokasi pengguna.');
            $this->line('Pertimbangkan pindah ke region terdekat (Singapore / ap-southeast-1).');
        }

        return self::SUCCESS;
    }
}
