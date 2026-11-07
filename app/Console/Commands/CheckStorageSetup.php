<?php

namespace App\Console\Commands;

use App\Support\FindumbStorage;
use Aws\S3\S3Client;
use Illuminate\Console\Command;

/**
 * Memeriksa kredensial S3 Supabase & memastikan bucket tersedia.
 * Jalankan setelah mengisi SUPABASE_S3_ACCESS_KEY / SECRET di .env.
 */
class CheckStorageSetup extends Command
{
    protected $signature = 'findumb:storage-check';

    protected $description = 'Periksa koneksi Supabase Storage dan pastikan bucket tersedia';

    public function handle(): int
    {
        if (! FindumbStorage::isSupabase()) {
            $this->warn('Kredensial Supabase Storage belum diisi di .env (SUPABASE_S3_*). Upload akan memakai disk lokal public.');

            return self::SUCCESS;
        }

        $config = config('filesystems.disks.supabase');
        $bucket = $config['bucket'];

        $client = new S3Client([
            'version' => '2006-03-01',
            'region' => $config['region'],
            'endpoint' => $config['endpoint'],
            'credentials' => ['key' => $config['key'], 'secret' => $config['secret']],
            'use_path_style_endpoint' => true,
        ]);

        try {
            $buckets = $client->listBuckets()->get('Buckets');
            $this->info('Koneksi S3 OK. Bucket terdaftar: '.implode(', ', array_column($buckets, 'Name')));
        } catch (\Throwable $e) {
            $this->error('Gagal terhubung ke Supabase Storage: '.$e->getMessage());

            return self::FAILURE;
        }

        try {
            $client->headBucket(['Bucket' => $bucket]);
            $this->info("Bucket '{$bucket}' tersedia.");
        } catch (\Throwable $e) {
            $this->warn("Bucket '{$bucket}' belum ada.");
            $this->warn('Buat manual di Dashboard > Storage > New bucket, centang Public bucket.');
            $this->warn('Atau biarkan perintah ini mencoba membuatnya otomatis:');

            try {
                $client->createBucket(['Bucket' => $bucket]);
                $this->info("Bucket '{$bucket}' berhasil dibuat otomatis.");
            } catch (\Throwable $e2) {
                $this->warn('Pembuatan otomatis gagal: '.$e2->getMessage());

                return self::FAILURE;
            }
        }

        try {
            $client->putObject(['Bucket' => $bucket, 'Key' => '__probe.txt', 'Body' => 'ok']);
            $url = $config['url'].'/__probe.txt';
            $status = @file_get_contents($url);
            $client->deleteObject(['Bucket' => $bucket, 'Key' => '__probe.txt']);

            if ($status === 'ok') {
                $this->info('Koneksi OK: tulis + baca publik + hapus berhasil.');
            } else {
                $this->warn('Tulis OK, tetapi objek tidak terbaca publik. Pastikan bucket berstatus PUBLIC.');
            }
        } catch (\Throwable $e) {
            $this->error('Tes tulis/hapus gagal: '.$e->getMessage());

            return self::FAILURE;
        }

        $this->line('Contoh public URL: '.$config['url'].'/reports/xxx.jpg');
        $this->comment('Bucket harus berstatus PUBLIC agar foto bisa dibuka tanpa token.');

        return self::SUCCESS;
    }
}