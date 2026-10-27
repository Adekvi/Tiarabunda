/* Data global: dipakai topbar, navbar, footer, cta di semua halaman */
export const SITE = {
  nama: 'Politeknik Tiara Bunda',
  kota: 'Kota Depok',
  alamat: 'Kota Depok, Jawa Barat',
  portal: [ [ 'Mahasiswa', '#' ], [ 'Dosen', '#' ], [ 'Alumni', '#' ], [ 'Portal', '#' ] ],
  menu: [
    {
      t: 'Profil',
      sub: [
        [ 'Sejarah', '/profil#sejarah' ],
        [ 'Visi & Misi', '/profil#visi' ],
        [ 'Struktur Organisasi', '/profil#struktur' ]
      ]
    },
    {
      t: 'Fakultas',
      sub: [
        [ 'Teknologi & Informatika', '/fakultas#prodi' ],
        [ 'Bisnis & Manajemen', '/fakultas#prodi' ],
        [ 'Kesehatan', '/fakultas#prodi' ]
      ]
    },
    {
      t: 'Layanan',
      sub: [
        [ 'Perpustakaan', '/layanan#pustaka' ],
        [ 'Kemahasiswaan', '/layanan#mhs' ],
        [ 'Pusat Karier', '/layanan#karier' ]
      ]
    },
    {
      t: 'Dokumen',
      sub: [ [ 'Kalender Akademik', '/dokumen#kalender' ], [ 'Panduan Akademik', '/dokumen#panduan' ] ]
    },
    { t: 'PMB', href: '/pmb' },
    { t: 'Akreditasi', href: '/akreditasi' }
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
        [ 'Fakultas & Prodi', '/fakultas#prodi' ],
        [ 'Kalender Akademik', '/dokumen' ],
        [ 'Perpustakaan', '/layanan' ]
      ]
    ],
    [ 'Informasi', [ [ 'PMB', '/pmb' ], [ 'Akreditasi', '/akreditasi' ], [ 'Berita', '/#berita' ] ] ]
  ],
  pmb: '/pmb'
}
