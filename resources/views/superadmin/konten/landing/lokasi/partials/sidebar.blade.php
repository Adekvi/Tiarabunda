<div class="card lok-side-card">
    <h2 class="lok-side-title">Ringkasan Lokasi</h2>
    <div class="lok-stat-row">
        <span>Lokasi Aktif</span>
        <span class="lok-badge lok-badge--ok">{{ $totalAktif }}</span>
    </div>
    <div class="lok-stat-row">
        <span>Nonaktif</span>
        <span class="lok-badge lok-badge--muted">{{ $totalNonaktif }}</span>
    </div>
    <div class="lok-stat-row">
        <span>Di Tong Sampah</span>
        <span class="lok-badge lok-badge--err">{{ $trashedCount }}</span>
    </div>
</div>

<div class="card lok-side-card">
    <h2 class="lok-side-title">Panduan Konten</h2>
    <ul class="lok-tips">
        <li>Gunakan foto mendatar (<em>landscape</em> 3:2 atau 16:10) agar seluruh fasad gedung tampil utuh tanpa
            terpotong.</li>
        <li>Jika foto dikosongkan, sistem otomatis memakai ilustrasi arsitektural bawaan.</li>
        <li>Urutan angka terkecil akan tampil sebagai tab lokasi pertama di halaman beranda.</li>
    </ul>
</div>
