<?php

/* Seluruh konten landing. Dibaca oleh komponen Blade (beranda) dan data/*.js (halaman menu).
   Setelah mengubah: php artisan config:clear */
return [
    'site' => [
        'nama' => 'Universitas Tiara Bunda',
        'kota' => 'Kota Depok',
        'alamat' => 'Kota Depok, Jawa Barat',
        'portal' => [
            ['Mahasiswa', '#'],
            ['Dosen', '#'],
            ['Alumni', '#'],
            ['Portal', '#'],
        ],
        'menu' => [
            ['t' => 'Profil', 'sub' => [
                ['Sejarah', '/profil#sejarah'],
                ['Visi & Misi', '/profil#visi'],
                ['Pimpinan Universitas', '/profil#pimpinan'],
                ['Struktur Organisasi', '/profil#struktur'],
            ]],
            ['t' => 'Pendidikan', 'sub' => [
                ['Program Pendidikan', '/pendidikan#prodi'],
                ['Penerimaan Mahasiswa Baru', '/pendidikan#pmb'],
                ['Beasiswa', '/pendidikan#beasiswa'],
            ]],
            ['t' => 'Layanan', 'sub' => [
                ['Perpustakaan', '/layanan#pustaka'],
                ['Kemahasiswaan', '/layanan#mhs'],
                ['Pusat Karier', '/layanan#karier'],
                ['Dokumen', '/layanan#dokumen'],
                ['Fasilitas', '/layanan#fasilitas'],
            ]],
            ['t' => 'Informasi', 'sub' => [
                ['Berita Terbaru', '/informasi#berita'],
                ['Kegiatan Kampus', '/informasi#kegiatan'],
                ['FAQ', '/informasi#faq'],
            ]],
        ],
        'footer' => [
            ['Akademik', [
                ['Program Studi', '/pendidikan#prodi'],
                ['Kalender Akademik', '/layanan#dokumen'],
                ['Perpustakaan', '/layanan#pustaka'],
            ]],
            ['Informasi', [
                ['PMB', '/pendidikan#pmb'],
                ['Akreditasi', '/pendidikan#akreditasi'],
                ['Berita', '/informasi#berita'],
            ]],
        ],
        'pmb' => '/pendidikan#pmb',
        'kontak' => [
            'alamat' => 'Jl. Contoh Raya No. 1, Kota Depok, Jawa Barat',
            'telp' => '(021) 000-0000',
            'email' => 'info@tiarabunda.example',
            'wa' => 'https://wa.me/620000000000',
        ],
    ],
    'pages' => [
        'profil' => [
            'hero' => ['kick' => 'Profil', 'judul' => 'Sejarah, Visi & Misi', 'sub' => 'Mengenal Universitas Tiara Bunda lebih dekat.'],
            'sections' => [
                ['type' => 'text', 'blok' => [
                    ['id' => 'sejarah', 't' => 'Sejarah', 'isi' => ['Tulis sejarah berdirinya kampus di sini.']],
                    ['id' => 'visi', 't' => 'Visi & Misi', 'isi' => ['Visi: tulis visi kampus.'], 'daftar' => ['Misi pertama', 'Misi kedua', 'Misi ketiga']],
                    ['id' => 'pimpinan', 't' => 'Pimpinan Universitas', 'isi' => ['Tambahkan daftar pimpinan universitas.']],
                    ['id' => 'struktur', 't' => 'Struktur Organisasi', 'isi' => ['Tambahkan bagan struktur organisasi.']],
                ]],
            ],
        ],
        'pendidikan' => [
            'hero' => ['kick' => 'Pendidikan', 'judul' => 'Program Pendidikan', 'sub' => 'Program studi, penerimaan mahasiswa baru, dan beasiswa.'],
            'sections' => [
                ['type' => 'prodi'],
                ['type' => 'text', 'blok' => [
                    ['id' => 'pmb', 't' => 'Penerimaan Mahasiswa Baru', 'isi' => ['Alur pendaftaran:'], 'daftar' => ['Buat akun PMB', 'Lengkapi data diri', 'Unggah dokumen', 'Ikuti seleksi', 'Daftar ulang']],
                    ['id' => 'beasiswa', 't' => 'Beasiswa', 'isi' => ['KIP Kuliah, beasiswa prestasi, dan keringanan untuk keluarga alumni.']],
                    ['id' => 'akreditasi', 't' => 'Akreditasi', 'isi' => ['Isi status dan nomor SK akreditasi institusi dan program studi.']],
                ]],
                ['type' => 'jalur'],
            ],
        ],
        'layanan' => [
            'hero' => ['kick' => 'Layanan', 'judul' => 'Layanan Kampus', 'sub' => 'Dukungan untuk mahasiswa selama belajar.'],
            'sections' => [
                ['type' => 'text', 'blok' => [
                    ['id' => 'pustaka', 't' => 'Perpustakaan', 'isi' => ['Jam layanan dan koleksi perpustakaan.']],
                    ['id' => 'mhs', 't' => 'Kemahasiswaan', 'isi' => ['Organisasi, kegiatan, dan beasiswa.']],
                    ['id' => 'karier', 't' => 'Pusat Karier', 'isi' => ['Bursa kerja, pelatihan CV, dan penyaluran lulusan.']],
                    ['id' => 'dokumen', 't' => 'Dokumen', 'isi' => ['Tautan unduhan kalender akademik dan panduan akademik.']],
                ]],
                ['type' => 'fasilitas'],
            ],
        ],
        'informasi' => [
            'hero' => ['kick' => 'Informasi', 'judul' => 'Informasi Kampus', 'sub' => 'Berita, kegiatan, dan pertanyaan umum.'],
            'sections' => [
                ['type' => 'berita'],
                ['type' => 'text', 'blok' => [
                    ['id' => 'kegiatan', 't' => 'Kegiatan Kampus', 'isi' => ['Daftar kegiatan dan agenda kampus.']],
                ]],
                ['type' => 'faq'],
            ],
        ],
    ],
    'home' => [
        'ticker' => ['Pendaftaran gelombang 1 dibuka', 'Kelas praktik berbasis industri', 'Beasiswa prestasi & KIP Kuliah', 'Kampus nyaman di Kota Depok'],
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
        'fakultas' => ['Teknologi & Informatika', 'Bisnis & Manajemen', 'Kesehatan'],
        'prodi' => [
            ['Teknik Informatika', 'D3', 0],
            ['Sistem Informasi', 'D3', 0],
            ['Rekayasa Perangkat Lunak', 'D4', 0],
            ['Akuntansi', 'D3', 1],
            ['Manajemen Bisnis', 'D3', 1],
            ['Administrasi Perkantoran', 'D3', 1],
            ['Keperawatan', 'D3', 2],
            ['Kebidanan', 'D3', 2],
            ['Rekam Medis', 'D3', 2],
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
