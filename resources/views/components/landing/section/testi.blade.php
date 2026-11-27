@props(['items' => null])

@php
    if ($items instanceof \Illuminate\Support\Collection) {
        $items = $items->all();
    }

    $avatars = [
        "data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 120 120'><defs><linearGradient id='g1' x1='0' y1='0' x2='1' y2='1'><stop offset='0%25' stop-color='%23dbeafe'/><stop offset='100%25' stop-color='%23bfdbfe'/></linearGradient></defs><rect width='120' height='120' fill='url(%23g1)'/><path d='M33 52 C33 30, 87 30, 87 52 L91 96 C75 104, 45 104, 29 96 Z' fill='%23173b66'/><path d='M22 120 C24 92, 42 84, 60 84 C78 84, 96 92, 98 120 Z' fill='%23ffffff'/><path d='M46 86 L60 104 L74 86' fill='none' stroke='%232f6db5' stroke-width='3' stroke-linecap='round'/><ellipse cx='60' cy='52' rx='17' ry='20' fill='%23f6c8a8'/><path d='M42 48 C44 33, 76 33, 78 48 C74 39, 46 39, 42 48 Z' fill='%231e497a'/><circle cx='54' cy='51' r='1.8' fill='%231e293b'/><circle cx='66' cy='51' r='1.8' fill='%231e293b'/><path d='M55 59 Q60 63 65 59' fill='none' stroke='%23b45309' stroke-width='2' stroke-linecap='round'/></svg>",
        "data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 120 120'><defs><linearGradient id='g2' x1='0' y1='0' x2='1' y2='1'><stop offset='0%25' stop-color='%23fef3c7'/><stop offset='100%25' stop-color='%23fde68a'/></linearGradient></defs><rect width='120' height='120' fill='url(%23g2)'/><path d='M22 120 C24 91, 41 82, 60 82 C79 82, 96 91, 98 120 Z' fill='%23173b66'/><path d='M48 82 L60 103 L72 82 Z' fill='%23ffffff'/><path d='M58 86 L62 86 L61 101 L59 101 Z' fill='%23f2994a'/><rect x='52' y='67' width='16' height='18' rx='7' fill='%23eab38f'/><ellipse cx='60' cy='50' rx='17.5' ry='20.5' fill='%23f2bfa0'/><path d='M42 46 C42 29, 78 29, 78 46 C77 37, 68 35, 60 35 C51 35, 43 38, 42 46 Z' fill='%231e293b'/><circle cx='54' cy='50' r='1.8' fill='%231e293b'/><circle cx='66' cy='50' r='1.8' fill='%231e293b'/><path d='M55 58 Q60 62 65 58' fill='none' stroke='%239a3412' stroke-width='2' stroke-linecap='round'/></svg>",
        "data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 120 120'><defs><linearGradient id='g3' x1='0' y1='0' x2='1' y2='1'><stop offset='0%25' stop-color='%23e0f2fe'/><stop offset='100%25' stop-color='%23bae6fd'/></linearGradient></defs><rect width='120' height='120' fill='url(%23g3)'/><path d='M38 48 C38 28, 82 28, 82 48 L84 82 L36 82 Z' fill='%231e293b'/><path d='M22 120 C24 91, 41 83, 60 83 C79 83, 96 91, 98 120 Z' fill='%232f6db5'/><path d='M47 83 Q60 95 73 83' fill='none' stroke='%23ffffff' stroke-width='3' stroke-linecap='round'/><rect x='53' y='67' width='14' height='18' rx='7' fill='%23f1c2a3'/><ellipse cx='60' cy='51' rx='17' ry='20' fill='%23f7cbb0'/><path d='M43 46 C47 34, 73 34, 77 46 C71 39, 49 39, 43 46 Z' fill='%231e293b'/><circle cx='54' cy='51' r='1.8' fill='%231e293b'/><circle cx='66' cy='51' r='1.8' fill='%231e293b'/><path d='M55 59 Q60 63 65 59' fill='none' stroke='%23b45309' stroke-width='2' stroke-linecap='round'/></svg>",
        "data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 120 120'><defs><linearGradient id='g4' x1='0' y1='0' x2='1' y2='1'><stop offset='0%25' stop-color='%23ede9fe'/><stop offset='100%25' stop-color='%23ddd6fe'/></linearGradient></defs><rect width='120' height='120' fill='url(%23g4)'/><path d='M22 120 C24 91, 41 82, 60 82 C79 82, 96 91, 98 120 Z' fill='%230f2744'/><path d='M49 82 L60 100 L71 82 Z' fill='%23f8fafc'/><rect x='52' y='67' width='16' height='18' rx='7' fill='%23e8b08c'/><ellipse cx='60' cy='50' rx='17' ry='20' fill='%23f2bfa0'/><path d='M43 45 C44 30, 76 30, 77 45 C72 37, 48 37, 43 45 Z' fill='%230f172a'/><rect x='47' y='47' width='10' height='7' rx='2' fill='none' stroke='%231e293b' stroke-width='1.6'/><rect x='63' y='47' width='10' height='7' rx='2' fill='none' stroke='%231e293b' stroke-width='1.6'/><line x1='57' y1='50' x2='63' y2='50' stroke='%231e293b' stroke-width='1.6'/><path d='M55 59 Q60 62 65 59' fill='none' stroke='%239a3412' stroke-width='2' stroke-linecap='round'/></svg>",
    ];

    $defaultTestimoni = [
        [
            'nama' => 'Ns. Nabila Putri Ramadhani, S.Kep.',
            'peran' => 'Alumni S1 Keperawatan & Profesi Ners',
            'angkatan' => 'Lulusan 2024',
            'kategori' => 'alumni',
            'instansi' => 'Perawat Klinik — RS Mitra Keluarga',
            'sorotan' => 'Siap praktik sejak hari pertama di rumah sakit',
            'kutipan' =>
                'Fasilitas laboratorium simulasi klinik yang lengkap membuat saya tidak canggung saat terjun praktik di rumah sakit. Dosen pembimbingnya mendampingi secara personal hingga lulus uji kompetensi dengan nilai memuaskan.',
            'rating' => 5,
            'img' => $avatars[0],
        ],
        [
            'nama' => 'Rizky Pratama Wijaya',
            'peran' => 'Mahasiswa Semester 6 — Manajemen',
            'angkatan' => 'Angkatan 2023',
            'kategori' => 'beasiswa',
            'instansi' => 'Penerima Beasiswa Prestasi Akademik',
            'sorotan' => 'Dukungan beasiswa & jejaring magang industri',
            'kutipan' =>
                'Program beasiswa prestasi sangat meringankan biaya kuliah sekaligus memotivasi saya aktif di riset dan organisasi. Jejaring magang kampusnya membuka akses langsung ke perusahaan mitra sebelum wisuda.',
            'rating' => 5,
            'img' => $avatars[1],
        ],
        [
            'nama' => 'Aulia Rahmawati, A.Md.Keb.',
            'peran' => 'Alumni D3 Kebidanan',
            'angkatan' => 'Lulusan 2024',
            'kategori' => 'alumni',
            'instansi' => 'Bidan Pelaksana — Puskesmas Kota Depok',
            'sorotan' => 'Kurikulum aplikatif & bimbingan karier terarah',
            'kutipan' =>
                'Bimbingan praktik klinik dan kerja sama kampus dengan berbagai fasilitas kesehatan membuat proses transisi dari bangku kuliah ke dunia kerja terasa jauh lebih terarah dan percaya diri.',
            'rating' => 5,
            'img' => $avatars[2],
        ],
        [
            'nama' => 'Muhammad Fadhil Akbar',
            'peran' => 'Mahasiswa Semester 4 — Sistem Informasi',
            'angkatan' => 'Angkatan 2024',
            'kategori' => 'mahasiswa',
            'instansi' => 'Ketua Himpunan & Asisten Laboratorium',
            'sorotan' => 'Ekosistem belajar modern & kolaboratif',
            'kutipan' =>
                'Suasana belajarnya hangat, ruang kelas nyaman, dan dosen selalu terbuka berdiskusi proyek nyata di luar jam kuliah. Kami didorong membangun portofolio sejak semester awal.',
            'rating' => 5,
            'img' => $avatars[3],
        ],
    ];

    $rawList = is_array($items) ? array_values($items) : $defaultTestimoni;

    $resolvePhoto = function (?string $img) use ($avatars): string {
        $src = trim((string) $img);
        if ($src === '') {
            return '';
        }
        if (str_starts_with($src, 'data:') || str_starts_with($src, 'http') || str_starts_with($src, '/')) {
            return $src;
        }
        return asset(ltrim($src, '/'));
    };

    $getInitials = function (?string $name): string {
        $clean = trim(preg_replace('/\b(Ns|Dr|Hj|H|Prof)\.|,.*$/u', '', (string) $name));
        $parts = preg_split('/\s+/', $clean, -1, PREG_SPLIT_NO_EMPTY);
        if (empty($parts)) {
            return 'TB';
        }
        $first = mb_substr($parts[0], 0, 1);
        $second = count($parts) > 1 ? mb_substr($parts[1], 0, 1) : '';
        return mb_strtoupper($first . $second);
    };

    $testimoniList = [];
    foreach ($rawList as $i => $row) {
        $nama = $row['nama'] ?? ($row['name'] ?? 'Mahasiswa');
        $kat = strtolower(trim((string) ($row['kategori'] ?? ($row['category'] ?? 'alumni'))));
        if (!in_array($kat, ['alumni', 'mahasiswa', 'beasiswa'], true)) {
            $kat = 'alumni';
        }

        $fotoRaw = $row['img'] ?? ($row['foto'] ?? '');

        $testimoniList[] = [
            'nama' => $nama,
            'peran' => $row['peran'] ?? ($row['role'] ?? ($row['prodi'] ?? 'Mahasiswa Aktif')),
            'angkatan' => $row['angkatan'] ?? ($row['tahun'] ?? 'Terverifikasi'),
            'kategori' => $kat,
            'instansi' => $row['instansi'] ?? ($row['karier'] ?? ''),
            'sorotan' => $row['sorotan'] ?? ($row['highlight'] ?? 'Pengalaman Akademik & Praktik'),
            'kutipan' => $row['kutipan'] ?? ($row['pesan'] ?? ($row['quote'] ?? '')),
            'rating' => max(1, min(5, (int) ($row['rating'] ?? 5))),
            'foto' => $resolvePhoto($fotoRaw),
            'initials' => $getInitials($nama),
        ];
    }

    $featured = $testimoniList[0] ?? null;
@endphp

@if (count($testimoniList))
    <section class="sec testimoni-sec" id="testimoni" data-testimoni-root aria-labelledby="testimoni-title">
        <div class="testi-bg" aria-hidden="true">
            <div class="testi-bg-grid"></div>
            <div class="testi-bg-glow testi-bg-glow--gold"></div>
            <div class="testi-bg-glow testi-bg-glow--navy"></div>
        </div>

        <div class="wrap position-relative">
            <div class="testi-head-wrap">
                <x-landing.ui.head kick="Apa kata mereka?" title="Cerita nyata dari mahasiswa & alumni"
                    lead="Pengalaman akademik, praktik klinik, dan perjalanan karier lulusan kami di berbagai institusi."
                    id="testimoni-title" />

                <div class="testi-meta" aria-label="Ringkasan kepuasan">
                    <span class="testi-meta-badge">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                            <path
                                d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" />
                        </svg>
                        <span><strong>4.9/5</strong> Indeks Kepuasan</span>
                    </span>
                    <span class="testi-meta-tag">Alumni &amp; Mahasiswa</span>
                </div>
            </div>

            @if ($featured)
                <div class="testi-spotlight rv" data-testi-spotlight>
                    <div class="testi-spotlight-pattern" aria-hidden="true"></div>

                    <div class="testi-spotlight-grid">
                        <div class="testi-spot-profile">
                            <div class="testi-spot-avatar-wrap">
                                <div class="testi-spot-ring" aria-hidden="true"></div>
                                <div class="testi-spot-avatar">
                                    @if ($featured['foto'] !== '')
                                        <img src="{{ $featured['foto'] }}" alt="Foto {{ $featured['nama'] }}"
                                            width="96" height="96" data-spot-img>
                                        <span class="testi-spot-initials" data-spot-initials style="display: none;">
                                            {{ $featured['initials'] }}
                                        </span>
                                    @else
                                        <img src="" alt="" width="96" height="96" data-spot-img
                                            style="display: none;">
                                        <span class="testi-spot-initials" data-spot-initials>
                                            {{ $featured['initials'] }}
                                        </span>
                                    @endif
                                </div>
                                <span class="testi-verified-badge" title="Kisah Terverifikasi" aria-hidden="true">
                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="3" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <polyline points="20 6 9 17 4 12"></polyline>
                                    </svg>
                                </span>
                            </div>

                            <div class="testi-spot-person">
                                <span class="testi-spot-pill" data-spot-angkatan>{{ $featured['angkatan'] }}</span>
                                <h3 class="testi-spot-name" data-spot-name>{{ $featured['nama'] }}</h3>
                                <p class="testi-spot-role" data-spot-role>{{ $featured['peran'] }}</p>
                                <span class="testi-spot-instansi" data-spot-instansi>{{ $featured['instansi'] }}</span>
                            </div>
                        </div>

                        <div class="testi-spot-content" aria-live="polite">
                            <div class="testi-spot-top">
                                <span class="testi-spot-kicker">
                                    <span class="testi-spot-dot" aria-hidden="true"></span>
                                    <span data-spot-sorotan>{{ $featured['sorotan'] }}</span>
                                </span>

                                <div class="testi-spot-stars" aria-label="Rating 5 bintang">
                                    @for ($s = 0; $s < 5; $s++)
                                        <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"
                                            aria-hidden="true">
                                            <path
                                                d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" />
                                        </svg>
                                    @endfor
                                </div>
                            </div>

                            <blockquote class="testi-spot-quote" data-spot-quote>
                                “{{ $featured['kutipan'] }}”
                            </blockquote>

                            {{-- Kontrol Navigasi Spotlight & Pemilih Avatar Cepat --}}
                            <div class="testi-spot-controls">
                                <div class="testi-spot-avatars" role="tablist"
                                    aria-label="Pilih cerita mahasiswa atau alumni">
                                    @foreach ($testimoniList as $idx => $item)
                                        <button type="button"
                                            class="testi-mini-btn {{ $idx === 0 ? 'is-active' : '' }}"
                                            data-spot-trigger="{{ $idx }}"
                                            aria-selected="{{ $idx === 0 ? 'true' : 'false' }}"
                                            title="Tampilkan cerita {{ $item['nama'] }}">
                                            @if ($item['foto'] !== '')
                                                <img src="{{ $item['foto'] }}" alt="{{ $item['nama'] }}"
                                                    width="36" height="36">
                                            @else
                                                <span>{{ $item['initials'] }}</span>
                                            @endif
                                        </button>
                                    @endforeach
                                </div>

                                <div class="testi-spot-nav">
                                    <span class="testi-spot-counter">
                                        <strong data-spot-index>01</strong> /
                                        {{ str_pad((string) count($testimoniList), 2, '0', STR_PAD_LEFT) }}
                                    </span>
                                    <button type="button" class="testi-nav-btn" data-spot-prev
                                        aria-label="Testimoni sebelumnya">
                                        <svg width="17" height="17" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2.2" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <path d="M19 12H5M12 19l-7-7 7-7" />
                                        </svg>
                                    </button>
                                    <button type="button" class="testi-nav-btn" data-spot-next
                                        aria-label="Testimoni berikutnya">
                                        <svg width="17" height="17" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2.2" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <path d="M5 12h14M12 5l7 7-7 7" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <div class="testi-toolbar rv">
                <div class="testi-filters" role="tablist" aria-label="Filter kategori testimoni">
                    <button type="button" class="testi-filter-btn is-active" data-testi-filter="all"
                        aria-selected="true">
                        Semua Cerita
                        <span class="testi-filter-count">{{ count($testimoniList) }}</span>
                    </button>
                    <button type="button" class="testi-filter-btn" data-testi-filter="alumni"
                        aria-selected="false">
                        Alumni Berkarier
                    </button>
                    <button type="button" class="testi-filter-btn" data-testi-filter="mahasiswa"
                        aria-selected="false">
                        Mahasiswa Aktif
                    </button>
                    <button type="button" class="testi-filter-btn" data-testi-filter="beasiswa"
                        aria-selected="false">
                        Penerima Beasiswa
                    </button>
                </div>

                <span class="testi-toolbar-hint">Klik kartu untuk menyorot cerita</span>
            </div>

            <div class="testi-grid rv" data-testi-grid>
                @foreach ($testimoniList as $idx => $t)
                    @php
                        $variantClass = match ($idx % 3) {
                            1 => 'testi-card--navy',
                            2 => 'testi-card--glass',
                            default => 'testi-card--ivory',
                        };
                    @endphp

                    <article class="testi-card {{ $variantClass }} {{ $idx === 0 ? 'is-spotlighted' : '' }}"
                        data-testi-card="{{ $idx }}" data-category="{{ $t['kategori'] }}"
                        data-nama="{{ $t['nama'] }}" data-peran="{{ $t['peran'] }}"
                        data-angkatan="{{ $t['angkatan'] }}" data-instansi="{{ $t['instansi'] }}"
                        data-sorotan="{{ $t['sorotan'] }}" data-kutipan="{{ $t['kutipan'] }}"
                        data-foto="{{ $t['foto'] }}" data-initials="{{ $t['initials'] }}"
                        style="--i: {{ $idx }};">

                        <div class="testi-card-top">
                            <span class="testi-cat-chip">
                                {{ $t['angkatan'] }}
                            </span>

                            <div class="testi-stars" aria-label="Penilaian {{ $t['rating'] }} dari 5 bintang">
                                @for ($s = 1; $s <= 5; $s++)
                                    <svg width="14" height="14" viewBox="0 0 24 24"
                                        fill="{{ $s <= $t['rating'] ? 'currentColor' : 'none' }}"
                                        stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                        <path
                                            d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" />
                                    </svg>
                                @endfor
                            </div>
                        </div>

                        <p class="testi-card-highlight">{{ $t['sorotan'] }}</p>

                        <blockquote class="testi-quote">
                            “{{ $t['kutipan'] }}”
                        </blockquote>

                        <div class="testi-author">
                            <div class="testi-avatar">
                                @if ($t['foto'] !== '')
                                    <img src="{{ $t['foto'] }}" alt="Foto {{ $t['nama'] }}" width="52"
                                        height="52" loading="lazy" decoding="async"
                                        onerror="this.style.display='none'; this.nextElementSibling.style.display='grid';">
                                    <span class="testi-initials" style="display: none;"
                                        aria-hidden="true">{{ $t['initials'] }}</span>
                                @else
                                    <span class="testi-initials" aria-hidden="true">{{ $t['initials'] }}</span>
                                @endif
                            </div>

                            <div class="testi-author-info">
                                <h3 class="testi-name">{{ $t['nama'] }}</h3>
                                <p class="testi-role">{{ $t['peran'] }}</p>
                                @if ($t['instansi'] !== '')
                                    <span class="testi-instansi">{{ $t['instansi'] }}</span>
                                @endif
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>

            {{-- <div class="testi-trust-bar rv" aria-label="Statistik kepuasan dan lulusan">
                <div class="testi-trust-item">
                    <span class="testi-trust-val">4.9<span>/5.0</span></span>
                    <span class="testi-trust-lbl">Kepuasan Layanan Akademik &amp; Lab</span>
                </div>
                <div class="testi-trust-sep" aria-hidden="true"></div>
                <div class="testi-trust-item">
                    <span class="testi-trust-val"><span data-to="94">94</span><span>%</span></span>
                    <span class="testi-trust-lbl">Lulusan Terserap Kerja &lt; 6 Bulan</span>
                </div>
                <div class="testi-trust-sep" aria-hidden="true"></div>
                <div class="testi-trust-item">
                    <span class="testi-trust-val"><span data-to="43">43</span><span>+</span></span>
                    <span class="testi-trust-lbl">Rumah Sakit, Klinik &amp; Mitra Industri</span>
                </div>
            </div> --}}
        </div>
    </section>
@endif
