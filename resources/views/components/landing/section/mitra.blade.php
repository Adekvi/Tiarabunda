@props([
    'universitas' => [],
    'lainnya' => [],
    'kerjasama' => [],
    'basePath' => 'landing/assets/img/webp',
])

@php
    $defaultUniversitas = [
        'ui.webp',
        'unpad.webp',
        'gunadharma.webp',
        'unpancasila.webp',
        'unjani.webp',
        'unpak.webp',
        'unbhakti.webp',
        'DONGSEO.webp',
        'Fooyin.webp',
        'NTUNHS.webp',
        'inti.webp',
        'lincoln.webp',
        'uis.webp',
        'stikesassyifa.webp',
        'stikesnh.webp',
        'untedc.webp',
    ];

    $defaultLainnya = [
        'rsannisa.webp',
        'rsbrimob.webp',
        'rspmibogor.webp',
        'rssentosa.webp',
        'rssitanala.webp',
        'rstuguibu.webp',
        'rsutangsel.webp',
        'klinikrizka.webp',
        'kimiafarma.webp',
        'viaapotek.webp',
        'dinkesdepok.webp',
        'dinkesmalteng.webp',
        'bnndepok.webp',
        'pemkabsukabumi.webp',
        'pemkotbekasi.webp',
        'kanwil_kumham_jabar.webp',
        'pmibogor.webp',
        'ibibekasi.webp',
        'bidandelima.webp',
        'kpri.webp',
        'ptavin.webp',
        'ptfast.webp',
        'aprat.webp',
        'ara.webp',
        'mrm.webp',
        'mvp.webp',
        'sali.webp',
    ];

    if (!empty($mitra)) {
        $allMitra = $mitra;
    } else {
        $listUniv = !empty($universitas) ? $universitas : $defaultUniversitas;
        $listLainnya = !empty($lainnya) ? $lainnya : $defaultLainnya;
        $allMitra = array_merge($listUniv, $listLainnya);
    }

    $prefix = trim($basePath, '/');

    $resolveLogo = function ($item) use ($prefix) {
        if (is_array($item)) {
            if (!empty($item['img'])) {
                return $item['img'];
            }
            $file = $item['file'] ?? '';
        } else {
            $file = (string) $item;
        }

        if ($file === '') {
            return '';
        }

        return str_starts_with($file, 'http') || str_starts_with($file, '/')
            ? $file
            : asset($prefix . '/' . ltrim($file, '/'));
    };

    $resolveAlt = function ($item) {
        if (is_array($item)) {
            $label = $item['alt'] ?? ($item['name'] ?? ($item['file'] ?? ''));
        } else {
            $label = pathinfo((string) $item, PATHINFO_FILENAME);
        }

        $clean = trim(str_replace(['_', '-'], ' ', (string) $label));
        return $clean !== '' ? 'Logo ' . strtoupper($clean) : 'Logo Mitra';
    };

    $logos = [];
    foreach ($allMitra as $m) {
        $src = $resolveLogo($m);
        if ($src !== '') {
            $logos[] = [
                'src' => $src,
                'alt' => $resolveAlt($m),
            ];
        }
    }

    $totalMitra = count($logos);
@endphp

@if ($totalMitra > 0)
    <section class="sec mitra-sec" id="mitra" aria-labelledby="mitra-title">
        <div class="mitra-bg" aria-hidden="true">
            <div class="mitra-bg-glow"></div>
        </div>

        <div class="wrap position-relative">
            <div class="mitra-head">
                <div class="mitra-head-left">
                    <span class="kick">Kerja Sama</span>
                    <h2 id="mitra-title">Dipercaya berbagai institusi</h2>
                </div>

                <div class="mitra-badge" aria-label="Total mitra kerja sama">
                    <span class="mitra-badge-dot" aria-hidden="true"></span>
                    <span><strong>{{ $totalMitra }}</strong> Mitra Resmi</span>
                </div>
            </div>

            <div class="mitra-card-compact rv">
                <div class="mitra-marquee" aria-label="Daftar logo mitra kerja sama">
                    @for ($loopIndex = 0; $loopIndex < 2; $loopIndex++)
                        <div class="mitra-track" @if ($loopIndex === 1) aria-hidden="true" @endif>
                            @foreach ($logos as $logo)
                                <div class="mitra-logo" title="{{ $logo['alt'] }}">
                                    <img src="{{ $logo['src'] }}" alt="{{ $loopIndex === 0 ? $logo['alt'] : '' }}"
                                        width="120" height="46" loading="lazy" decoding="async"
                                        onerror="this.closest('.mitra-logo').style.display='none'">
                                </div>
                            @endforeach
                        </div>
                    @endfor
                </div>
            </div>
        </div>
    </section>
@endif
