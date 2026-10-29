/* Semua konten ada di sini (contoh/placeholder) — ganti dengan data asli,
   atau nanti isi dari API/admin: window.SITE = await fetch('/api/site').then(r => r.json()) */
window.SITE = {
    nama: 'Universitas Tiara Bunda',
    kota: 'Kota Depok',
    hotline: '(021) 000-0000',
    alamat: 'Kota Depok, Jawa Barat',
    portal: [
        ['Mahasiswa', '#'],
        ['Dosen', '#'],
        ['Alumni', '#'],
        ['Portal', '#']
    ],
    menu: [
        {
            t: 'Profil',
            sub: [
                ['Sejarah', 'pages/profil.html#sejarah'],
                ['Visi & Misi', 'pages/profil.html#visi'],
                ['Pimipinan Universitas', 'pages/profil.html#pimpinan'],
                ['Struktur Organisasi', 'pages/profil.html#struktur']
            ]
        },
        {
            t: 'Pendidikan',
            sub: [
                ['Program Pendidikan', 'pages/pendidikan.html#tek'],
                ['Penerimaan Mahasiswa Baru', 'pages/pendidikan.html#pmb'],
                ['Beasiswa', 'pages/pendidikan.html#beasiswa']
            ]
        },
        {
            t: 'Layanan',
            sub: [
                ['Perpustakaan', 'pages/layanan.html#pustaka'],
                ['Kemahasiswaan', 'pages/layanan.html#mhs'],
                ['Pusat Karier', 'pages/layanan.html#karier'],
                ['Dokumen', 'pages/layanan.html#dokumen'],
                ['Fasilitas', 'pages/layanan.html#fasilitas']
            ]
        },
        {
            t: 'Informasi',
            sub: [
                ['Berita Terbaru', 'pages/informasi.html#berita'],
                ['Kegiatan Kampus', 'pages/informasi.html#kegiatan'][
                    ('FAQ', 'pages/informasi.html#faq')
                ]
            ]
        }
        // { t: 'PMB', href: 'pages/pmb.html' },
        // { t: 'Akreditasi', href: 'pages/akreditasi.html' }
    ],
    ticker: [
        'Pendaftaran gelombang 1 dibuka',
        'Kelas praktik berbasis industri',
        'Beasiswa prestasi & KIP Kuliah',
        'Kampus nyaman di Kota Depok'
    ],
    mitra: [
        'Mitra Industri A',
        'Rumah Sakit B',
        'Perusahaan Teknologi C',
        'Bank D',
        'Lembaga Sertifikasi E',
        'Komunitas F'
    ],
    rektor: {
        nama: 'Dr. Bdn. Lusy Pratiwi, S.Tr.Keb., M.K.M.',
        jabatan: 'Direktur Universitas Tiara Bunda',
        kutipan:
            'Sebagai Direktur, saya bangga memimpin kampus Universitas Tiara Bunda yang berkomitmen untuk memberikan pendidikan berkualitas dan berorientasi pada kebutuhan industri dan masyarakat. Universitas Tiara Bunda sebagai Perguruan Tinggi Vokasi mencetak lulusan siap kerja dan akan menghantarkan hingga pintu gerbang kesuksesan. Kami berharap dapat menjalin kerja sama yang baik dengan seluruh stakeholder di Universitas Tiara Bunda dan masyarakat luas untuk meraih prestasi yang lebih tinggi.'
    },
    alasan: [
        {
            t: 'Praktik lebih banyak',
            d: 'Kurikulum vokasi dengan porsi praktik besar di laboratorium dan studio, disusun bersama mitra industri.',
            p: ['Laboratorium aktif', 'Magang terjadwal', 'Dosen praktisi']
        },
        {
            t: 'Kampus yang mudah dijangkau',
            d: 'Berada di Kota Depok dengan akses KRL dan angkutan umum, sehingga nyaman untuk mahasiswa dari Jabodetabek.',
            p: ['Dekat stasiun', 'Kelas pagi & sore', 'Area belajar bersama']
        },
        {
            t: 'Pendampingan sampai bekerja',
            d: 'Pusat karier membantu CV, simulasi wawancara, dan penyaluran lulusan ke perusahaan mitra.',
            p: ['Bursa kerja', 'Sertifikasi kompetensi', 'Jejaring alumni']
        }
    ],
    statistik: [
        [92, '%', 'Lulusan bekerja kurang dari 6 bulan'],
        [120, '+', 'Mitra industri'],
        [3, '', 'Fakultas'],
        [1500, '+', 'Alumni']
    ],
    fakultas: ['Teknologi & Informatika', 'Bisnis & Manajemen', 'Kesehatan'],
    prodi: [
        ['Teknik Informatika', 'D3', 0],
        ['Sistem Informasi', 'D3', 0],
        ['Rekayasa Perangkat Lunak', 'D4', 0],
        ['Akuntansi', 'D3', 1],
        ['Manajemen Bisnis', 'D3', 1],
        ['Administrasi Perkantoran', 'D3', 1],
        ['Keperawatan', 'D3', 2],
        ['Kebidanan', 'D3', 2],
        ['Rekam Medis', 'D3', 2]
    ],
    fasilitas: [
        ['Laboratorium Komputer', 'assets/img/fasilitas/lab-komputer.jpg'],
        ['Perpustakaan', 'assets/img/fasilitas/perpustakaan.jpg'],
        ['Ruang Kelas', 'assets/img/fasilitas/ruang-kelas.jpg'],
        ['Studio Praktik', 'assets/img/fasilitas/studio-praktik.jpg'],
        ['Area Komunitas', 'assets/img/fasilitas/area-komunitas.jpg']
    ],
    berita: [
        {
            j: 'Kegiatan',
            t: 'Mahasiswa mengikuti kunjungan industri ke perusahaan mitra',
            tgl: '12 Okt 2026'
        },
        {
            j: 'Prestasi',
            t: 'Tim mahasiswa meraih juara pada lomba inovasi tingkat regional',
            tgl: '5 Okt 2026'
        },
        {
            j: 'Akademik',
            t: 'Jadwal pengisian KRS semester genap dibuka bulan depan',
            tgl: '28 Sep 2026'
        },
        {
            j: 'PMB',
            t: 'Pendaftaran mahasiswa baru gelombang 1 resmi dibuka',
            tgl: '20 Sep 2026'
        }
    ],
    jalur: [
        [
            'PMB Dini',
            'Daftar lebih awal untuk potongan biaya pendaftaran sesuai peringkat prestasi.'
        ],
        [
            'Prestasi',
            'Seleksi nilai rapor dan prestasi akademik maupun non-akademik.'
        ],
        [
            'Reguler',
            'Seleksi tes tulis atau CBT di kampus, tersedia kelas pagi dan sore.'
        ],
        [
            'Beasiswa',
            'KIP Kuliah, beasiswa prestasi, dan keringanan untuk keluarga alumni.'
        ]
    ],
    faq: [
        [
            'Bagaimana cara mendaftar?',
            'Isi email di formulir PMB, lengkapi data diri, unggah dokumen, lalu ikuti seleksi sesuai jalur yang dipilih.'
        ],
        [
            'Apakah kampus sudah terakreditasi?',
            'Informasi akreditasi institusi dan program studi tersedia di menu Akreditasi.'
        ],
        [
            'Apakah ada kelas karyawan?',
            'Ada kelas sore untuk program studi tertentu. Cek detailnya di halaman PMB.'
        ],
        [
            'Bagaimana pembayaran biaya kuliah?',
            'Pembayaran lewat virtual account dan dapat dicicil sesuai ketentuan. Rinciannya ada di halaman Biaya.'
        ]
    ],
    kontak: {
        alamat: 'Jl. Contoh Raya No. 1, Kota Depok, Jawa Barat',
        telp: '(021) 000-0000',
        email: 'info@tiarabunda.example',
        wa: 'https://wa.me/620000000000'
    },
    footer: [
        [
            'Akademik',
            [
                ['Fakultas & Prodi', 'pages/fakultas.html'],
                ['Kalender Akademik', 'pages/dokumen.html'],
                ['Perpustakaan', 'pages/layanan.html']
            ]
        ],
        [
            'Informasi',
            [
                ['PMB', 'pages/pmb.html'],
                ['Akreditasi', 'pages/akreditasi.html'],
                ['Berita', '#berita']
            ]
        ]
    ]
}
