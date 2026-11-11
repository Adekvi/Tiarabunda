@push('css')
    <link rel="stylesheet" href="{{ asset('admin/landing/hero/hero.css') }}">
@endpush

<x-dashboard.layout.app title="Hero Section" page-title="Hero Section">
    <div class="g">

        {{-- Flash --}}
        @if (session('success'))
            <div class="al al-success" role="status">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                    <polyline points="22 4 12 14.01 9 11.01"></polyline>
                </svg>
                {{ session('success') }}
            </div>
        @endif

        {{-- Breadcrumb / aksi atas --}}
        <div class="card" style="padding: 14px 22px;">
            <div class="ch2" style="margin-bottom: 0;">
                <div>
                    <p style="margin: 0 0 2px; font-size: 0.75rem; color: var(--mut);">
                        Superadmin
                        <span style="opacity: 0.5;"> / </span>
                        Beranda
                        <span style="opacity: 0.5;"> / </span>
                        <span style="color: var(--ink); font-weight: 600;">Hero Section</span>
                    </p>
                    <h2 style="margin: 0; font-size: 1.15rem;">Hero Section</h2>
                </div>
                <a href="{{ route('superadmin.hero.edit') }}" class="btn-hero-primary">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        @if ($item)
                            <path d="M12 20h9" />
                            <path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4 12.5-12.5z" />
                        @else
                            <path d="M12 5v14" />
                            <path d="M5 12h14" />
                        @endif
                    </svg>
                    {{ $item ? 'Edit Pengaturan' : 'Tambah Data Hero' }}
                </a>
            </div>
        </div>

        @if (!$item)
            {{-- Empty State --}}
            <div class="card" style="padding: 56px 24px; text-align: center;">
                <div class="hero-empty-icon">
                    <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                        <circle cx="8.5" cy="8.5" r="1.5"></circle>
                        <polyline points="21 15 16 10 5 21"></polyline>
                    </svg>
                </div>
                <h2 style="font-size: 1.35rem; font-weight: 700; color: var(--ink); margin: 0 0 8px;">
                    Belum Ada Pengaturan Hero
                </h2>
                <p
                    style="font-size: 0.95rem; color: var(--mut); max-width: 420px; margin: 0 auto 22px; line-height: 1.6;">
                    Anda belum mengatur teks atau media untuk bagian paling atas halaman beranda.
                </p>
                <a href="{{ route('superadmin.hero.edit') }}" class="btn-hero-primary">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 5v14"></path>
                        <path d="M5 12h14"></path>
                    </svg>
                    Tambah Data Hero
                </a>
            </div>
        @else
            <div class="hero-layout">

                {{-- ===================== KOLOM KIRI ===================== --}}
                <div class="hero-main">

                    {{-- PREVIEW BOX --}}
                    <div class="card" style="padding: 0; overflow: hidden; border-radius: 12px;">
                        <div class="hero-preview" style="border: none; border-radius: 0; min-height: 380px;">
                            @if ($item->tipe_media === 'video' && $item->path_media)
                                <video class="hero-preview__bg" autoplay loop muted playsinline
                                    poster="{{ $item->path_poster ? asset('storage/' . $item->path_poster) : '' }}">
                                    <source src="{{ asset('storage/' . $item->path_media) }}" type="video/mp4">
                                </video>
                                <div class="hero-preview__overlay"></div>
                            @elseif ($item->tipe_media === 'gambar' && $item->path_media)
                                <div class="hero-preview__bg"
                                    style="background-image: url('{{ asset('storage/' . $item->path_media) }}'); background-size: cover; background-position: center;">
                                </div>
                                <div class="hero-preview__overlay"></div>
                            @endif

                            <div class="hero-preview__content">
                                <span class="hero-preview__badge"
                                    style="background: {{ $item->tipe_media !== 'none' ? 'rgba(255,255,255,0.15)' : 'var(--acc-t)' }}; color: {{ $item->tipe_media !== 'none' ? '#fff' : 'var(--acc)' }};">
                                    <span class="hero-preview__badge-dot"></span>
                                    Informasi Pendaftaran Dibuka
                                </span>

                                <h1 class="hero-preview__title"
                                    style="color: {{ $item->tipe_media !== 'none' ? '#fff' : 'var(--ink)' }}; text-shadow: {{ $item->tipe_media !== 'none' ? '0 2px 10px rgba(0,0,0,0.3)' : 'none' }};">
                                    {{ $item->judul }}
                                </h1>

                                @if ($item->sub_judul)
                                    <p class="hero-preview__desc"
                                        style="color: {{ $item->tipe_media !== 'none' ? 'rgba(255,255,255,0.9)' : 'var(--mut)' }};">
                                        {{ $item->sub_judul }}
                                    </p>
                                @endif

                                <div class="hero-preview__actions">
                                    <span class="btn-preview-primary">
                                        {{ $item->teks_tombol_1 }}
                                    </span>
                                    <span class="btn-preview-secondary"
                                        style="border-color: {{ $item->tipe_media !== 'none' ? '#fff' : 'var(--pri)' }}; color: {{ $item->tipe_media !== 'none' ? '#fff' : 'var(--pri)' }};">
                                        {{ $item->teks_tombol_2 }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        {{-- Tabel Detail Data --}}
                        <div class="hero-table-wrapper">
                            <div class="hero-table-header">
                                <h3>Detail Konfigurasi Tersimpan</h3>
                                <span class="badge badge-outline">ID: #{{ $item->id }}</span>
                            </div>

                            <div class="table-responsive">
                                <table class="hero-table">
                                    <thead>
                                        <tr>
                                            <th width="25%">Parameter</th>
                                            <th width="75%">Nilai Tersimpan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><strong>Judul Utama</strong></td>
                                            <td>{{ $item->judul }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Sub Judul</strong></td>
                                            <td>{{ $item->sub_judul ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Tombol 1 (Utama)</strong></td>
                                            <td>
                                                <span class="badge-outline">Teks: {{ $item->teks_tombol_1 }}</span>
                                                <span class="badge-outline">Link: {{ $item->link_tombol_1 }}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Tombol 2 (Sekunder)</strong></td>
                                            <td>
                                                <span class="badge-outline">Teks: {{ $item->teks_tombol_2 }}</span>
                                                <span class="badge-outline">Link: {{ $item->link_tombol_2 }}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Tipe Media</strong></td>
                                            <td>
                                                @if ($item->tipe_media === 'none')
                                                    <span class="badge status-none">Tanpa Media</span>
                                                @elseif($item->tipe_media === 'gambar')
                                                    <span class="badge status-image">Gambar</span>
                                                @elseif($item->tipe_media === 'video')
                                                    <span class="badge status-video">Video</span>
                                                @endif
                                            </td>
                                        </tr>
                                        @if ($item->path_media)
                                            <tr>
                                                <td><strong>Berkas Media</strong></td>
                                                <td>
                                                    <a href="{{ asset('storage/' . $item->path_media) }}"
                                                        target="_blank" class="link-media">
                                                        <svg width="14" height="14" viewBox="0 0 24 24"
                                                            fill="none" stroke="currentColor" stroke-width="2"
                                                            stroke-linecap="round" stroke-linejoin="round">
                                                            <path
                                                                d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6">
                                                            </path>
                                                            <polyline points="15 3 21 3 21 9"></polyline>
                                                            <line x1="10" y1="14" x2="21"
                                                                y2="3"></line>
                                                        </svg>
                                                        Lihat Media
                                                    </a>
                                                </td>
                                            </tr>
                                        @endif
                                        <tr>
                                            <td><strong>Terakhir Diperbarui</strong></td>
                                            <td>{{ $item->updated_at->format('d F Y, H:i') }} WIB</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ===================== SIDEBAR KANAN ===================== --}}
                <div class="hero-aside">

                    {{-- Card Status --}}
                    <div class="card hero-stat">
                        <div class="hero-stat__head">
                            <h3>Status Publikasi</h3>
                            <p>Pengaturan tampilan Hero</p>
                        </div>

                        <div style="margin-top: 16px;">
                            <div class="status-box {{ $item->urip ? 'is-active' : 'is-inactive' }}">
                                <div class="status-icon">
                                    @if ($item->urip)
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2.5" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                                            <polyline points="22 4 12 14.01 9 11.01"></polyline>
                                        </svg>
                                    @else
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2.5" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <circle cx="12" cy="12" r="10"></circle>
                                            <line x1="4.93" y1="4.93" x2="19.07" y2="19.07">
                                            </line>
                                        </svg>
                                    @endif
                                </div>
                                <div class="status-text">
                                    <strong>{{ $item->urip ? 'Aktif Tayang' : 'Draf / Nonaktif' }}</strong>
                                    <span>{{ $item->urip ? 'Terlihat oleh pengunjung di landing page.' : 'Disembunyikan dari halaman publik.' }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="hero-stat__footer">
                            <a href="{{ url('/') }}" target="_blank" class="btn-hero-outline"
                                style="width: 100%;">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" style="margin-right: 6px;">
                                    <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
                                    <polyline points="15 3 21 3 21 9"></polyline>
                                    <line x1="10" y1="14" x2="21" y2="3"></line>
                                </svg>
                                Buka Landing Page
                            </a>
                        </div>
                    </div>

                    {{-- Card Panduan --}}
                    <div class="card hero-stat">
                        <div class="hero-stat__head">
                            <h3>Panduan Singkat</h3>
                        </div>
                        <ul class="hero-tips">
                            <li>Judul singkat, maksimal 1–2 baris.</li>
                            <li>Sub judul menjelaskan nilai utama kampus.</li>
                            <li>Gunakan media ringan agar loading cepat.</li>
                            <li>Pastikan kontras teks dengan background media terbaca jelas.</li>
                        </ul>
                    </div>

                    {{-- Card Saran Media --}}
                    <div class="card hero-stat">
                        <div class="hero-stat__head">
                            <h3>Saran Media</h3>
                        </div>
                        <ul class="hero-tips">
                            <li><strong>Video:</strong> MP4/WebM, durasi pendek, maks ~10–15MB.</li>
                            <li><strong>Gambar:</strong> Rasio 16:9, resolusi minimal 1920x1080.</li>
                            <li><strong>Poster:</strong> Opsional, membantu tampilan awal video.</li>
                        </ul>
                    </div>

                </div>
            </div>
        @endif
    </div>
</x-dashboard.layout.app>
