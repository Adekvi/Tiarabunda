<?php

return [
    'brand_subtitle' => 'Panel Superadmin',

    'nav' => [
        [
            'type' => 'link',
            'label' => 'Dashboard',
            'icon' => 'home',
            'route' => 'superadmin.dashboard',
            'active' => 'superadmin.dashboard',
        ],

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
                [
                    'label' => 'Sambutan',
                    'route' => 'superadmin.pimpinan.index',
                    'active' => 'superadmin.pimpinan.*',
                ],
            ],
        ],

        [
            'type' => 'group',
            'label' => 'Pendidikan',
            'icon' => 'book',
            'children' => [
                [
                    'label' => 'Fakultas',
                    'route' => 'superadmin.fakultas.index',
                    'active' => 'superadmin.fakultas.*',
                ],
                [
                    'label' => 'Program Studi',
                    'route' => 'superadmin.prodi.index',
                    'active' => 'superadmin.prodi.*',
                ],
                [
                    'label' => 'PMB',
                    'route' => 'superadmin.pmb.index',
                    'active' => 'superadmin.pmb.*',
                ],
                [
                    'label' => 'Beasiswa',
                    'route' => 'superadmin.beasiswa.index',
                    'active' => 'superadmin.beasiswa.*',
                ],
                [
                    'label' => 'Akreditasi',
                    'route' => 'superadmin.akreditasi.index',
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
                    'route' => 'superadmin.perpus.index',
                    'active' => 'superadmin.perpus.*',
                ],
                [
                    'label' => 'Kemahasiswaan',
                    'route' => 'superadmin.kemaha.index',
                    'active' => 'superadmin.kemaha.*',
                ],
                [
                    'label' => 'Pusat Karier',
                    'route' => 'superadmin.karir.index',
                    'active' => 'superadmin.karir.*',
                ],
                [
                    'label' => 'Fasilitas',
                    'route' => 'superadmin.fasilitas.index',
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
                    'label' => 'Berita',
                    'route' => 'superadmin.berita.index',
                    'active' => 'superadmin.berita.*',
                ],
                [
                    'label' => 'Kegiatan',
                    'route' => 'superadmin.kegiatan.index',
                    'active' => 'superadmin.kegiatan.*',
                ],
                [
                    'label' => 'FAQ',
                    'route' => 'superadmin.faq.index',
                    'active' => 'superadmin.faq.*',
                ],
            ],
        ],

        ['type' => 'sep', 'label' => 'Sistem'],

        [
            'type' => 'link',
            'label' => 'Pengguna',
            'icon' => 'users',
            'route' => 'superadmin.users',
            'active' => 'superadmin.users',
        ],
        [
            'type' => 'link',
            'label' => 'Galeri',
            'icon' => 'img',
            'route' => 'superadmin.gallery',
            'active' => 'superadmin.gallery',
        ],
        [
            'type' => 'link',
            'label' => 'Kalender',
            'icon' => 'cal',
            'route' => 'superadmin.calendar',
            'active' => 'superadmin.calendar',
        ],
    ],
];
