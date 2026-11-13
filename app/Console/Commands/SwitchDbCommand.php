<?php

/**
 * Pindah database ke project Supabase lain (mis. dari Sydney ke Singapore).
 *
 * Supabase tidak bisa mengganti region project yang sudah ada — harus bikin
 * project baru lalu migrasi. Perintah ini mengerjakan bagian migrasinya:
 * uji koneksi → jalankan migration → seed → tulis .env → verifikasi.
 *
 * Jalankan: php artisan findumb:switch-db
 */

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class SwitchDbCommand extends Command
{
    protected $signature = 'findumb:switch-db
                            {--host= : Host pooler project baru}
                            {--port=5432 : Port}
                            {--user= : Username (postgres.PROJECTREF)}
                            {--password= : Password database}
                            {--database=postgres : Nama database}
                            {--skip-seed : Jangan jalankan seeder}';

    protected $description = 'Pindahkan aplikasi ke project Supabase baru (migrasi + seed + update .env)';

    public function handle(): int
    {
        $this->info('=== Pindah database FindUMB ke project Supabase baru ===');
        $this->line('');

        $host = $this->option('host') ?: $this->ask('Host pooler (contoh: aws-0-ap-southeast-1.pooler.supabase.com)');
        $port = $this->option('port') ?: '5432';
        $user = $this->option('user') ?: $this->ask('Username (contoh: postgres.abcdefghijklmnop)');
        $pass = $this->option('password') ?: $this->secret('Password database');
        $db = $this->option('database') ?: 'postgres';

        $this->line('');
        $this->info('Menguji koneksi...');

        $config = [
            'driver' => 'pgsql',
            'host' => $host,
            'port' => $port,
            'database' => $db,
            'username' => $user,
            'password' => $pass,
            'charset' => 'utf8',
            'prefix' => '',
            'prefix_indexes' => true,
            'search_path' => 'public',
            'sslmode' => 'require',
        ];

        config(['database.connections.migrate_target' => $config]);
        DB::purge('migrate_target');

        try {
            $ms = (int) round($this->timeIt(fn () => DB::connection('migrate_target')->select('select 1')) * 1000);
            $this->info("Koneksi OK — {$ms} ms");
        } catch (\Throwable $e) {
            $this->error('Koneksi GAGAL: '.$e->getMessage());
            $this->line('');
            $this->warn('Cek: host/username/password, dan apakah database sudah selesai provisioning.');

            return self::FAILURE;
        }

        if ($ms > 120) {
            $this->warn("Latency {$ms} ms masih tinggi. Region Singapore biasanya ~20-40 ms.");
            if (! $this->confirm('Lanjut saja?', false)) {
                return self::FAILURE;
            }
        }

        $this->line('');
        $this->info('Menjalankan migration...');
        $this->call('migrate', [
            '--database' => 'migrate_target',
            '--force' => true,
        ]);

        if (! $this->option('skip-seed')) {
            $this->info('Mengisi data contoh...');
            $this->call('db:seed', [
                '--database' => 'migrate_target',
                '--force' => true,
            ]);
        }

        $this->line('');
        $this->info('Verifikasi isi database:');
        $counts = [];
        foreach (['users', 'item_categories', 'reports', 'claims', 'notifications'] as $table) {
            try {
                $counts[$table] = DB::connection('migrate_target')->table($table)->count();
            } catch (\Throwable $e) {
                $counts[$table] = 'ERROR';
            }
        }
        $this->table(['Tabel', 'Baris'], collect($counts)->map(fn ($v, $k) => [$k, $v])->all());

        $this->line('');
        $this->info('Menulis .env...');
        $this->writeEnv([
            'DB_CONNECTION' => 'pgsql',
            'DB_HOST' => $host,
            'DB_PORT' => (string) $port,
            'DB_DATABASE' => $db,
            'DB_USERNAME' => $user,
            'DB_PASSWORD' => $pass,
        ]);

        $this->info('Membersihkan cache konfigurasi...');
        $this->call('config:clear');

        $this->line('');
        $this->info('=== SELESAI ===');
        $this->line('Cek cepat:  php artisan findumb:ping');

        return self::SUCCESS;
    }

    private function timeIt(callable $fn): float
    {
        $t = microtime(true);
        $fn();

        return microtime(true) - $t;
    }

    /** @param array<string, string> $values */
    private function writeEnv(array $values): void
    {
        $path = base_path('.env');
        $content = File::get($path);

        foreach ($values as $key => $value) {
            // Password berisi karakter khusus wajib dikutip.
            $needsQuote = (bool) preg_match('/[#\s"\'\\\\]/', $value);
            $rendered = $needsQuote ? '"'.addcslashes($value, '"\\').'"' : $value;

            $line = $key.'='.$rendered;

            if (preg_match('/^'.preg_quote($key, '/').'=.*$/m', $content)) {
                $content = preg_replace('/^'.preg_quote($key, '/').'=.*$/m', $line, $content, 1);
            } else {
                $content .= PHP_EOL.$line;
            }
        }

        File::put($path, $content);
        $this->line('  .env diperbarui.');
    }
}
