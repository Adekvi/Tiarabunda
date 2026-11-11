<x-dashboard.layout.app title="Hero Section" page-title="Hero Section">
    <div class="hero-page">

        {{-- Flash Message --}}
        @if (session('success'))
            <div class="al al-success" role="status">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                    stroke-linecap="round" stroke-linejoin="round">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                    <polyline points="22 4 12 14.01 9 11.01"></polyline>
                </svg>
                {{ session('success') }}
            </div>
        @endif

        @if (!$item)
            {{-- TAMPILAN JIKA DATA KOSONG (EMPTY STATE) --}}
            <div class="card" style="padding: 60px 20px; text-align: center; margin-top: 20px;">
                <div
                    style="display: inline-flex; align-items: center; justify-content: center; width: 80px; height: 80px; border-radius: 50%; background: var(--pri-t); color: var(--pri); margin-bottom: 24px;">
                    <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                        <circle cx="8.5" cy="8.5" r="1.5"></circle>
                        <polyline points="21 15 16 10 5 21"></polyline>
                    </svg>
                </div>
                <h2 style="font-size: 1.5rem; font-weight: 700; color: var(--ink); margin-bottom: 8px;">Belum Ada
                    Pengaturan Hero</h2>
                <p style="font-size: 1rem; color: var(--mut); max-width: 500px; margin: 0 auto 24px; line-height: 1.6;">
                    Anda belum mengatur teks atau media untuk bagian paling atas halaman beranda. Silakan buat
                    pengaturan pertama Anda sekarang.
                </p>
                <a href="{{ route('superadmin.hero.edit') }}" class="btn"
                    style="padding: 12px 28px; font-size: 1rem;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 6px;">
                        <path d="M12 5v14"></path>
                        <path d="M5 12h14"></path>
                    </svg>
                    Tambah Data Hero
                </a>
            </div>
        @else
            {{-- TAMPILAN JIKA DATA ADA (PREVIEW SEPERTI SEBELUMNYA) --}}
            <div class="hero-layout">
                {{-- Kolom Konten Utama --}}
                <div class="hero-main">

                    {{-- Header Hero --}}
                    <div class="hero-header">
                        <div>
                            <div class="hero-header__crumb">
                                <span>Superadmin</span>
                                <span>/</span>
                                <span>Beranda</span>
                                <span>/</span>
                                <span class="is-current">Hero Section</span>
                            </div>
                            <h1 class="hero-header__title">Hero Section</h1>
                            <p class="hero-header__desc">
                                Pratinjau teks dan media yang saat ini tampil di bagian paling atas halaman beranda
                                (Landing Page).
                            </p>
                        </div>
                        <a href="{{ route('superadmin.hero.edit') }}" class="btn hero-header__cta">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12 20h9" />
                                <path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4 12.5-12.5z" />
                            </svg>
                            Edit Pengaturan
                        </a>
                    </div>

                    {{-- Kotak Preview Hero --}}
                    <div class="hero-preview">

                        {{-- Background Media --}}
                        @if ($item->tipe_media === 'video' && $item->path_media)
                            <video class="hero-preview__bg" autoplay loop muted playsinline
                                poster="{{ $item->path_poster ? asset('storage/' . $item->path_poster) : '' }}">
                                <source src="{{ asset('storage/' . $item->path_media) }}" type="video/mp4">
                            </video>
                            <div class="hero-preview__overlay"></div>
                        @elseif($item->tipe_media === 'gambar' && $item->path_media)
                            <div class="hero-preview__bg"
                                style="background-image: url('{{ asset('storage/' . $item->path_media) }}'); background-size: cover; background-position: center;">
                            </div>
                            <div class="hero-preview__overlay"></div>
                        @endif

                        {{-- Konten Teks --}}
                        <div class="hero-preview__content">

                            {{-- Ticker / Badge --}}
                            <span class="hero-preview__badge"
                                style="background: {{ $item->tipe_media !== 'none' ? 'rgba(255,255,255,0.15)' : 'var(--acc-t)' }}; color: {{ $item->tipe_media !== 'none' ? '#fff' : 'var(--acc)' }};">
                                <span class="hero-preview__badge-dot"></span>
                                Informasi Pendaftaran Dibuka
                            </span>

                            {{-- Judul --}}
                            <h1 class="hero-preview__title"
                                style="color: {{ $item->tipe_media !== 'none' ? '#fff' : 'var(--ink)' }}; text-shadow: {{ $item->tipe_media !== 'none' ? '0 2px 10px rgba(0,0,0,0.3)' : 'none' }};">
                                {{ $item->judul }}
                            </h1>

                            {{-- Sub Judul --}}
                            @if ($item->sub_judul)
                                <p class="hero-preview__desc"
                                    style="color: {{ $item->tipe_media !== 'none' ? 'rgba(255,255,255,0.9)' : 'var(--mut)' }};">
                                    {{ $item->sub_judul }}
                                </p>
                            @endif

                            {{-- Tombol --}}
                            <div class="hero-preview__actions">
                                <span class="btn"
                                    style="padding: 12px 28px; font-size: 1rem; background: var(--pri); color: #fff;">
                                    {{ $item->teks_tombol_1 }}
                                </span>
                                <span class="btn"
                                    style="padding: 12px 28px; font-size: 1rem; background: transparent; border: 2px solid {{ $item->tipe_media !== 'none' ? '#fff' : 'var(--pri)' }}; color: {{ $item->tipe_media !== 'none' ? '#fff' : 'var(--pri)' }};">
                                    {{ $item->teks_tombol_2 }}
                                </span>
                            </div>

                        </div>
                    </div>

                </div>

                {{-- Sidebar Kanan --}}
                <div class="hero-aside">

                    {{-- Card Status Publikasi --}}
                    <div class="card hero-stat">
                        <div class="hero-stat__head">
                            <h3>Status Hero</h3>
                            <p>Informasi publikasi konten.</p>
                        </div>

                        <div class="hero-stat__row">
                            <div>
                                <span class="hero-stat__val">{{ $item->urip ? 'Tayang' : 'Draf' }}</span>
                                <span class="hero-stat__lbl">Terakhir diperbarui
                                    {{ $item->updated_at->diffForHumans() }}</span>
                            </div>
                            <span class="badge {{ $item->urip ? 'ok' : 'err' }}">
                                {{ $item->urip ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </div>

                        <div class="hero-stat__sep"></div>

                        <h4 class="hero-stat__title">Informasi Media</h4>
                        <ul class="hero-stat__list">
                            <li>
                                <div class="hero-stat__ico">
                                    @if ($item->tipe_media === 'video')
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <polygon points="23 7 16 12 23 17 23 7"></polygon>
                                            <rect x="1" y="5" width="15" height="14" rx="2"
                                                ry="2"></rect>
                                        </svg>
                                    @elseif($item->tipe_media === 'gambar')
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <rect x="3" y="3" width="18" height="18" rx="2"
                                                ry="2"></rect>
                                            <circle cx="8.5" cy="8.5" r="1.5"></circle>
                                            <polyline points="21 15 16 10 5 21"></polyline>
                                        </svg>
                                    @else
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <circle cx="12" cy="12" r="10"></circle>
                                            <line x1="12" y1="8" x2="12" y2="12">
                                            </line>
                                            <line x1="12" y1="16" x2="12.01" y2="16">
                                            </line>
                                        </svg>
                                    @endif
                                </div>
                                <div class="hero-stat__meta">
                                    <span class="hero-stat__name">Tipe Media</span>
                                    <span
                                        class="hero-stat__hint">{{ $item->tipe_media === 'none' ? 'Tanpa Background' : ucfirst($item->tipe_media) }}</span>
                                </div>
                            </li>
                            @if ($item->path_media)
                                <li>
                                    <div class="hero-stat__ico">
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                            <polyline points="7 10 12 15 17 10"></polyline>
                                            <line x1="12" y1="15" x2="12" y2="3">
                                            </line>
                                        </svg>
                                    </div>
                                    <div class="hero-stat__meta">
                                        <span class="hero-stat__name">Berkas Media</span>
                                        <a href="{{ asset('storage/' . $item->path_media) }}" target="_blank"
                                            class="hero-stat__link">Unduh / Lihat File</a>
                                    </div>
                                </li>
                            @endif

                            {{-- Cek jika ada poster video --}}
                            @if ($item->path_poster)
                                <li>
                                    <div class="hero-stat__ico">
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <rect x="3" y="3" width="18" height="18" rx="2"
                                                ry="2"></rect>
                                            <circle cx="8.5" cy="8.5" r="1.5"></circle>
                                            <polyline points="21 15 16 10 5 21"></polyline>
                                        </svg>
                                    </div>
                                    <div class="hero-stat__meta">
                                        <span class="hero-stat__name">Poster Video</span>
                                        <a href="{{ asset('storage/' . $item->path_poster) }}" target="_blank"
                                            class="hero-stat__link">Lihat Poster</a>
                                    </div>
                                </li>
                            @endif
                        </ul>

                        <div class="hero-stat__sep"></div>

                        {{-- Tombol Kunjungi Landing Page --}}
                        <a href="{{ url('/') }}" target="_blank" class="btn"
                            style="width: 100%; display: flex; justify-content: center; gap: 8px;">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
                                <polyline points="15 3 21 3 21 9"></polyline>
                                <line x1="10" y1="14" x2="21" y2="3"></line>
                            </svg>
                            Lihat Landing Page
                        </a>
                    </div>

                    {{-- Card Panduan --}}
                    <div class="card">
                        <h3 class="hero-stat__title" style="display: flex; align-items: center; gap: 8px;">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"></circle>
                                <line x1="12" y1="16" x2="12" y2="12"></line>
                                <line x1="12" y1="8" x2="12.01" y2="8"></line>
                            </svg>
                            Informasi
                        </h3>
                        <p style="margin:0;font-size:0.85rem;color:var(--mut);line-height:1.65;">
                            Hero adalah elemen pertama yang dilihat pengunjung. Pastikan teks tidak terlalu panjang dan
                            media berukuran ringan agar website cepat diakses.
                        </p>
                    </div>

                    {{-- Card Saran --}}
                    <div class="card">
                        <h3 class="hero-stat__title" style="display: flex; align-items: center; gap: 8px;">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path d="M9 18h6"></path>
                                <path d="M10 22h4"></path>
                                <path
                                    d="M15.09 14c.18-.98.65-1.74 1.41-2.5A4.65 4.65 0 0 0 18 8 6 6 0 0 0 6 8c0 1.45.55 2.67 1.5 3.5.76.76 1.23 1.52 1.41 2.5">
                                </path>
                            </svg>
                            Saran Media
                        </h3>
                        <ul class="hero-tips">
                            <li>Gunakan video berformat <strong>MP4/WebM</strong> berdurasi pendek.</li>
                            <li>Resolusi video disarankan <strong>1080p</strong> dengan ukuran maksimal
                                <strong>10MB</strong>.
                            </li>
                            <li>Untuk gambar, gunakan rasio <strong>16:9</strong> agar pas di berbagai layar.</li>
                        </ul>
                    </div>

                </div>
            </div>
        @endif
    </div>

    @push('css')
        <link rel="stylesheet" href="{{ asset('admin/landing/hero/hero.css') }}">
    @endpush
</x-dashboard.layout.app>
