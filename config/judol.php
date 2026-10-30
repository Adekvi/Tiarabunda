<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Threshold skor penolakan
    |--------------------------------------------------------------------------
    | >= block  → ditolak
    | >= review → boleh disimpan sebagai draft / flag moderasi (opsional)
    */
    'thresholds' => [
        'block'  => 50,
        'review' => 30,
    ],

    /*
    |--------------------------------------------------------------------------
    | Domain blocklist (tanpa skema, lowercase)
    |--------------------------------------------------------------------------
    | Update berkala. Hindari domain generik (google.com, dll).
    */
    'domains' => [
        // contoh pola — ganti/extend dengan daftar internal kampus
        'slot-gacor.com',
        'maxwin-judi.com',
        'togel-online.net',
    ],

    /*
    |--------------------------------------------------------------------------
    | Keyword (substring, dicek setelah normalisasi)
    |--------------------------------------------------------------------------
    */
    'keywords' => [
        // ID
        'judi online',
        'judi slot',
        'situs judi',
        'agen judi',
        'slot gacor',
        'slot online',
        'maxwin',
        'scatter hitam',
        'togel',
        'togel online',
        'bandar togel',
        'sabung ayam',
        'mix parlay',
        'parlay judi',
        'casino online',
        'live casino',
        // EN umum
        'online gambling',
        'online casino',
        'betting site',
    ],

    /*
    |--------------------------------------------------------------------------
    | Bobot skor
    |--------------------------------------------------------------------------
    */
    'weights' => [
        'domain_exact'   => 100,
        'keyword_host'   => 40,
        'keyword_path'   => 25,
        'keyword_body'   => 15,
        'shortener'      => 10,
        'obfuscation'    => 20,
    ],

    /*
    |--------------------------------------------------------------------------
    | Shortener yang perlu diwaspadai (bukan blok total)
    |--------------------------------------------------------------------------
    */
    'shorteners' => [
        'bit.ly',
        't.co',
        'tinyurl.com',
        'goo.gl',
        'is.gd',
        'cutt.ly',
        's.id',
        'rb.gy',
        'ow.ly',
    ],

];
