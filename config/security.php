<?php

return [

    /*
    | Nama field yang berisi HTML dan dirender mentah ({!! !!}) di halaman publik.
    | Nilainya disanitasi (allowlist) oleh middleware SanitizeAdminInput saat disimpan.
    | Tambahkan nama field baru di sini jika membuat form konten baru.
    */
    'html_fields' => [
        'katrangan',
        'isi',
        'wangsulan',
        'syarat',
        'langkah',
        'syarat_html',
        'langkah_html',
        'syarat_text',
        'langkah_text',
        'riwayat',
        'sambutan',
    ],

    /*
    | Proxy tepercaya (mis. Cloudflare) agar $request->ip() = IP pengunjung asli,
    | sehingga rate limit per-IP bekerja. Isi '*' atau daftar IP dipisah koma.
    | AKTIFKAN HANYA jika server asal sudah dikunci agar hanya menerima trafik proxy
    | (jika tidak, header X-Forwarded-For bisa dipalsukan untuk menghindari rate limit).
    */
    'trusted_proxies' => env('TRUSTED_PROXIES'),

    /*
    | Rahasia bersama dengan Cloudflare (header X-Origin-Auth, lihat RequireOriginSecret).
    | Kosong = pengecekan nonaktif. Buat: openssl rand -hex 32
    */
    'origin_secret' => env('ORIGIN_SECRET'),

];
