import home from './home.js'

/* Data halaman fakultas (memakai ulang data prodi dari Beranda) */
export default {
    hero: { kick: 'Fakultas', judul: 'Fakultas & Program Studi', sub: 'Pilih bidang studi yang sesuai minatmu.' },
    fakultas: home.fakultas,
    prodi: home.prodi
}
