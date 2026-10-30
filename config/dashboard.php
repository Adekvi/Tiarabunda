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
                ['label' => 'Sejarah', 'route' => 'superadmin.konten', 'params' => ['slug' => 'sejarah']],
                ['label' => 'Visi & Misi', 'route' => 'superadmin.konten', 'params' => ['slug' => 'visi']],
                // ...
            ],
        ],
        ['type' => 'sep', 'label' => 'Sistem'],
        ['type' => 'link', 'label' => 'Pengguna', 'icon' => 'users', 'route' => 'superadmin.users'],
        ['type' => 'link', 'label' => 'Galeri', 'icon' => 'img', 'route' => 'superadmin.gallery'],
        ['type' => 'link', 'label' => 'Kalender', 'icon' => 'cal', 'route' => 'superadmin.calendar'],
        ['type' => 'link', 'label' => 'FAQ', 'icon' => 'help', 'route' => 'superadmin.faq'],
    ],
];