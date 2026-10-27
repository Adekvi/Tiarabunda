import home from './home.js'

/* Data halaman PMB (jalur dan FAQ dipakai ulang dari Beranda) */
export default {
    hero: { kick: 'PMB', judul: 'Penerimaan Mahasiswa Baru', sub: 'Alur, persyaratan, dan jalur pendaftaran.' },
    blok: [
        { id: 'alur', t: 'Alur pendaftaran', daftar: ['Buat akun PMB', 'Lengkapi data diri', 'Unggah dokumen', 'Ikuti seleksi', 'Daftar ulang'] },
        { id: 'syarat', t: 'Persyaratan', daftar: ['Ijazah/SKL', 'Pas foto terbaru', 'Fotokopi KTP/KK'] }
    ],
    jalur: home.jalur,
    faq: home.faq
}
