@props([
    'site' => config('landing.site'),
    'fakultas' => [],
    'prodi' => [],
    'gambar' => 'seed/fasilitas/fas5.webp',
    'keunggulan' => null,
    'deskripsi' => null,
])

@php
    $totalFakultas = count($fakultas);
    $totalProdi = count($prodi);

    $keunggulan = $keunggulan ?? [
        [
            'icon' => '<path d="M22 10v6M2 10l10-5 10 5-10 5z" /><path d="M6 12v5c3 3 9 3 12 0v-5" />',
            'judul' => 'Kurikulum Berbasis Industri',
            'desc' => 'Materi ajar dirancang bersama mitra industri agar lulusan siap kerja sejak hari pertama.',
            'detail' =>
                'Kurikulum disusun bersama 120+ mitra industri, diperbarui setiap tahun mengikuti tren pasar kerja.',
        ],
        [
            'icon' =>
                '<path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" /><circle cx="9" cy="7" r="4" /><path d="M23 21v-2a4 4 0 0 0-3-3.87" /><path d="M16 3.13a4 4 0 0 1 0 7.75" />',
            'judul' => 'Dosen & Praktisi Berpengalaman',
            'desc' => 'Dibimbing langsung oleh akademisi dan praktisi aktif yang relevan dengan dunia kerja.',
            'detail' => '70% dosen adalah praktisi aktif di industri, rumah sakit, dan perusahaan teknologi.',
        ],
        [
            'icon' =>
                '<rect x="2" y="7" width="20" height="14" rx="2" ry="2" /><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16" />',
            'judul' => 'Siap Kerja & Berdaya Saing',
            'desc' => 'Program magang, sertifikasi kompetensi, dan pendampingan karier hingga lulus kuliah.',
            'detail' => '92% lulusan terserap kerja dalam 6 bulan, dengan pendampingan Pusat Karier hingga wawancara.',
        ],
    ];

    $deskripsi =
        $deskripsi ??
        sprintf(
            '%1$s hadir sebagai kampus vokasi di %2$s yang berkomitmen mencetak lulusan terampil, siap kerja, dan berdaya saing. Lewat pembelajaran berbasis praktik, dosen profesional, serta kemitraan industri, kami membentuk generasi yang matang secara akademik maupun kompetensi.',
            $site['nama'] ?? 'Kampus kami',
            $site['kota'] ?? 'Indonesia',
        );
@endphp

<section class="mkt" data-market-section>
    <div class="mkt__bg-ornament" aria-hidden="true">
        <span class="mkt__blob mkt__blob--1"></span>
        <span class="mkt__blob mkt__blob--2"></span>
        <span class="mkt__grid-lines"></span>
    </div>

    <div class="wrap mkt__wrap">
        {{-- Kolom kiri --}}
        <div class="mkt__main rv">
            <p class="kick">Mengenal {{ $site['nama'] ?? 'Kami' }}</p>

            <h2 class="mkt__title">
                Kampus yang Membawamu
                <span class="mkt__title-accent">Lebih Dekat ke Dunia Kerja</span>
            </h2>

            <p class="mkt__lead lead">{{ $deskripsi }}</p>

            <div class="mkt__stats" data-counters>
                @if ($totalFakultas)
                    <div class="mkt__stat">
                        <b class="mkt__counter" data-target="{{ $totalFakultas }}" data-suffix="">0</b>
                        <span>Fakultas</span>
                    </div>
                @endif
                @if ($totalProdi)
                    <div class="mkt__stat">
                        <b class="mkt__counter" data-target="{{ $totalProdi }}" data-suffix="">0</b>
                        <span>Program Studi</span>
                    </div>
                @endif
                <div class="mkt__stat mkt__stat--accent">
                    <b class="mkt__counter" data-target="92" data-suffix="%">0%</b>
                    <span>Lulusan Terserap Kerja</span>
                </div>
            </div>

            <ul class="mkt__list" data-accordion>
                @foreach ($keunggulan as $i => $k)
                    <li class="mkt__item {{ $i === 0 ? 'is-open' : '' }}">
                        <button type="button" class="mkt__item-head" aria-expanded="{{ $i === 0 ? 'true' : 'false' }}">
                            <span class="mkt__list-ico" aria-hidden="true">
                                <svg width="22" height="22" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    {!! $k['icon'] !!}
                                </svg>
                            </span>
                            <span class="mkt__list-text">
                                <strong>{{ $k['judul'] }}</strong>
                                <small>{{ $k['desc'] }}</small>
                            </span>
                            <span class="mkt__item-chevron" aria-hidden="true">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2.5" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path d="M6 9l6 6 6-6" />
                                </svg>
                            </span>
                        </button>
                        <div class="mkt__item-body" @if ($i !== 0) hidden @endif>
                            <p>{{ $k['detail'] ?? $k['desc'] }}</p>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>

        {{-- Kolom kanan: visual kampus --}}
        <div class="mkt__aside rv">
            <figure class="mkt__visual" data-visual>
                <div class="mkt__visual-stage">
                    <div class="mkt__visual-frame">
                        <img src="{{ asset($gambar) }}" alt="Suasana kampus {{ $site['nama'] ?? '' }}" loading="lazy">
                        <div class="mkt__visual-shine"></div>
                    </div>

                    {{-- Float Card 1: Saya sembunyikan di layar kecil lewat CSS, tapi tetap ada di HTML --}}
                    <div class="mkt__float-card mkt__float-card--1">
                        <span class="mkt__float-icon">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M22 10v6M2 10l10-5 10 5-10 5z" />
                                <path d="M6 12v5c3 3 9 3 12 0v-5" />
                            </svg>
                        </span>
                        <div>
                            <b>{{ $totalProdi ?: '12' }}+</b>
                            <small>Program Studi</small>
                        </div>
                    </div>

                    <div class="mkt__float-card mkt__float-card--2">
                        <span class="mkt__float-icon mkt__float-icon--gold">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M20 6L9 17l-5-5" />
                            </svg>
                        </span>
                        <div>
                            <b>92%</b>
                            <small>Terserap Kerja</small>
                        </div>
                    </div>

                    <figcaption class="mkt__visual-badge">
                        <span class="mkt__badge-dot"></span>
                        <span>Kampus Aktif &amp; Beroperasi</span>
                    </figcaption>

                    <div class="mkt__visual-ornament" aria-hidden="true"></div>
                </div>
            </figure>
        </div>
    </div>

    <div class="wrap">
        <div class="mkt__trust rv">
            <span class="mkt__trust-label">Terakreditasi &amp; Dipercaya oleh</span>
            <div class="mkt__trust-card">
                <div class="mkt__trust-list">
                    <div class="mkt__trust-item">
                        <span class="mkt__trust-ico">
                            <svg viewBox="0 0 24 24" fill="currentColor" stroke="none">
                                <path
                                    d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z" />
                            </svg>
                        </span>
                        <span class="mkt__trust-text">
                            <b>Akreditasi Unggul</b>
                            <small>dari BAN-PT</small>
                        </span>
                    </div>
                    <div class="mkt__trust-item">
                        <span class="mkt__trust-ico">
                            <svg viewBox="0 0 24 24" fill="currentColor" stroke="none">
                                <path d="M12 3L1 9L4 10.63V16H5V11.13L12 15L23 9L12 3Z" />
                                <path
                                    d="M6 17.5V15.5C6 14.12 9.13 13 13 13C16.87 13 20 14.12 20 15.5V17.5C20 18.88 16.87 20 13 20C9.13 20 6 18.88 6 17.5Z" />
                            </svg>
                        </span>
                        <span class="mkt__trust-text">
                            <b>Standar Nasional</b>
                            <small>Kemendikbud RI</small>
                        </span>
                    </div>
                    <div class="mkt__trust-item">
                        <span class="mkt__trust-ico">
                            <svg viewBox="0 0 24 24" fill="currentColor" stroke="none">
                                <path
                                    d="M16 11C17.66 11 18.99 9.66 18.99 8C18.99 6.34 17.66 5 16 5C14.34 5 13 6.34 13 8C13 9.66 14.34 11 16 11ZM8 11C9.66 11 10.99 9.66 10.99 8C10.99 6.34 9.66 5 8 5C6.34 5 5 6.34 5 8C5 9.66 6.34 11 8 11ZM8 13C5.67 13 1 14.17 1 16.5V19H15V16.5C15 14.17 10.33 13 8 13ZM16 13C15.71 13 15.38 13.02 15.03 13.05C16.19 13.89 17 15.02 17 16.5V19H23V16.5C23 14.17 18.33 13 16 13Z" />
                            </svg>
                        </span>
                        <span class="mkt__trust-text">
                            <b>120+ Mitra</b>
                            <small>Industri Global</small>
                        </span>
                    </div>
                    <div class="mkt__trust-item">
                        <span class="mkt__trust-ico">
                            <svg viewBox="0 0 24 24" fill="currentColor" stroke="none">
                                <path
                                    d="M12 1L3 5V11C3 16.55 6.84 21.74 12 23C17.16 21.74 21 16.55 21 11V5L12 1ZM10.94 15.54L7.4 12L8.81 10.59L10.94 12.72L15.18 8.48L16.59 9.89L10.94 15.54Z" />
                            </svg>
                        </span>
                        <span class="mkt__trust-text">
                            <b>Lulusan Siap Kerja</b>
                            <small>92% Terserap</small>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@push('js')
    <script @if (csp_nonce()) nonce="{{ csp_nonce() }}" @endif>
        (function() {
            'use strict';

            const section = document.querySelector('[data-market-section]');
            if (!section) return;

            /* ---------- 1. Accordion ---------- */
            section.querySelectorAll('[data-accordion]').forEach((list) => {
                list.addEventListener('click', (e) => {
                    const btn = e.target.closest('.mkt__item-head');
                    if (!btn) return;
                    const item = btn.closest('.mkt__item');
                    const body = item.querySelector('.mkt__item-body');
                    const isOpen = item.classList.contains('is-open');

                    // tutup semua
                    list.querySelectorAll('.mkt__item').forEach((it) => {
                        it.classList.remove('is-open');
                        it.querySelector('.mkt__item-head').setAttribute('aria-expanded',
                            'false');
                        const b = it.querySelector('.mkt__item-body');
                        if (b) b.hidden = true;
                    });

                    // buka yang diklik (toggle)
                    if (!isOpen) {
                        item.classList.add('is-open');
                        btn.setAttribute('aria-expanded', 'true');
                        if (body) body.hidden = false;
                    }
                });
            });

            /* ---------- 2. Counter animasi ---------- */
            const counters = section.querySelectorAll('.mkt__counter');
            const animateCounter = (el) => {
                const target = parseInt(el.dataset.target, 10) || 0;
                const suffix = el.dataset.suffix || '';
                const duration = 1400;
                const start = performance.now();

                const step = (now) => {
                    const p = Math.min((now - start) / duration, 1);
                    const eased = 1 - Math.pow(1 - p, 3);
                    el.textContent = Math.round(eased * target) + suffix;
                    if (p < 1) requestAnimationFrame(step);
                };
                requestAnimationFrame(step);
            };

            if ('IntersectionObserver' in window) {
                const io = new IntersectionObserver((entries) => {
                    entries.forEach((entry) => {
                        if (!entry.isIntersecting) return;
                        animateCounter(entry.target);
                        io.unobserve(entry.target);
                    });
                }, {
                    threshold: 0.4
                });
                counters.forEach((c) => io.observe(c));
            } else {
                counters.forEach(animateCounter);
            }

            /* ---------- 3. Hover effect pada visual (frame + shine) ---------- */
            const visual = section.querySelector('[data-visual]');
            if (visual) {
                const frame = visual.querySelector('.mkt__visual-frame');
                const onEnter = () => frame && frame.classList.add('is-hovered');
                const onLeave = () => frame && frame.classList.remove('is-hovered');

                // Desktop: hover
                visual.addEventListener('mouseenter', onEnter);
                visual.addEventListener('mouseleave', onLeave);

                // Touch: tap toggle
                visual.addEventListener('touchstart', () => {
                    if (!frame) return;
                    frame.classList.toggle('is-hovered');
                }, {
                    passive: true
                });
            }
        })();
    </script>
@endpush
