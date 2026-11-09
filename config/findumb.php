<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Aturan bisnis yang masih dapat dikonfigurasi
    |--------------------------------------------------------------------------
    | Nilai default mengikuti keputusan di docs/DECISIONS_LOG.md. Beberapa
    | keputusan masih berstatus "Needs Clarification" (DEC-016, DEC-021),
    | sehingga sengaja dibuat konfigurabel dan tidak di-hardcode.
    */

    'campus_email_domains' => array_values(array_filter(array_map(
        'trim',
        explode(',', (string) env('CAMPUS_EMAIL_DOMAINS', 'mercubuana.ac.id'))
    ))),

    // Enum lokasi kampus sesuai docs/API_CONTRACT.md §3 (CampusLocation).
    'campuses' => [
        'meruya' => 'Kampus Meruya',
        'menteng' => 'Kampus Menteng',
        'pejaten' => 'Kampus Pejaten',
        'warung_buncit' => 'Kampus Warung Buncit',
    ],

    'claim' => [
        // DEC-024 — total batas waktu tanpa respons penemu.
        'expiry_hours' => (int) env('CLAIM_EXPIRY_HOURS', 120),
        // DEC-012 — pengingat pertama sebelum kedaluwarsa.
        'reminder_hours' => (int) env('CLAIM_REMINDER_HOURS', 72),
    ],

    'chat' => [
        // BR-09 — retensi pesan setelah serah terima selesai.
        'retention_days' => (int) env('CHAT_RETENTION_DAYS', 30),
        'max_length' => 2000,
        // Interval polling client-side untuk pembaruan chat (SYSTEM_DESIGN 12.3).
        'poll_seconds' => (int) env('CHAT_POLL_SECONDS', 5),
    ],

    'report' => [
        // DEC-021 — usulan nilai aman sebelum fitur unggah diaktifkan.
        'photo_max_kb' => (int) env('REPORT_PHOTO_MAX_KB', 2048),
        'photo_mimes' => array_values(array_filter(array_map(
            'trim',
            explode(',', (string) env('REPORT_PHOTO_MIMES', 'jpg,jpeg,png,webp'))
        ))),
        'per_page' => 12,
    ],

    'abuse' => [
        // Jenis objek yang boleh dilaporkan (DATABASE_DESIGN 4.9).
        'target_types' => ['report', 'chat_message'],
    ],

];
