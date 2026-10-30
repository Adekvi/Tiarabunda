<?php

/* Seluruh konten landing. Dibaca oleh komponen Blade (beranda) dan data/*.js (halaman menu).
   Setelah mengubah: php artisan config:clear */
return [
    'site' => [
        'nama' => 'Universitas Tiara Bunda',
        'kota' => 'Kota Depok',
        'hotline' => '(021) 000-0000',
        'alamat' => 'Kota Depok, Jawa Barat',
        'portal' => [
            ['Mahasiswa', '#'],
            ['Dosen', '#'],
            ['Alumni', '#'],
            ['Portal', '#'],
        ],
        'footer' => [
            ['Akademik', [
                ['Program Studi', '/pendidikan/program-pendidikan'],
                ['Kalender Akademik', '/layanan/dokumen'],
                ['Perpustakaan', '/layanan/perpustakaan'],
            ]],
            ['Informasi', [
                ['PMB', '/pendidikan/penerimaan-mahasiswa-baru'],
                ['Akreditasi', '/pendidikan/akreditasi'],
                ['Berita', '/informasi/berita-terbaru'],
            ]],
        ],
        'pmb' => '/pendidikan/penerimaan-mahasiswa-baru',
        'kontak' => [
            'alamat' => 'Jl. Contoh Raya No. 1, Kota Depok, Jawa Barat',
            'telp' => '(021) 000-0000',
            'email' => 'info@tiarabunda.example',
            'wa' => 'https://wa.me/620000000000',
        ],
    ],
    'menus' => [
        'profil' => ['t' => 'Profil', 'subs' => [
            'sejarah' => ['t' => 'Sejarah', 'sub' => 'Perjalanan Universitas Tiara Bunda.', 'blocks' => [
                ['type' => 'text', 'isi' => ['Tulis sejarah berdirinya kampus di sini.']],
            ]],
            'visi-misi' => [
                't'   => 'Visi & Misi',
                'sub' => 'Arah dan tujuan kampus.',
                'blocks' => [
                    [
                        'type' => 'text',
                        't'    => 'Visi',
                        'isi'  => [
                            'Menjadi Perguruan Tinggi Vokasi Unggul yang Menghasilkan Lulusan Kompeten dan Inovatif dalam Pemecahan Masalah Kesehatan di Jawa Barat Tahun 2032'
                        ]
                    ],
                    [
                        'type' => 'text',
                        't'    => 'Misi',
                        'daftar' => [
                            'Menyelenggarakan pendidikan serta pengajaran unggul yang menghasilkan lulusan kompeten dan inovatif dalam pemecahan masalah kesehatan.',
                            'Menyelenggarakan penelitian untuk pengembangan serta pemanfaatan ilmu pengetahuan dan teknologi kesehatan dalam pemecahan masalah kesehatan.',
                            'Menyelenggarakan pengabdian kepada masyarakat melalui pemanfaatan hasil penelitian untuk meningkatkan kualitas kesehatan masyarakat sesuai dengan evidence based.',
                            'Menyelenggarakan tata kelola yang sehat, transparan, berkualitas, dan akuntabel sebagai wujud penyelenggaraan institusi yang sehat.',
                            'Menyelenggarakan kerjasama dengan pemerintah, perguruan tinggi dan industri baik dalam maupun luar negeri untuk mendukung pelaksanaan tri dharma perguruan tinggi.',
                        ]
                    ],
                ]
            ],
            // 'pimpinan' => ['t' => 'Pimpinan Universitas', 'sub' => 'Pimpinan yang memandu kampus.', 'blocks' => [
            //     ['type' => 'sambutan'],
            // ]],
            // 'struktur' => ['t' => 'Struktur Organisasi', 'sub' => 'Bagan organisasi kampus.', 'blocks' => [
            //     ['type' => 'text', 'isi' => ['Tambahkan bagan struktur organisasi.']],
            // ]],
        ]],
        'pendidikan' => ['t' => 'Pendidikan', 'subs' => [
            'program-pendidikan' => ['t' => 'Program Pendidikan', 'sub' => 'Fakultas dan program studi.', 'blocks' => [
                ['type' => 'prodi'],
            ]],
            'penerimaan-mahasiswa-baru' => ['t' => 'Penerimaan Mahasiswa Baru', 'sub' => 'Alur, persyaratan, dan jalur pendaftaran.', 'blocks' => [
                ['type' => 'text', 't' => 'Alur pendaftaran', 'daftar' => ['Buat akun PMB', 'Lengkapi data diri', 'Unggah dokumen', 'Ikuti seleksi', 'Daftar ulang']],
                ['type' => 'text', 't' => 'Persyaratan', 'daftar' => ['Ijazah/SKL', 'Pas foto terbaru', 'Fotokopi KTP/KK']],
                ['type' => 'jalur'],
            ]],
            'beasiswa' => ['t' => 'Beasiswa', 'sub' => 'Dukungan biaya pendidikan.', 'blocks' => [
                ['type' => 'text', 'isi' => ['KIP Kuliah, beasiswa prestasi, dan keringanan untuk keluarga alumni.']],
            ]],
            'akreditasi' => ['t' => 'Akreditasi', 'sub' => 'Status akreditasi institusi dan program studi.', 'blocks' => [
                ['type' => 'text', 't' => 'Akreditasi Institusi', 'isi' => ['Isi status dan nomor SK akreditasi institusi.']],
                ['type' => 'text', 't' => 'Akreditasi Program Studi', 'isi' => ['Daftar akreditasi tiap program studi.']],
            ]],
        ]],
        'layanan' => ['t' => 'Layanan', 'subs' => [
            'perpustakaan' => ['t' => 'Perpustakaan', 'sub' => 'Koleksi dan jam layanan.', 'blocks' => [
                ['type' => 'text', 'isi' => ['Jam layanan dan koleksi perpustakaan.']],
            ]],
            'kemahasiswaan' => ['t' => 'Kemahasiswaan', 'sub' => 'Organisasi dan kegiatan mahasiswa.', 'blocks' => [
                ['type' => 'text', 'isi' => ['Organisasi, kegiatan, dan beasiswa.']],
            ]],
            'pusat-karier' => ['t' => 'Pusat Karier', 'sub' => 'Pendampingan sampai bekerja.', 'blocks' => [
                ['type' => 'text', 'isi' => ['Bursa kerja, pelatihan CV, dan penyaluran lulusan.']],
            ]],
            // 'dokumen' => ['t' => 'Dokumen', 'sub' => 'Unduh panduan dan kalender akademik.', 'blocks' => [
            //     ['type' => 'text', 't' => 'Kalender Akademik', 'isi' => ['Tautan unduhan kalender akademik.']],
            //     ['type' => 'text', 't' => 'Panduan Akademik', 'isi' => ['Tautan unduhan panduan akademik.']],
            // ]],
            'fasilitas' => ['t' => 'Fasilitas', 'sub' => 'Ruang belajar yang mendukung praktik.', 'blocks' => [
                ['type' => 'fasilitas'],
            ]],
        ]],
        'informasi' => ['t' => 'Informasi', 'subs' => [
            'berita-terbaru' => ['t' => 'Berita Terbaru', 'sub' => 'Kabar terbaru dari kampus.', 'blocks' => [
                ['type' => 'berita'],
            ]],
            'kegiatan-kampus' => ['t' => 'Kegiatan Kampus', 'sub' => 'Agenda dan kegiatan kampus.', 'blocks' => [
                ['type' => 'text', 'isi' => ['Daftar kegiatan dan agenda kampus.']],
            ]],
            'faq' => ['t' => 'FAQ', 'sub' => 'Pertanyaan yang sering diajukan.', 'blocks' => [
                ['type' => 'faq'],
            ]],
        ]],
    ],
    'home' => [
        'ticker' => ['Pendaftaran gelombang 1 dibuka', 'Kelas praktik berbasis industri', 'Beasiswa prestasi & KIP Kuliah', 'Kampus nyaman di Kota Depok'],
        'info' => [
            ['k' => 'PMB', 't' => 'Brosur PMB Gelombang 1 2026/2027', 'd' => 'Jadwal, biaya, dan jalur pendaftaran mahasiswa baru.', 'img' => 'assets/img/brosur.webp', 'tgl' => '20 Sep 2026'],
            ['k' => 'PMB', 't' => 'Brosur Beasiswa & KIP Kuliah',     'd' => 'Syarat dan alur pengajuan beasiswa.',               'img' => 'assets/img/info/brosur-beasiswa.webp', 'tgl' => '20 Sep 2026'],
            ['k' => 'SK',  't' => 'SK Pendirian Universitas',         'd' => 'Dokumen legalitas Universitas Tiara Bunda.',        'img' => 'assets/img/info/sk-universitas.webp', 'tgl' => '10 Agu 2026'],
            ['k' => 'SK',  't' => 'SK Fakultas Kesehatan',            'd' => 'Penetapan Fakultas Kesehatan.',                     'img' => 'assets/img/info/sk-fakultas-kes.webp', 'tgl' => '10 Agu 2026'],
            ['k' => 'SK',  't' => 'SK Program Studi Kebidanan',       'd' => 'Izin penyelenggaraan program studi.',               'img' => 'assets/img/info/sk-prodi-kebidanan.webp', 'tgl' => '10 Agu 2026'],
        ],
        'mitra' => ['Mitra Industri A', 'Rumah Sakit B', 'Perusahaan Teknologi C', 'Bank D', 'Lembaga Sertifikasi E', 'Komunitas F'],
        'rektor' => [
            'nama' => 'Dr. Bdn. Lusy Pratiwi, S.Tr.Keb., M.K.M.',
            'jabatan' => 'Direktur Universitas Tiara Bunda',
            'kutipan' => 'Sebagai Direktur, saya bangga memimpin kampus Universitas Tiara Bunda yang berkomitmen untuk memberikan pendidikan berkualitas dan berorientasi pada kebutuhan industri dan masyarakat. Universitas Tiara Bunda sebagai Perguruan Tinggi Vokasi mencetak lulusan siap kerja dan akan menghantarkan hingga pintu gerbang kesuksesan. Kami berharap dapat menjalin kerja sama yang baik dengan seluruh stakeholder di Universitas Tiara Bunda dan masyarakat luas untuk meraih prestasi yang lebih tinggi.',
        ],
        'alasan' => [
            [
                't' => 'Praktik lebih banyak',
                'd' => 'Kurikulum vokasi dengan porsi praktik besar di laboratorium dan studio, disusun bersama mitra industri.',
                'p' => ['Laboratorium aktif', 'Magang terjadwal', 'Dosen praktisi'],
            ],
            [
                't' => 'Kampus yang mudah dijangkau',
                'd' => 'Berada di Kota Depok dengan akses KRL dan angkutan umum, sehingga nyaman untuk mahasiswa dari Jabodetabek.',
                'p' => ['Dekat stasiun', 'Kelas pagi & sore', 'Area belajar bersama'],
            ],
            [
                't' => 'Pendampingan sampai bekerja',
                'd' => 'Pusat karier membantu CV, simulasi wawancara, dan penyaluran lulusan ke perusahaan mitra.',
                'p' => ['Bursa kerja', 'Sertifikasi kompetensi', 'Jejaring alumni'],
            ],
        ],
        'statistik' => [
            [92, '%', 'Lulusan bekerja kurang dari 6 bulan'],
            [120, '+', 'Mitra industri'],
            [3, '', 'Fakultas'],
            [1500, '+', 'Alumni'],
        ],
        'fakultas' => ['Ilmu Kesehatan', 'Keguruan dan Ilmu Pendidikan', 'Ilmu Komputer', 'Ekonomi Bisnis'],
        'prodi' => [
            [
                'nama' => 'Farmasi',
                'jenjang' => 'D3',
                'gelar' => 'A.Md.Farm',
                'fakultas' => 0
            ],
            [
                'nama' => 'Kebidanan',
                'jenjang' => 'D3',
                'gelar' => 'A.Md.Keb',
                'fakultas' => 0
            ],
            [
                'nama' => 'Keperawatan Bidan Anestesiologi',
                'jenjang' => 'D4',
                'gelar' => 'S.Tr.Kes',
                'fakultas' => 0
            ],
            [
                'nama' => 'Kebidanan',
                'jenjang' => 'D4',
                'gelar' => 'S.Tr.Keb',
                'fakultas' => 0
            ],
            [
                'nama' => 'Pendidikan Profesi Bidan',
                'jenjang' => '',
                'gelar' => 'Bdn',
                'fakultas' => 0
            ],
            [
                'nama' => 'Pendidikan Guru PAUD',
                'jenjang' => 'D3',
                'gelar' => 'S.Pd',
                'fakultas' => 1
            ],
            [
                'nama' => 'Pendidikan Guru Sekolah Dasar (PGSD)',
                'jenjang' => 'D3',
                'gelar' => 'S.Pd',
                'fakultas' => 1
            ],
            [
                'nama' => 'Teknologi Perangkat Lunak',
                'jenjang' => 'D4',
                'gelar' => 'S.Tr.Kom',
                'fakultas' => 2
            ],
            [
                'nama' => 'Sistem Informasi',
                'jenjang' => 'S1',
                'gelar' => 'S.Kom',
                'fakultas' => 2
            ],
            [
                'nama' => 'Kecerdasan Buatan',
                'jenjang' => 'S1',
                'gelar' => 'S.Kom',
                'fakultas' => 2
            ],
            [
                'nama' => 'Ilmu Komputer',
                'jenjang' => 'S1',
                'gelar' => 'S.Kom',
                'fakultas' => 2
            ],
            [
                'nama' => 'Logistik Bisnis',
                'jenjang' => 'D4',
                'gelar' => 'S.Tr.Log',
                'fakultas' => 3
            ],
            [
                'nama' => 'Bisnis Digital',
                'jenjang' => 'D4',
                'gelar' => 'S.Tr.Bns',
                'fakultas' => 3
            ],
            [
                'nama' => 'Manajemen',
                'jenjang' => 'S1',
                'gelar' => 'S.M',
                'fakultas' => 3
            ],
        ],
        'fasilitas' => [
            ['Laboratorium Komputer', 'assets/img/fasilitas/lab-komputer.jpg'],
            ['Perpustakaan', 'assets/img/fasilitas/perpustakaan.jpg'],
            ['Ruang Kelas', 'assets/img/fasilitas/ruang-kelas.jpg'],
            ['Studio Praktik', 'assets/img/fasilitas/studio-praktik.jpg'],
            ['Area Komunitas', 'assets/img/fasilitas/area-komunitas.jpg'],
        ],
        'berita' => [
            [
                'j' => 'Kegiatan',
                't' => 'Mahasiswa mengikuti kunjungan industri ke perusahaan mitra',
                'tgl' => '12 Okt 2026',
            ],
            [
                'j' => 'Prestasi',
                't' => 'Tim mahasiswa meraih juara pada lomba inovasi tingkat regional',
                'tgl' => '5 Okt 2026',
            ],
            [
                'j' => 'Akademik',
                't' => 'Jadwal pengisian KRS semester genap dibuka bulan depan',
                'tgl' => '28 Sep 2026',
            ],
            [
                'j' => 'PMB',
                't' => 'Pendaftaran mahasiswa baru gelombang 1 resmi dibuka',
                'tgl' => '20 Sep 2026',
            ],
        ],
        'jalur' => [
            ['PMB Dini', 'Daftar lebih awal untuk potongan biaya pendaftaran sesuai peringkat prestasi.'],
            ['Prestasi', 'Seleksi nilai rapor dan prestasi akademik maupun non-akademik.'],
            ['Reguler', 'Seleksi tes tulis atau CBT di kampus, tersedia kelas pagi dan sore.'],
            ['Beasiswa', 'KIP Kuliah, beasiswa prestasi, dan keringanan untuk keluarga alumni.'],
        ],
        'faq' => [
            ['Bagaimana cara mendaftar?', 'Isi email di formulir PMB, lengkapi data diri, unggah dokumen, lalu ikuti seleksi sesuai jalur yang dipilih.'],
            ['Apakah kampus sudah terakreditasi?', 'Informasi akreditasi institusi dan program studi tersedia di menu Akreditasi.'],
            ['Apakah ada kelas karyawan?', 'Ada kelas sore untuk program studi tertentu. Cek detailnya di halaman PMB.'],
            ['Bagaimana pembayaran biaya kuliah?', 'Pembayaran lewat virtual account dan dapat dicicil sesuai ketentuan. Rinciannya ada di halaman Biaya.'],
        ],
    ],
];
