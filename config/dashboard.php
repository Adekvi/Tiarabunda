<?php

return [
    'brand_subtitle' => 'Panel Superadmin',

    'nav' => [
        ['type' => 'link', 'label' => 'Dashboard', 'icon' => 'home', 'route' => 'superadmin.dashboard'],

        ['type' => 'sep', 'label' => 'Konten Landing'],
        [
            'type' => 'group',
            'label' => 'Profil',
            'icon' => 'bldg',
            'children' => [
                [
                    'label' => 'Sejarah',
                    'route' => 'superadmin.sejarah.index',
                    'active' => 'superadmin.sejarah.*',
                ],
                [
                    'label' => 'Visi & Misi',
                    'route' => 'superadmin.visimisi.index',
                    'active' => 'superadmin.visimisi.*',
                ],
            ],
        ],
        [
            'type' => 'group',
            'label' => 'Pendidikan',
            'icon' => 'book',
            'children' => [
                [
                    'label' => 'Program Pendidikan',
                    'route' => 'superadmin.fakultas',
                    'params' => ['slug' => 'program-pendidikan'],
                    'active' => 'superadmin.fakultas.*',
                ],
                [
                    'label' => 'Program Pendidikan',
                    'route' => 'superadmin.prodi',
                    'params' => ['slug' => 'program-pendidikan'],
                    'active' => 'superadmin.prodi.*',
                ],
                [
                    'label' => 'PMB',
                    'route' => 'superadmin.pmb',
                    'params' => ['slug' => 'pmb'],
                    'active' => 'superadmin.pmb.*',
                ],
                [
                    'label' => 'Beasiswa',
                    'route' => 'superadmin.beasiswa',
                    'params' => ['slug' => 'beasiswa'],
                    'active' => 'superadmin.beasiswa.*',
                ],
                [
                    'label' => 'Akreditasi',
                    'route' => 'superadmin.akreditasi',
                    'params' => ['slug' => 'akreditasi'],
                    'active' => 'superadmin.akreditasi.*',
                ],
            ],
        ],
        [
            'type' => 'group',
            'label' => 'Layanan',
            'icon' => 'box',
            'children' => [
                [
                    'label' => 'Perpustakaan',
                    'route' => 'superadmin.perpus',
                    'params' => ['slug' => 'perpustakaan'],
                    'active' => 'superadmin.perpus.*',
                ],
                [
                    'label' => 'Kemahasiswaan',
                    'route' => 'superadmin.kemaha',
                    'params' => ['slug' => 'kemahasiswaan'],
                    'active' => 'superadmin.kemaha.*',
                ],
                [
                    'label' => 'Pusat Karir',
                    'route' => 'superadmin.karir',
                    'params' => ['slug' => 'pusat-karir'],
                    'active' => 'superadmin.karir.*',
                ],
                [
                    'label' => 'Fasilitas',
                    'route' => 'superadmin.fasilitas',
                    'params' => ['slug' => 'fasilitas'],
                    'active' => 'superadmin.fasilitas.*',
                ],
            ],
        ],
        [
            'type' => 'group',
            'label' => 'Informasi',
            'icon' => 'info',
            'children' => [
                [
                    'label' => 'Berita Terbaru',
                    'route' => 'superadmin.berita',
                    'params' => ['slug' => 'berita-terbaru'],
                    'active' => 'superadmin.berita.*',
                ],
                [
                    'label' => 'Kegiatan Kampus',
                    'route' => 'superadmin.kegiatan',
                    'params' => ['slug' => 'kegiatan-kampus'],
                    'active' => 'superadmin.kegiatan.*',
                ],
                [
                    'label' => 'FAQ',
                    'route' => 'superadmin.faq.index'
                ],
            ],
        ],

        ['type' => 'sep', 'label' => 'Sistem'],
        ['type' => 'link', 'label' => 'Pengguna', 'icon' => 'users', 'route' => 'superadmin.users'],
        ['type' => 'link', 'label' => 'Galeri', 'icon' => 'img', 'route' => 'superadmin.gallery'],
        ['type' => 'link', 'label' => 'Kalender', 'icon' => 'cal', 'route' => 'superadmin.calendar'],
        // Menu FAQ single link di bagian Sistem sebelumnya bisa Anda hapus karena sudah masuk ke grup Informasi
    ],
];
