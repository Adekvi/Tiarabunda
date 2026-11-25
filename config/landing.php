<?php

/* Seluruh konten landing. Dibaca oleh komponen Blade (beranda) dan data/*.js (halaman menu).
   Setelah mengubah: php artisan config:clear */
return [
    'site' => [
        'nama' => 'Universitas Tiara Bunda',
        'kota' => 'Kota Depok',
        'hotline' => '0811-1701-4000',
        'alamat' => 'Kota Depok, Jawa Barat',
        'portal' => [
            ['Mahasiswa', 'https://sima.politekniktiarabunda.ac.id/'],
            ['Dosen', '#'],
            ['Alumni', '#'],
            ['Portal', '#'],
        ],

        'footer' => [
            [
                'Pendaftaran Mahasiswa Baru',
                [
                    ['PMB', 'https://forms.gle/eDmoP7SdmJyS1YxD6']
                ]
            ],
            [
                'Tentang Kami',
                [
                    ['Sejarah', '/profil/sejarah'],
                    ['Visi & Misi', '/profil/visi-misi'],
                    ['Sambutan rektor', '/profil/sambutan'],
                    ['Struktur Organisasi', '/profil/struktur'],
                    ['Akreditasi', '/pendidikan/akreditasi'],
                ],
            ],
            [
                'Lembaga',
                [
                    ['LPPM', '/lembaga/lppm'],
                ],
            ],
            [
                'Pendidikan',
                [
                    ['Fakultas', '/pendidikan/program-pendidikan'],
                ],
            ],
            [
                'Layanan',
                [
                    ['Perpustakaan', '/layanan/perpustakaan'],
                    ['Kemahasiswaan', '/layanan/kemahasiswaan'],
                    ['Beasiswa', '/layanan/beasiswa'],
                    ['Pusat Karier', '/layanan/pusat-karier'],
                    ['Akademik', '/layanan/akademik'],
                    ['Fasilitas', '/layanan/fasilitas'],
                    ['Kerjasama', '/layanan/kerjasama'],
                ],
            ],
            [
                'Informasi',
                [
                    ['Berita Terbaru', '/informasi/berita-terbaru'],
                    ['Kegiatan Kampus', '/informasi/kegiatan-kampus'],
                    ['FAQ', '/informasi/faq'],
                ],
            ],
        ],

        'sosial' => [
            'youtube'  => 'https://www.youtube.com/@tiarabunda3025',
            'facebook' => 'https://www.facebook.com/PoliteknikTiaraBunda/',
            'tiktok'   => 'https://www.tiktok.com/@universitastiarabunda?lang=id-ID',
            'linktree' => 'https://linktr.ee/UNIVERSITASTIARABUNDA',
            'instagram' => 'https://www.instagram.com/universitastiarabunda/'
        ],

        'pmb' => '/pendidikan/penerimaan-mahasiswa-baru',
        'kontak' => [
            'alamat' => [
                [
                    'label' => 'Kampus A',
                    'isi'   => 'Jl. Cinere Raya Blok M No. 17, Cinere, Depok, Jawa Barat 16514',
                    'maps'  => 'https://maps.google.com/?q=Jl.+Cinere+Raya+Blok+M+No.+17+Cinere+Depok',
                ],
                [
                    'label' => 'Kampus B',
                    'isi'   => 'Jl. Galur No. 8 Krukut, Limo, Kota Depok, Jawa Barat 16512',
                    'maps'  => 'https://maps.google.com/?q=Jl.+Galur+No.+8+Krukut+Limo+Depok',
                ],
            ],
            'telp' => '0811-1701-4000',
            'email' => 'universitastiarabunda.ac.id',
            'wa'   => 'https://wa.me/6281117014000',
        ],
    ],
    'menus' => [
        'beranda' => [
            't'    => 'Beranda',
            'href' => '/',
        ],
        'pmb' => [
            't'    => 'Pendaftaran',
            'link' => 'https://forms.gle/eDmoP7SdmJyS1YxD6',
        ],
        'profil' => ['t' => 'Tentang Kami', 'subs' => [
            'sejarah' => [
                't'      => 'Sejarah',
                'sub'    => 'Perjalanan Universitas Tiara Bunda.',
                'blocks' => [
                    [
                        'type' => 'text',
                        'isi' => [
                            'Tulis sejarah berdirinya kampus di sini.'
                        ]
                    ],
                ]
            ],
            'visi-misi' => [
                't'         => 'Visi & Misi',
                'sub'       => 'Arah dan tujuan kampus.',
                'blocks'    => [
                    [
                        'type' => 'text',
                        't'    => 'Visi',
                        'isi'  => [
                            'Menjadi Perguruan Tinggi Vokasi Unggul yang Menghasilkan Lulusan Kompeten dan Inovatif dalam Pemecahan Masalah Kesehatan di Jawa Barat Tahun 2032'
                        ]
                    ],
                    [
                        'type'      => 'text',
                        't'         => 'Misi',
                        'daftar'    => [
                            'Menyelenggarakan pendidikan serta pengajaran unggul yang menghasilkan lulusan kompeten dan inovatif dalam pemecahan masalah kesehatan.',
                            'Menyelenggarakan penelitian untuk pengembangan serta pemanfaatan ilmu pengetahuan dan teknologi kesehatan dalam pemecahan masalah kesehatan.',
                            'Menyelenggarakan pengabdian kepada masyarakat melalui pemanfaatan hasil penelitian untuk meningkatkan kualitas kesehatan masyarakat sesuai dengan evidence based.',
                            'Menyelenggarakan tata kelola yang sehat, transparan, berkualitas, dan akuntabel sebagai wujud penyelenggaraan institusi yang sehat.',
                            'Menyelenggarakan kerjasama dengan pemerintah, perguruan tinggi dan industri baik dalam maupun luar negeri untuk mendukung pelaksanaan tri dharma perguruan tinggi.',
                        ]
                    ],
                ],
            ],
            'sambutan' => [
                't'      => 'Sambutan Rektor',
                'sub'    => 'Sambutan Pimpinan Universitas.',
                'blocks' => [
                    ['type' => 'sambutan'],
                ],
            ],
            'struktur' => [
                't' => 'Struktur Organisasi',
                'sub' => 'Bagan organisasi kampus.',
                'blocks' => [
                    [
                        'type' => 'text',
                        'isi' => ['Tambahkan bagan struktur organisasi.']
                    ],
                ]
            ],
            'akreditasi' =>
            [
                't' => 'Akreditasi',
                'sub' => 'Status akreditasi institusi dan program studi.',
                'blocks' => [
                    [
                        'type' => 'text',
                        't' => 'Akreditasi Institusi',
                        'isi' => ['Isi status dan nomor SK akreditasi institusi.']
                    ],
                    [
                        'type' => 'text',
                        't' => 'Akreditasi Program Studi',
                        'isi' => ['Daftar akreditasi tiap program studi.']
                    ],
                ]
            ],
        ]],
        'lembaga' => ['t' => 'Lembaga', 'subs' => [
            'lppm' => [
                't' => 'LPPM',
                'sub' => 'Lembaga Penelitian dan Pengabdian kepada Masyarakat.',
                'blocks' => [
                    [
                        'type' => 'lpipm',
                    ],
                ],
            ],
            'lpm' => [
                't' => 'LPM',
                'sub' => 'Lembaga Pemberdayaan Masyarakat.',
                'blocks' => [
                    [
                        'type' => 'lpm',
                    ],
                ],
            ],
        ]],
        'pendidikan' => ['t' => 'Pendidikan', 'subs' => [
            'program-pendidikan' => [
                't'      => 'Fakultas',
                'sub'    => 'Fakultas dan program studi.',
                'blocks' => [
                    [
                        'type' => 'prodi'
                    ],
                ]
            ],
        ]],
        'layanan' => ['t' => 'Layanan', 'subs' => [
            'perpustakaan' => [
                't' => 'Perpustakaan',
                'sub' => 'Koleksi dan jam layanan.',
                'blocks' => [
                    [
                        'type' => 'text',
                        'isi' => ['Jam layanan dan koleksi perpustakaan.']
                    ],
                ]
            ],
            'kemahasiswaan' => [
                't' => 'Kemahasiswaan',
                'sub' => 'Organisasi dan kegiatan mahasiswa.',
                'blocks' => [
                    [
                        'type' => 'text',
                        'isi' => ['Organisasi dan kegiatan mahasiswa.']
                    ],
                ]
            ],
            'beasiswa' => [
                't'      => 'Beasiswa',
                'sub'    => 'Program bantuan biaya pendidikan dan beasiswa mahasiswa.',
                'blocks' => [
                    [
                        'type' => 'text',
                        't'    => 'Beasiswa',
                        'isi'  => ['KIP Kuliah, beasiswa prestasi, dan keringanan untuk keluarga alumni.']
                    ],
                ]
            ],
            'pusat-karier' => [
                't' => 'Pusat Karier',
                'sub' => 'Pendampingan sampai bekerja.',
                'blocks' => [
                    [
                        'type' => 'text',
                        'isi' => ['Bursa kerja, pelatihan CV, dan penyaluran lulusan.']
                    ],
                ]
            ],
            'akademik' => [
                't'      => 'Akademik',
                'sub'    => 'Pengelola administrasi nilai, KRS, surat keterangan, dan kelulusan.',
                'blocks' => [
                    [
                        'type' => 'akademik',
                    ],
                ],
            ],
            'fasilitas' => ['t' => 'Fasilitas', 'sub' => 'Ruang belajar yang mendukung praktik.', 'blocks' => [
                [
                    'type' => 'fasilitas'
                ],
            ]],
            'kerjasama' => [
                't'      => 'Kerjasama',
                'sub'    => 'Kemitraan strategis dengan industri, pemerintah, dan institusi.',
                'blocks' => [
                    [
                        'type' => 'text',
                        't'    => 'Kerjasama',
                        'isi'  => ['Informasi kemitraan industri, magang, penelitian bersama, dan jejaring institusi Universitas Tiara Bunda.']
                    ],
                ]
            ],
        ]],
        'informasi' => ['t' => 'Informasi', 'subs' => [
            'berita-terbaru' => ['t' => 'Berita Terbaru', 'sub' => 'Kabar terbaru dari kampus.', 'blocks' => [
                [
                    'type' => 'berita'
                ],
            ]],
            'kegiatan-kampus' => ['t' => 'Kegiatan Kampus', 'sub' => 'Agenda dan kegiatan kampus.', 'blocks' => [
                [
                    'type' => 'text',
                    'isi' => ['Daftar kegiatan dan agenda kampus.']
                ],
            ]],
            'faq' => ['t' => 'FAQ', 'sub' => 'Pertanyaan yang sering diajukan.', 'blocks' => [
                [
                    'type' => 'faq'
                ],
            ]],
        ]],
    ],
    'home' => [
        'ticker' => ['Pendaftaran Mahasiswa Baru dibuka', 'Kelas praktik berbasis industri', 'Beasiswa prestasi & KIP Kuliah', 'Kampus nyaman di Kota Depok'],
        'info' => [
            ['k' => 'PMB', 't' => 'Brosur PMB 2026/2027', 'd' => 'Jadwal, biaya, dan jalur pendaftaran mahasiswa baru.', 'img' => 'assets/img/brosur.webp', 'tgl' => '20 Sep 2026'],
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
            [40, '+', 'Mitra industri'],
            [4, '', 'Fakultas'],
            [1000, '+', 'Alumni'],
        ],
        'fakultas' => ['Ilmu Kesehatan', 'Keguruan dan Ilmu Pendidikan', 'Ilmu Komputer', 'Ekonomi Bisnis'],
        'prodi' => [
            // ===== Fakultas Ilmu Kesehatan =====
            [
                'nama' => 'Farmasi',
                'jenjang' => 'D3',
                'gelar' => 'A.Md.Farm',
                'fakultas' => 0,
                'tema' => 'stabilo'
            ],
            [
                'nama' => 'Kebidanan',
                'jenjang' => 'D3',
                'gelar' => 'A.Md.Keb',
                'fakultas' => 0,
                'tema' => 'biru'
            ],
            [
                'nama' => 'Keperawatan Bidan Anestesiologi',
                'jenjang' => 'D4',
                'gelar' => 'S.Tr.Kes',
                'fakultas' => 0,
                'tema' => 'hijau-tua'
            ],
            [
                'nama' => 'Kebidanan',
                'jenjang' => 'D4',
                'gelar' => 'S.Tr.Keb',
                'fakultas' => 0,
                'tema' => 'biru-muda'
            ],
            [
                'nama' => 'Pendidikan Profesi Bidan',
                'jenjang' => '',
                'gelar' => 'Bdn',
                'fakultas' => 0,
                'tema' => 'marun'
            ],

            // ===== Fakultas Keguruan dan Ilmu Pendidikan =====
            [
                'nama' => 'Pendidikan Guru PAUD',
                'jenjang' => 'D3',
                'gelar' => 'S.Pd',
                'fakultas' => 1,
                'tema' => 'mawar'
            ],
            [
                'nama' => 'Pendidikan Guru Sekolah Dasar (PGSD)',
                'jenjang' => 'D3',
                'gelar' => 'S.Pd',
                'fakultas' => 1,
                'tema' => 'amber'
            ],

            // ===== Fakultas Ilmu Komputer =====
            [
                'nama' => 'Teknologi Perangkat Lunak',
                'jenjang' => 'D4',
                'gelar' => 'S.Tr.Kom',
                'fakultas' => 2,
                'tema' => 'indigo'
            ],
            [
                'nama' => 'Sistem Informasi',
                'jenjang' => 'S1',
                'gelar' => 'S.Kom',
                'fakultas' => 2,
                'tema' => 'teal'
            ],
            [
                'nama' => 'Kecerdasan Buatan',
                'jenjang' => 'S1',
                'gelar' => 'S.Kom',
                'fakultas' => 2,
                'tema' => 'violet'
            ],
            [
                'nama' => 'Ilmu Komputer',
                'jenjang' => 'S1',
                'gelar' => 'S.Kom',
                'fakultas' => 2,
                'tema' => 'slate'
            ],

            // ===== Fakultas Ekonomi Bisnis =====
            [
                'nama' => 'Logistik Bisnis',
                'jenjang' => 'D4',
                'gelar' => 'S.Tr.Log',
                'fakultas' => 3,
                'tema' => 'terakota'
            ],
            [
                'nama' => 'Bisnis Digital',
                'jenjang' => 'D4',
                'gelar' => 'S.Tr.Bns',
                'fakultas' => 3,
                'tema' => 'anggrek'
            ],
            [
                'nama' => 'Manajemen',
                'jenjang' => 'S1',
                'gelar' => 'S.M',
                'fakultas' => 3,
                'tema' => 'taupe'
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
                't' => 'Pendaftaran mahasiswa baru resmi dibuka',
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
    'welcome_popup' => [
        'enabled' => true,
        'badge' => 'Informasi Penerimaan Mahasiswa Baru',
        'title' => 'Selamat Datang di Politeknik Tiara Bunda',
        'subtitle' => 'Temukan jalur pendaftaran, pilihan program studi yang sesuai minat, hingga peluang beasiswa kuliah.',
        'banner_img' => 'landing/assets/img/popup-pmb.webp',
        'brochure_url' => '/layanan/akademik',
        'register_url' => 'https://poltektiarabunda.pmbonline.siakad.tech/register',
        'items' => [
            [
                'key' => 'pendaftaran',
                'tag' => 'PMB & Brosur',
                'title' => 'Pendaftaran & Unduh Brosur',
                'desc' => 'Jadwal gelombang pendaftaran, syarat masuk, rincian biaya kuliah, dan brosur resmi.',
                'cta' => 'Daftar / Lihat Brosur',
                'url' => 'https://poltektiarabunda.pmbonline.siakad.tech/register',
                'external' => true,
                'accent' => 'blue',
            ],
            [
                'key' => 'jurusan',
                'tag' => 'Konsultasi Prodi',
                'title' => 'Temukan Jurusan yang Cocok',
                'desc' => 'Eksplorasi fakultas, jenjang D3/D4, prospek karier lulusan, dan kurikulum tiap prodi.',
                'cta' => 'Pilih Jurusan',
                'url' => '/pendidikan/program-pendidikan',
                'external' => false,
                'accent' => 'sky',
            ],
            [
                'key' => 'beasiswa',
                'tag' => 'Bantuan Biaya',
                'title' => 'Program Beasiswa Kuliah',
                'desc' => 'Informasi KIP-Kuliah, beasiswa prestasi akademik, serta keringanan biaya pendidikan.',
                'cta' => 'Cek Beasiswa',
                'url' => '/layanan/beasiswa',
                'external' => false,
                'accent' => 'orange',
            ],
            [
                'key' => 'info',
                'tag' => 'Pusat Informasi',
                'title' => 'Panduan & Informasi Lainnya',
                'desc' => 'Kalender akademik, fasilitas kampus, kontak layanan admisi, dan pertanyaan umum (FAQ).',
                'cta' => 'Jelajahi Informasi',
                'url' => '/informasi/faq',
                'external' => false,
                'accent' => 'navy',
            ],
        ],
    ],
];
