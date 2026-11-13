<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QueryAuditCommand extends Command
{
    protected $signature = 'findumb:query-audit {--path=* : Path yang diuji} {--sql : Tampilkan daftar SQL}';

    protected $description = 'Audit jumlah query dan N+1 pada halaman aplikasi';

    public function handle(): int
    {
        $paths = $this->option('path') ?: ['/', '/search', '/report/1', '/report/4'];
        $kernel = app(\Illuminate\Contracts\Http\Kernel::class);

        $rows = [];

        foreach ($paths as $path) {
            DB::flushQueryLog();
            DB::enableQueryLog();

            $start = microtime(true);
            $request = Request::create($path, 'GET');
            $request->headers->set('Accept', 'text/html');

            try {
                $response = $kernel->handle($request);
                $status = $response->getStatusCode();
            } catch (\Throwable $e) {
                $status = 'ERR';
            }

            $elapsed = (microtime(true) - $start) * 1000;
            $log = DB::getQueryLog();
            DB::disableQueryLog();

            $shapes = [];
            foreach ($log as $q) {
                $s = preg_replace('/\b\d+\b/', 'N', $q['query']);
                $shapes[$s] = ($shapes[$s] ?? 0) + 1;
            }
            $repeated = array_filter($shapes, fn ($n) => $n > 3);
            arsort($repeated);

            $rows[] = [$path, $status, count($log), round($elapsed).' ms', count($repeated)];

            if ($this->option('sql')) {
                $this->line('');
                $this->info("SQL untuk {$path}:");
                foreach ($log as $i => $q) {
                    $this->line('  '.($i + 1).'. '.substr(preg_replace('/\s+/', ' ', $q['query']), 0, 130));
                }
            }
        }

        $this->line('');
        $this->table(['Path', 'Status', 'Query', 'Waktu', 'N+1'], $rows);

        return self::SUCCESS;
    }
}
