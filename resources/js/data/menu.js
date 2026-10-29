/* Struktur menu dashboard. Mengikuti menu landing page (config/landing.php → site.menu):
   Profil, Pendidikan, Layanan, Informasi, PMB, Akreditasi — ditambah pengelolaan Beranda & sistem.
   Bentuk: { t: label, to: path, icon, children: [...] }  (kedalaman bebas) */
export const MENU = [
    { group: 'Utama' },
    { t: 'Dashboard', to: '/', icon: 'layout' },
    { group: 'Konten website' },
    {
        t: 'Beranda', icon: 'home', children: [
            { t: 'Hero & Ticker', to: '/konten/hero' },
            { t: 'Mitra', to: '/konten/mitra' },
            { t: 'Sambutan Pimpinan', to: '/konten/sambutan' },
            { t: 'Alasan Memilih', to: '/konten/alasan' },
            { t: 'Statistik', to: '/konten/statistik' },
            { t: 'Program Studi', to: '/konten/prodi' },
            { t: 'Fasilitas', to: '/konten/fasilitas' },
            { t: 'Berita', to: '/konten/berita' },
            { t: 'Jalur Masuk', to: '/konten/jalur' },
            { t: 'FAQ Beranda', to: '/konten/faq' },
        ],
    },
    {
        t: 'Profil', icon: 'building', children: [
            { t: 'Sejarah', to: '/konten/sejarah' },
            { t: 'Visi & Misi', to: '/konten/visi' },
            { t: 'Pimpinan Universitas', to: '/konten/pimpinan' },
            { t: 'Struktur Organisasi', to: '/konten/struktur' },
        ],
    },
    {
        t: 'Pendidikan', icon: 'cap', children: [
            { t: 'Program Pendidikan', to: '/konten/prodi' },
            {
                t: 'Penerimaan Mahasiswa Baru', children: [
                    { t: 'Alur & Persyaratan', to: '/konten/pmb' },
                    { t: 'Jalur Masuk', to: '/konten/jalur' },
                    { t: 'Pendaftar Masuk', to: '/pendaftar' },
                ],
            },
            { t: 'Beasiswa', to: '/konten/beasiswa' },
        ],
    },
    {
        t: 'Layanan', icon: 'service', children: [
            { t: 'Perpustakaan', to: '/konten/pustaka' },
            { t: 'Kemahasiswaan', to: '/konten/mhs' },
            { t: 'Pusat Karier', to: '/konten/karier' },
            { t: 'Dokumen', to: '/konten/dokumen' },
            { t: 'Fasilitas', to: '/konten/fasilitas' },
        ],
    },
    {
        t: 'Informasi', icon: 'news', children: [
            { t: 'Berita Terbaru', to: '/konten/berita' },
            { t: 'Kegiatan Kampus', to: '/konten/kegiatan' },
            { t: 'FAQ', to: '/konten/faq' },
        ],
    },
    { t: 'Akreditasi', to: '/konten/akreditasi', icon: 'award' },
    { t: 'Menu & Kontak Situs', to: '/konten/situs', icon: 'settings' },
    { group: 'Media & jadwal' },
    { t: 'Galeri', to: '/galeri', icon: 'image' },
    { t: 'Kalender', to: '/kalender', icon: 'calendar' },
    { group: 'Pengguna' },
    {
        t: 'Pengguna', icon: 'users', children: [
            { t: 'Admin', to: '/pengguna/admin' },
            { t: 'Customer (Calon Mahasiswa)', to: '/pengguna/customer' },
        ],
    },
    { group: 'Bantuan & contoh' },
    { t: 'Showcase Komponen', to: '/showcase', icon: 'sparkle' },
    { t: 'Pusat Bantuan (FAQ)', to: '/bantuan', icon: 'help' },
    { t: 'Dokumentasi', to: '/dokumentasi', icon: 'book' },
    {
        t: 'Halaman Contoh', icon: 'file', children: [
            {
                t: 'Autentikasi', children: [
                    { t: 'Masuk', to: '/masuk' },
                    { t: 'Daftar', to: '/daftar' },
                    { t: 'Lupa Password', to: '/lupa-password' },
                    { t: 'Reset Password', to: '/reset-password' },
                ],
            },
            {
                t: 'Halaman Error', children: [
                    { t: '403 Dilarang', to: '/403' },
                    { t: '404 Tidak Ditemukan', to: '/halaman-tidak-ada' },
                    { t: '500 Server Error', to: '/500' },
                ],
            },
        ],
    },
];
