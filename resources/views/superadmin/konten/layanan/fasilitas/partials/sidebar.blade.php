@php
    $total = (int) ($stats['total'] ?? 0);
    $aktif = (int) ($stats['aktif'] ?? 0);
    $nonaktif = (int) ($stats['nonaktif'] ?? 0);
    $adaGambar = (int) ($stats['ada_gambar'] ?? 0);
    $terhapus = (int) ($stats['terhapus'] ?? 0);

    $tanpaGambar = max(0, $total - $adaGambar);
    $persenAktif = $total > 0 ? round(($aktif / $total) * 100) : 0;
    $persenGambar = $total > 0 ? round(($adaGambar / $total) * 100) : 0;
@endphp

<aside class="fasilitas-sidebar" aria-label="Informasi pengelolaan fasilitas">

    {{-- 1. Ringkasan --}}
    <section class="card fs-side-card">
        <div class="fs-section-heading">
            <div>
                <span class="fs-eyebrow">OVERVIEW</span>
                <h3>Ringkasan fasilitas</h3>
                <p>Kondisi data fasilitas yang tersimpan saat ini.</p>
            </div>
            <span class="fs-live"><span></span>Data</span>
        </div>

        <div class="fs-stats">
            <div class="fs-stat">
                <div class="fs-stat__icon fs-stat__icon--primary">
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round">
                        <rect x="3" y="3" width="7" height="7" rx="1" />
                        <rect x="14" y="3" width="7" height="7" rx="1" />
                        <rect x="3" y="14" width="7" height="7" rx="1" />
                        <rect x="14" y="14" width="7" height="7" rx="1" />
                    </svg>
                </div>
                <strong>{{ number_format($total) }}</strong>
                <span>Total fasilitas</span>
            </div>

            <div class="fs-stat">
                <div class="fs-stat__icon fs-stat__icon--success">
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round">
                        <path d="M20 6 9 17l-5-5" />
                    </svg>
                </div>
                <strong>{{ number_format($aktif) }}</strong>
                <span>Fasilitas aktif</span>
            </div>

            <div class="fs-stat">
                <div class="fs-stat__icon fs-stat__icon--danger">
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round">
                        <circle cx="12" cy="12" r="9" />
                        <path d="M15 9l-6 6M9 9l6 6" />
                    </svg>
                </div>
                <strong>{{ number_format($nonaktif) }}</strong>
                <span>Nonaktif</span>
            </div>

            <div class="fs-stat">
                <div class="fs-stat__icon fs-stat__icon--info">
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round">
                        <rect x="3" y="3" width="18" height="18" rx="2" />
                        <circle cx="8.5" cy="8.5" r="1.5" />
                        <path d="m21 15-5-5L5 21" />
                    </svg>
                </div>
                <strong>{{ number_format($adaGambar) }}</strong>
                <span>Memiliki gambar</span>
            </div>
        </div>

        <div class="fs-progress-group">
            <div class="fs-progress-item">
                <div class="fs-progress__heading">
                    <span>Publikasi aktif</span>
                    <strong>{{ $persenAktif }}%</strong>
                </div>
                <div class="fs-progress">
                    <span class="fs-progress__fill fs-progress__fill--success"
                        style="width:{{ $persenAktif }}%"></span>
                </div>
                <small>{{ $aktif }} dari {{ $total }} fasilitas aktif</small>
            </div>

            <div class="fs-progress-item">
                <div class="fs-progress__heading">
                    <span>Kelengkapan gambar</span>
                    <strong>{{ $persenGambar }}%</strong>
                </div>
                <div class="fs-progress">
                    <span class="fs-progress__fill" style="width:{{ $persenGambar }}%"></span>
                </div>
                <small>{{ $adaGambar }} dari {{ $total }} fasilitas memiliki gambar</small>
            </div>
        </div>
    </section>

    {{-- 2. Kelengkapan data --}}
    <section class="card fs-side-card">
        <div class="fs-section-heading">
            <div>
                <span class="fs-eyebrow">DATA QUALITY</span>
                <h3>Kelengkapan data</h3>
                <p>Hal yang perlu diperhatikan dalam pengelolaan.</p>
            </div>
            <span class="fs-heading-icon">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2" stroke-linecap="round">
                    <path d="M12 9v4" />
                    <path d="M12 17h.01" />
                    <path d="M10.3 3.9 1.8 18.1A2 2 0 0 0 3.5 21h17a2 2 0 0 0 1.7-2.9L13.7 3.9a2 2 0 0 0-3.4 0z" />
                </svg>
            </span>
        </div>

        @if ($tanpaGambar > 0)
            <div class="fs-notice fs-notice--warning">
                <span class="fs-notice__icon">
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round">
                        <path d="M12 9v4" />
                        <path d="M12 17h.01" />
                        <circle cx="12" cy="12" r="10" />
                    </svg>
                </span>
                <div>
                    <strong>{{ $tanpaGambar }} fasilitas belum memiliki gambar</strong>
                    <p>Lengkapi gambar agar informasi fasilitas lebih menarik saat ditampilkan.</p>
                </div>
            </div>
        @else
            <div class="fs-notice fs-notice--success">
                <span class="fs-notice__icon">
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round">
                        <path d="M20 6 9 17l-5-5" />
                    </svg>
                </span>
                <div>
                    <strong>Semua fasilitas memiliki gambar</strong>
                    <p>Kelengkapan gambar fasilitas sudah terpenuhi.</p>
                </div>
            </div>
        @endif

        <div class="fs-quality-list">
            <div class="fs-quality-row">
                <span class="fs-quality-dot fs-quality-dot--success"></span>
                <span>Fasilitas aktif</span><strong>{{ $aktif }}</strong>
            </div>
            <div class="fs-quality-row">
                <span class="fs-quality-dot fs-quality-dot--danger"></span>
                <span>Fasilitas nonaktif</span><strong>{{ $nonaktif }}</strong>
            </div>
            <div class="fs-quality-row">
                <span class="fs-quality-dot fs-quality-dot--warning"></span>
                <span>Belum memiliki gambar</span><strong>{{ $tanpaGambar }}</strong>
            </div>
            @if ($terhapus > 0)
                <div class="fs-quality-row">
                    <span class="fs-quality-dot fs-quality-dot--muted"></span>
                    <span>Di sampah</span><strong>{{ $terhapus }}</strong>
                </div>
            @endif
        </div>
    </section>

    {{-- 3. Quick actions --}}
    <section class="card fs-side-card">
        <div class="fs-section-heading">
            <div>
                <span class="fs-eyebrow">QUICK ACTIONS</span>
                <h3>Aksi cepat</h3>
                <p>Akses langsung ke pengelolaan fasilitas.</p>
            </div>
        </div>

        <div class="fs-actions">
            <a href="{{ route('superadmin.fasilitas.create') }}" class="fs-action fs-action--primary">
                <span class="fs-action__icon">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2.2" stroke-linecap="round">
                        <path d="M12 5v14M5 12h14" />
                    </svg>
                </span>
                <span>
                    <strong>Tambah fasilitas</strong>
                    <small>Buat data fasilitas baru</small>
                </span>
                <svg class="fs-action__arrow" width="16" height="16" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2">
                    <path d="m9 18 6-6-6-6" />
                </svg>
            </a>

            <a href="{{ route('superadmin.fasilitas.index', ['status' => 'active']) }}" class="fs-action">
                <span class="fs-action__icon">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round">
                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8S1 12 1 12z" />
                        <circle cx="12" cy="12" r="3" />
                    </svg>
                </span>
                <span>
                    <strong>Fasilitas aktif</strong>
                    <small>Lihat {{ $aktif }} fasilitas aktif</small>
                </span>
                <svg class="fs-action__arrow" width="16" height="16" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2">
                    <path d="m9 18 6-6-6-6" />
                </svg>
            </a>

            <a href="{{ route('superadmin.fasilitas.index', ['status' => 'inactive']) }}" class="fs-action">
                <span class="fs-action__icon">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round">
                        <circle cx="12" cy="12" r="9" />
                        <path d="M15 9l-6 6M9 9l6 6" />
                    </svg>
                </span>
                <span>
                    <strong>Fasilitas nonaktif</strong>
                    <small>Lihat {{ $nonaktif }} fasilitas nonaktif</small>
                </span>
                <svg class="fs-action__arrow" width="16" height="16" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2">
                    <path d="m9 18 6-6-6-6" />
                </svg>
            </a>

            <a href="{{ route('superadmin.fasilitas.trashed') }}" class="fs-action">
                <span class="fs-action__icon">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round">
                        <path d="M3 6h18" />
                        <path d="M8 6V4h8v2" />
                        <path d="M19 6l-1 14H6L5 6" />
                    </svg>
                </span>
                <span>
                    <strong>Sampah fasilitas</strong>
                    <small>{{ $terhapus }} data terhapus</small>
                </span>
                <svg class="fs-action__arrow" width="16" height="16" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2">
                    <path d="m9 18 6-6-6-6" />
                </svg>
            </a>
        </div>
    </section>

    {{-- 4. Panduan --}}
    <section class="card fs-side-card">
        <div class="fs-section-heading">
            <div>
                <span class="fs-eyebrow">MANAGEMENT GUIDE</span>
                <h3>Panduan pengelolaan</h3>
                <p>Rekomendasi untuk menjaga kualitas informasi.</p>
            </div>
            <span class="fs-heading-icon">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2" stroke-linecap="round">
                    <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20" />
                    <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z" />
                </svg>
            </span>
        </div>

        <ol class="fs-guide-list">
            <li>
                <span class="fs-guide-number">01</span>
                <div>
                    <strong>Gunakan nama yang jelas</strong>
                    <p>Gunakan nama fasilitas yang mudah dikenali oleh mahasiswa, dosen, dan pengunjung.</p>
                </div>
            </li>
            <li>
                <span class="fs-guide-number">02</span>
                <div>
                    <strong>Gunakan gambar berkualitas</strong>
                    <p>Pilih foto yang relevan, jelas, dan mewakili kondisi fasilitas sebenarnya.</p>
                </div>
            </li>
            <li>
                <span class="fs-guide-number">03</span>
                <div>
                    <strong>Atur urutan tampilan</strong>
                    <p>Angka urutan yang lebih kecil akan ditampilkan lebih dahulu.</p>
                </div>
            </li>
            <li>
                <span class="fs-guide-number">04</span>
                <div>
                    <strong>Periksa status publikasi</strong>
                    <p>Pastikan hanya fasilitas yang siap ditampilkan memiliki status aktif.</p>
                </div>
            </li>
        </ol>
    </section>

    {{-- 5. Informasi teknis --}}
    <section class="card fs-side-card">
        <div class="fs-section-heading">
            <div>
                <span class="fs-eyebrow">SYSTEM INFORMATION</span>
                <h3>Informasi gambar</h3>
                <p>Ketentuan teknis untuk menjaga performa halaman.</p>
            </div>
        </div>

        <div class="fs-tech-list">
            <div><span>Gambar utama</span><strong>≤ 1600 px</strong></div>
            <div><span>Thumbnail</span><strong>≤ 480 px</strong></div>
            <div><span>Format yang disarankan</span><strong>WebP / JPG</strong></div>
            <div><span>Pemuatan gambar</span><strong>Lazy loading</strong></div>
        </div>

        <div class="fs-footer-note">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                stroke-width="2" stroke-linecap="round">
                <circle cx="12" cy="12" r="10" />
                <path d="M12 16v-4M12 8h.01" />
            </svg>
            <span>Gambar dioptimasi saat proses unggah sesuai konfigurasi aplikasi.</span>
        </div>
    </section>
</aside>

@push('css')
    <style>
        /* Trash card */
        .fas-card--trashed {
            border-style: dashed;
            opacity: .9;
        }

        /* Tombol danger */
        .btn-danger {
            color: var(--err, #b42318);
            border-color: var(--err, #b42318);
        }

        .btn-danger:hover {
            background: var(--err-t, #fee4e2);
        }

        /* Quality dot muted */
        .fs-quality-dot--muted {
            background: var(--mut, #667085);
        }

        /* Layout utama — sama seperti Kegiatan */
        .kegiatan-layout {
            display: grid;
            grid-template-columns: minmax(0, 1fr) minmax(270px, 320px);
            align-items: start;
            gap: 20px;
            min-width: 0;
        }

        .kegiatan-main {
            min-width: 0;
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .fasilitas-sidebar {
            position: sticky;
            top: calc(var(--top, 0px) + 20px);
            max-height: calc(100dvh - var(--top, 0px) - 40px);
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            gap: 16px;
            padding-right: 4px;
        }

        @media (max-width: 1100px) {
            .kegiatan-layout {
                grid-template-columns: minmax(0, 1fr) 270px;
            }
        }

        @media (max-width: 850px) {
            .kegiatan-layout {
                grid-template-columns: 1fr;
            }

            .fasilitas-sidebar {
                position: static;
                max-height: none;
                overflow: visible;
                display: grid;
                grid-template-columns: repeat(2, minmax(0, 1fr));
                padding: 0;
            }
        }

        @media (max-width: 560px) {
            .fasilitas-sidebar {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endpush
