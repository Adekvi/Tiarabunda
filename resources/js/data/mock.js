/* Data tiruan (mock). Nilai awal disalin dari config/landing.php.
   Nanti ganti dengan respons API Laravel, mis. GET /api/superadmin/konten/{slug}. */
const faqRows = [
    { q: 'Bagaimana cara mendaftar?', a: 'Isi email di formulir PMB, lengkapi data diri, unggah dokumen, lalu ikuti seleksi.' },
    { q: 'Apakah kampus sudah terakreditasi?', a: 'Informasi akreditasi tersedia di menu Akreditasi.' },
    { q: 'Apakah ada kelas karyawan?', a: 'Ada kelas sore untuk program studi tertentu.' },
    { q: 'Bagaimana pembayaran biaya kuliah?', a: 'Lewat virtual account dan dapat dicicil sesuai ketentuan.' },
];
const textCols = (...k) => k.map(([key, label, type]) => ({ key, label, type: type || 'text' }));

/* type 'form' = satu set isian; type 'list' = daftar yang bisa ditambah/ubah/hapus */
export const CONTENT = {
    hero: { title: 'Hero & Ticker', desc: 'Judul utama, teks pembuka, dan kalimat berjalan di bagian paling atas Beranda.', type: 'form',
        fields: textCols(['judul', 'Judul utama'], ['lead', 'Teks pembuka', 'area'], ['t1', 'Ticker 1'], ['t2', 'Ticker 2'], ['t3', 'Ticker 3'], ['t4', 'Ticker 4']),
        values: { judul: 'Kuliah vokasi yang langsung membawamu ke dunia kerja', lead: 'Universitas Tiara Bunda menyiapkan lulusan terampil lewat praktik, magang, dan pendampingan karier.', t1: 'Pendaftaran gelombang 1 dibuka', t2: 'Kelas praktik berbasis industri', t3: 'Beasiswa prestasi & KIP Kuliah', t4: 'Kampus nyaman di Kota Depok' } },
    mitra: { title: 'Mitra', desc: 'Nama mitra yang berjalan di pita Beranda.', type: 'list', cols: textCols(['nama', 'Nama mitra']),
        rows: ['Mitra Industri A', 'Rumah Sakit B', 'Perusahaan Teknologi C', 'Bank D', 'Lembaga Sertifikasi E', 'Komunitas F'].map((nama) => ({ nama })) },
    sambutan: { title: 'Sambutan Pimpinan', desc: 'Kutipan dan identitas pimpinan.', type: 'form',
        fields: textCols(['nama', 'Nama & gelar'], ['jabatan', 'Jabatan'], ['kutipan', 'Kutipan sambutan', 'area']),
        values: { nama: 'Dr. Bdn. Lusy Pratiwi, S.Tr.Keb., M.K.M.', jabatan: 'Direktur Universitas Tiara Bunda', kutipan: 'Sebagai Direktur, saya bangga memimpin kampus yang berkomitmen memberikan pendidikan berkualitas dan berorientasi pada kebutuhan industri dan masyarakat.' } },
    alasan: { title: 'Alasan Memilih', desc: 'Tiga tab "Mengapa memilih kami".', type: 'list', cols: textCols(['t', 'Judul'], ['d', 'Deskripsi', 'area'], ['p', 'Poin (pisahkan koma)']),
        rows: [
            { t: 'Praktik lebih banyak', d: 'Kurikulum vokasi dengan porsi praktik besar di laboratorium dan studio.', p: 'Laboratorium aktif, Magang terjadwal, Dosen praktisi' },
            { t: 'Kampus yang mudah dijangkau', d: 'Berada di Kota Depok dengan akses KRL dan angkutan umum.', p: 'Dekat stasiun, Kelas pagi & sore, Area belajar bersama' },
            { t: 'Pendampingan sampai bekerja', d: 'Pusat karier membantu CV, simulasi wawancara, dan penyaluran lulusan.', p: 'Bursa kerja, Sertifikasi kompetensi, Jejaring alumni' },
        ] },
    statistik: { title: 'Statistik', desc: 'Angka yang dihitung naik pada Beranda.', type: 'list', cols: textCols(['angka', 'Angka', 'number'], ['sufiks', 'Sufiks (%, +)'], ['label', 'Keterangan']),
        rows: [{ angka: 92, sufiks: '%', label: 'Lulusan bekerja kurang dari 6 bulan' }, { angka: 120, sufiks: '+', label: 'Mitra industri' }, { angka: 3, sufiks: '', label: 'Fakultas' }, { angka: 1500, sufiks: '+', label: 'Alumni' }] },
    prodi: { title: 'Program Studi', desc: 'Daftar program studi per fakultas.', type: 'list', cols: textCols(['nama', 'Program studi'], ['jenjang', 'Jenjang'], ['fakultas', 'Fakultas']),
        rows: [
            ['Teknik Informatika', 'D3', 'Teknologi & Informatika'], ['Sistem Informasi', 'D3', 'Teknologi & Informatika'], ['Rekayasa Perangkat Lunak', 'D4', 'Teknologi & Informatika'],
            ['Akuntansi', 'D3', 'Bisnis & Manajemen'], ['Manajemen Bisnis', 'D3', 'Bisnis & Manajemen'], ['Administrasi Perkantoran', 'D3', 'Bisnis & Manajemen'],
            ['Keperawatan', 'D3', 'Kesehatan'], ['Kebidanan', 'D3', 'Kesehatan'], ['Rekam Medis', 'D3', 'Kesehatan'],
        ].map(([nama, jenjang, fakultas]) => ({ nama, jenjang, fakultas })) },
    fasilitas: { title: 'Fasilitas', desc: 'Foto yang berjalan pada galeri fasilitas.', type: 'list', cols: textCols(['nama', 'Nama fasilitas'], ['gambar', 'Path gambar']),
        rows: ['lab-komputer', 'perpustakaan', 'ruang-kelas', 'studio-praktik', 'area-komunitas'].map((s) => ({ nama: s.replace('-', ' ').replace(/^./, (c) => c.toUpperCase()), gambar: `assets/img/fasilitas/${s}.jpg` })) },
    berita: { title: 'Berita', desc: 'Berita pertama tampil sebagai sorotan.', type: 'list', cols: textCols(['j', 'Kategori'], ['t', 'Judul'], ['tgl', 'Tanggal']),
        rows: [
            { j: 'Kegiatan', t: 'Mahasiswa mengikuti kunjungan industri ke perusahaan mitra', tgl: '12 Okt 2026' },
            { j: 'Prestasi', t: 'Tim mahasiswa meraih juara pada lomba inovasi tingkat regional', tgl: '5 Okt 2026' },
            { j: 'Akademik', t: 'Jadwal pengisian KRS semester genap dibuka bulan depan', tgl: '28 Sep 2026' },
            { j: 'PMB', t: 'Pendaftaran mahasiswa baru gelombang 1 resmi dibuka', tgl: '20 Sep 2026' },
        ] },
    jalur: { title: 'Jalur Masuk', desc: 'Kartu jalur pendaftaran.', type: 'list', cols: textCols(['nama', 'Jalur'], ['ket', 'Keterangan', 'area']),
        rows: [
            { nama: 'PMB Dini', ket: 'Daftar lebih awal untuk potongan biaya pendaftaran.' }, { nama: 'Prestasi', ket: 'Seleksi nilai rapor dan prestasi.' },
            { nama: 'Reguler', ket: 'Tes tulis atau CBT di kampus, kelas pagi dan sore.' }, { nama: 'Beasiswa', ket: 'KIP Kuliah, beasiswa prestasi, keringanan keluarga alumni.' },
        ] },
    faq: { title: 'FAQ', desc: 'Pertanyaan yang sering diajukan.', type: 'list', cols: textCols(['q', 'Pertanyaan'], ['a', 'Jawaban', 'area']), rows: faqRows },
    pmb: { title: 'Alur & Persyaratan PMB', desc: 'Isi halaman /pmb.', type: 'list', cols: textCols(['blok', 'Blok'], ['isi', 'Isi (pisahkan koma)', 'area']),
        rows: [{ blok: 'Alur pendaftaran', isi: 'Buat akun PMB, Lengkapi data diri, Unggah dokumen, Ikuti seleksi, Daftar ulang' }, { blok: 'Persyaratan', isi: 'Ijazah/SKL, Pas foto terbaru, Fotokopi KTP/KK' }] },
    akreditasi: { title: 'Akreditasi', desc: 'Status akreditasi institusi dan program studi.', type: 'form',
        fields: textCols(['inst', 'Akreditasi institusi'], ['sk', 'Nomor SK'], ['prodi', 'Akreditasi program studi', 'area']),
        values: { inst: 'Baik Sekali', sk: '', prodi: '' } },
    situs: { title: 'Menu & Kontak Situs', desc: 'Identitas, kontak, dan tautan footer.', type: 'form',
        fields: textCols(['nama', 'Nama kampus'], ['kota', 'Kota'], ['alamat', 'Alamat', 'area'], ['telp', 'Telepon'], ['email', 'Email'], ['wa', 'Tautan WhatsApp']),
        values: { nama: 'Universitas Tiara Bunda', kota: 'Kota Depok', alamat: 'Jl. Contoh Raya No. 1, Kota Depok, Jawa Barat', telp: '(021) 000-0000', email: 'info@tiarabunda.example', wa: 'https://wa.me/620000000000' } },
};
/* halaman teks panjang (Profil, Layanan, Informasi) memakai pola blok yang sama dengan data/*.js landing */
const blok = (title, desc, rows) => ({ title, desc, type: 'list', cols: textCols(['t', 'Judul blok'], ['isi', 'Isi', 'area']), rows });
Object.assign(CONTENT, {
    sejarah: blok('Sejarah', 'Halaman /profil#sejarah', [{ t: 'Sejarah', isi: 'Tulis sejarah berdirinya kampus di sini.' }]),
    visi: blok('Visi & Misi', 'Halaman /profil#visi', [{ t: 'Visi', isi: 'Tulis visi kampus.' }, { t: 'Misi', isi: 'Misi pertama, Misi kedua, Misi ketiga' }]),
    pimpinan: blok('Pimpinan Universitas', 'Halaman /profil#pimpinan', [{ t: 'Direktur', isi: 'Dr. Bdn. Lusy Pratiwi, S.Tr.Keb., M.K.M.' }]),
    struktur: blok('Struktur Organisasi', 'Halaman /profil#struktur', [{ t: 'Bagan', isi: 'Tambahkan bagan atau daftar pimpinan.' }]),
    beasiswa: blok('Beasiswa', 'Halaman /pendidikan#beasiswa', [{ t: 'KIP Kuliah', isi: 'Isi syarat dan cara mengajukan.' }]),
    pustaka: blok('Perpustakaan', 'Halaman /layanan#pustaka', [{ t: 'Jam layanan', isi: 'Jam layanan dan koleksi perpustakaan.' }]),
    mhs: blok('Kemahasiswaan', 'Halaman /layanan#mhs', [{ t: 'Organisasi', isi: 'Organisasi, kegiatan, dan beasiswa.' }]),
    karier: blok('Pusat Karier', 'Halaman /layanan#karier', [{ t: 'Bursa kerja', isi: 'Bursa kerja, pelatihan CV, dan penyaluran lulusan.' }]),
    dokumen: blok('Dokumen', 'Halaman /dokumen', [{ t: 'Kalender Akademik', isi: 'Tautan unduhan kalender akademik.' }, { t: 'Panduan Akademik', isi: 'Tautan unduhan panduan akademik.' }]),
    kegiatan: blok('Kegiatan Kampus', 'Halaman /informasi#kegiatan', [{ t: 'Kunjungan industri', isi: 'Ringkasan kegiatan.' }]),
});

export const STATS = [
    { label: 'Pendaftar PMB 2026/2027', value: 842, delta: '+18%', tone: 'blue' },
    { label: 'Halaman terbit', value: 14, delta: '2 draf', tone: 'orange' },
    { label: 'Pengunjung / hari', value: 3260, delta: '+7%', tone: 'blue' },
    { label: 'Pesan belum dibaca', value: 23, delta: 'baru', tone: 'orange' },
];
export const PENDAFTAR_BULANAN = [['Mei', 40], ['Jun', 95], ['Jul', 160], ['Agu', 210], ['Sep', 190], ['Okt', 147]];
export const JALUR_PCT = [['Reguler', 46], ['Prestasi', 27], ['PMB Dini', 19], ['Beasiswa', 8]];
export const AKTIVITAS = [
    ['Sri Wahyuni', 'mengubah Berita "KRS semester genap"', '10 menit lalu'],
    ['Budi Santoso', 'menambah foto di Galeri', '1 jam lalu'],
    ['Super Admin', 'memperbarui Sambutan Pimpinan', 'kemarin'],
    ['Dewi Lestari', 'menerbitkan Jalur Masuk "Prestasi"', 'kemarin'],
];
export const USERS = [
    { id: 1, name: 'Super Admin', email: 'superadmin@tiarabunda.test', role: 'superadmin', status: 'Aktif' },
    { id: 2, name: 'Sri Wahyuni', email: 'sri@tiarabunda.test', role: 'admin', status: 'Aktif' },
    { id: 3, name: 'Budi Santoso', email: 'budi@tiarabunda.test', role: 'admin', status: 'Aktif' },
    { id: 4, name: 'Dewi Lestari', email: 'dewi@tiarabunda.test', role: 'admin', status: 'Nonaktif' },
    { id: 5, name: 'Rina Kusuma', email: 'rina@mail.test', role: 'customer', status: 'Aktif' },
    { id: 6, name: 'Dimas Pratama', email: 'dimas@mail.test', role: 'customer', status: 'Menunggu verifikasi' },
    { id: 7, name: 'Salsabila Putri', email: 'salsa@mail.test', role: 'customer', status: 'Aktif' },
    { id: 8, name: 'Fajar Nugroho', email: 'fajar@mail.test', role: 'customer', status: 'Aktif' },
];
export const EVENTS = [
    { d: '2026-10-05', t: 'Lomba inovasi regional', c: 'orange' }, { d: '2026-10-12', t: 'Kunjungan industri', c: 'blue' },
    { d: '2026-10-20', t: 'Penutupan PMB gelombang 1', c: 'orange' }, { d: '2026-10-29', t: 'Rapat koordinasi konten', c: 'blue' },
    { d: '2026-11-03', t: 'Tes seleksi reguler (CBT)', c: 'orange' }, { d: '2026-11-15', t: 'Pengisian KRS genap dibuka', c: 'blue' },
];
export const GALLERY = [
    ['Laboratorium Komputer', 'Fasilitas'], ['Perpustakaan', 'Fasilitas'], ['Ruang Kelas', 'Fasilitas'], ['Studio Praktik', 'Fasilitas'],
    ['Kunjungan Industri', 'Kegiatan'], ['Wisuda Angkatan 5', 'Kegiatan'], ['Lomba Inovasi', 'Prestasi'], ['Praktik Klinik', 'Kegiatan'],
    ['Area Komunitas', 'Fasilitas'], ['Pelantikan BEM', 'Kegiatan'], ['Juara Regional', 'Prestasi'], ['Gedung Kampus A', 'Fasilitas'],
].map(([title, cat], id) => ({ id, title, cat, date: `${10 + (id % 18)} Okt 2026`, size: `${(1.2 + id * 0.37).toFixed(1)} MB` }));
export const HELP = [
    ['Bagaimana mengubah teks di Beranda?', 'Buka Konten website → Beranda, pilih seksi, ubah isian, lalu klik Simpan perubahan.'],
    ['Perubahan belum tampil di landing page?', 'Landing page membaca config/landing.php. Setelah konten tersinkron, jalankan php artisan config:clear.'],
    ['Apa beda Admin dan Customer?', 'Admin mengelola konten. Customer adalah calon mahasiswa yang membuat akun lewat formulir PMB.'],
    ['Bagaimana mengganti warna tema?', 'Ubah token --color-brand-* dan --color-accent-* pada resources/css/superadmin.css.'],
    ['Bagaimana mengaktifkan mode gelap?', 'Klik ikon bulan di bilah atas. Pilihan tersimpan di peramban.'],
];
