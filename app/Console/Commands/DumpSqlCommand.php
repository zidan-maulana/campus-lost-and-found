<?php

/**
 * Dump isi database Supabase aktif ke file SQL (schema + data).
 * Dipakai sebagai cadangan sebelum pindah project/region.
 *
 * Jalankan: php artisan findumb:dump-sql
 */

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class DumpSqlCommand extends Command
{
    protected $signature = 'findumb:dump-sql {--path= : Lokasi file output}';

    protected $description = 'Ekspor skema dan data database aktif ke file SQL (cadangan sebelum migrasi)';

    public function handle(): int
    {
        $path = $this->option('path')
            ?: storage_path('backups/findumb-'.date('Ymd-His').'.sql');

        @mkdir(dirname($path), 0775, true);

        $driver = DB::connection()->getDriverName();
        $tables = $this->tableList($driver);

        if (! $tables) {
            $this->error('Tidak ada tabel yang bisa diekspor.');

            return self::FAILURE;
        }

        $out = "-- FindUMB dump\n-- driver: {$driver}\n-- dibuat: ".date('c')."\n\n";
        $out .= "BEGIN;\n\n";

        $total = 0;

        foreach ($tables as $table) {
            $this->line("→ {$table}");

            $rows = DB::table($table)->get();
            $total += $rows->count();

            foreach ($rows as $row) {
                $cols = array_keys((array) $row);
                $vals = array_map(function ($v) {
                    if (is_null($v)) {
                        return 'NULL';
                    }
                    if (is_bool($v)) {
                        return $v ? 'TRUE' : 'FALSE';
                    }
                    if (is_int($v) || is_float($v)) {
                        return (string) $v;
                    }

                    return "'".str_replace("'", "''", (string) $v)."'";
                }, array_values((array) $row));

                $out .= 'INSERT INTO "'.$table.'" ("'.implode('","', $cols).'") VALUES ('
                    .implode(',', $vals).");\n";
            }

            $out .= "\n";
        }

        $out .= "COMMIT;\n";

        file_put_contents($path, $out);

        $this->info('');
        $this->info('Selesai: '.$path);
        $this->info('Tabel: '.count($tables).' | Baris: '.$total);
        $this->info('Ukuran: '.round(filesize($path) / 1024, 1).' KB');

        return self::SUCCESS;
    }

    /** @return array<int, string> */
    private function tableList(string $driver): array
    {
        if ($driver === 'sqlite') {
            return array_map(
                fn ($r) => $r->name,
                DB::select("select name from sqlite_master where type='table' and name not like 'sqlite_%' order by name")
            );
        }

        return array_map(
            fn ($r) => $r->tablename,
            DB::select("select tablename from pg_tables where schemaname='public' order by tablename")
        );
    }
}
