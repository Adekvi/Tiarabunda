<x-dashboard.layout.app title="Dashboard" page-title="Dashboard">
    {{-- Welcome --}}
    <div class="card"
        style="margin-bottom: 18px; background: linear-gradient(112deg, #154a99, var(--pri) 55%, #3b7ad8); color: #fff; border: 0;">
        <div style="display: flex; justify-content: space-between; align-items: center; gap: 20px; flex-wrap: wrap;">
            <div>
                <p style="font-size: 11px; font-weight: 700; letter-spacing: .12em; color: #ffc36f; margin-bottom: 6px;">
                    SUPERADMIN PANEL
                </p>
                <h2 style="font-size: 1.45rem; margin: 0 0 6px; color: #fff;">
                    Selamat datang, {{ auth()->user()->name ?? 'Admin' }}
                </h2>
                <p style="margin: 0; color: #d6e4fa; font-size: 13px; max-width: 52ch;">
                    Ringkasan aktivitas kampus hari ini. Kelola konten landing, PMB, dan pengguna dari satu tempat.
                </p>
            </div>
            <div style="text-align: right;">
                <p style="margin: 0; font-size: 12px; color: #d6e4fa;">{{ now()->translatedFormat('l, d F Y') }}</p>
                <p style="margin: 4px 0 0; font-size: 11px; color: #b8cdf0;">{{ now()->format('H:i') }} WIB</p>
            </div>
        </div>
    </div>

    {{-- Stats --}}
    <div class="g g4" style="margin-bottom: 18px;">
        <div class="card stat">
            <span class="ico" aria-hidden="true">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2">
                    <path d="M12 12a4 4 0 100-8 4 4 0 000 8zM4 21a8 8 0 0116 0" />
                </svg>
            </span>
            <div>
                <small>Pendaftar PMB</small>
                <b>1.284</b>
                <span class="up">+12% bulan ini</span>
            </div>
        </div>
        <div class="card stat">
            <span class="ico" aria-hidden="true">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2">
                    <path d="M9 11a4 4 0 100-8 4 4 0 000 8zM2 21a7 7 0 0114 0M17 4a4 4 0 010 7M22 21a7 7 0 00-4-6" />
                </svg>
            </span>
            <div>
                <small>Pengguna aktif</small>
                <b>48</b>
                <span class="up">+3 baru</span>
            </div>
        </div>
        <div class="card stat">
            <span class="ico" aria-hidden="true">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2">
                    <path d="M5 4h14v16H5zM8 8h8M8 12h8M8 16h5" />
                </svg>
            </span>
            <div>
                <small>Berita terbit</small>
                <b>24</b>
                <span class="up">+2 minggu ini</span>
            </div>
        </div>
        <div class="card stat">
            <span class="ico" aria-hidden="true">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2">
                    <path d="M4 20V10M10 20V4M16 20v-8M22 20H2" />
                </svg>
            </span>
            <div>
                <small>Kunjungan situs</small>
                <b>12,4 rb</b>
                <span class="up">+8% vs lalu</span>
            </div>
        </div>
    </div>

    {{-- Charts row --}}
    <div class="g g2" style="margin-bottom: 18px;">
        <div class="card">
            <h2>Pendaftar PMB per bulan</h2>
            <div class="bars"
                style="display: flex; align-items: flex-end; gap: 8px; height: 160px; margin-top: 12px;">
                @php
                    $bln = [42, 55, 48, 70, 64, 82, 90, 76, 68, 85, 100, 58];
                    $label = ['J', 'F', 'M', 'A', 'M', 'J', 'J', 'A', 'S', 'O', 'N', 'D'];
                    $max = max($bln);
                @endphp
                @foreach ($bln as $i => $v)
                    <div
                        style="flex: 1; display: flex; flex-direction: column; align-items: center; gap: 6px; height: 100%; justify-content: flex-end;">
                        <span style="font-size: 10px; color: var(--mut);">{{ $v }}</span>
                        <i
                            style="display: block; width: 100%; max-width: 28px; height: {{ ($v / $max) * 100 }}%; background: {{ $v === $max ? 'var(--acc)' : 'var(--pri)' }}; border-radius: 6px 6px 2px 2px; opacity: {{ $v === $max ? '1' : '.75' }};"></i>
                        <span style="font-size: 10px; color: var(--mut);">{{ $label[$i] }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="card">
            <h2>Kelengkapan konten landing</h2>
            @foreach ([['Profil', 80], ['Pendidikan', 55], ['Layanan', 40], ['Informasi', 70]] as [$name, $pct])
                <div style="margin-bottom: 14px;">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 4px;">
                        <small style="color: var(--mut);">{{ $name }}</small>
                        <small style="font-weight: 700;">{{ $pct }}%</small>
                    </div>
                    <div class="prog"><i style="width: {{ $pct }}%;"></i></div>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Table + quick actions --}}
    <div class="g g2">
        <div class="card">
            <div
                style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px; gap: 12px;">
                <h2 style="margin: 0;">Pendaftar terbaru</h2>
                <a href="{{ route('superadmin.dashboard') }}" class="btn"
                    style="font-size: 12px; padding: 6px 12px;">Lihat semua</a>
            </div>
            <div class="tw">
                <table>
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $rows = [
                                ['Aisyah Putri', 'aisyah@mail.example', 'Aktif', 'ok'],
                                ['Dimas Pratama', 'dimas@mail.example', 'Menunggu', 'warn'],
                                ['Nabila Zahra', 'nabila@mail.example', 'Aktif', 'ok'],
                                ['Fajar Ramadhan', 'fajar@mail.example', 'Nonaktif', 'err'],
                            ];
                        @endphp
                        @foreach ($rows as [$nama, $email, $status, $badge])
                            <tr>
                                <td>{{ $nama }}</td>
                                <td>{{ $email }}</td>
                                <td><span class="badge {{ $badge }}">{{ $status }}</span></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card">
            <h2>Aksi cepat</h2>
            <div style="display: flex; flex-direction: column; gap: 10px;">
                <a href="#" class="btn" style="justify-content: flex-start;">Ubah konten Profil</a>
                <a href="{{ route('superadmin.faq.index') }}" class="btn"
                    style="justify-content: flex-start; background: transparent; border: 1px solid var(--line); color: var(--ink);">Kelola
                    FAQ</a>
                <a href="{{ route('superadmin.users') }}" class="btn"
                    style="justify-content: flex-start; background: transparent; border: 1px solid var(--line); color: var(--ink);">Manajemen
                    pengguna</a>
                <a href="{{ route('superadmin.gallery') }}" class="btn"
                    style="justify-content: flex-start; background: transparent; border: 1px solid var(--line); color: var(--ink);">Galeri
                    fasilitas</a>
            </div>
            <p style="margin-top: 16px; font-size: 12px; color: var(--mut);">
                Data di atas masih mock. Hubungkan ke controller & query DB pada tahap berikutnya.
            </p>
        </div>
    </div>
</x-dashboard.layout.app>
