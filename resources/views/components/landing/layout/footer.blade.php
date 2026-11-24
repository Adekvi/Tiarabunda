@props([
    'site' => config('landing.site'),
    'menus' => config('landing.menus'),
    'fakultas' => config('landing.home.fakultas', []),
])

@php
    $sosial = array_filter($site['sosial'] ?? [], fn($url) => filled($url));
    $icons = [
        'youtube' => [
            'label' => 'YouTube',
            'path' =>
                'M23.5 6.2a3 3 0 0 0-2.1-2.1C19.5 3.5 12 3.5 12 3.5s-7.5 0-9.4.6A3 3 0 0 0 .5 6.2 31.5 31.5 0 0 0 0 12a31.5 31.5 0 0 0 .5 5.8 3 3 0 0 0 2.1 2.1c1.9.6 9.4.6 9.4.6s7.5 0 9.4-.6a3 3 0 0 0 2.1-2.1A31.5 31.5 0 0 0 24 12a31.5 31.5 0 0 0-.5-5.8zM9.8 15.5v-7l6.2 3.5-6.2 3.5z',
        ],
        'facebook' => [
            'label' => 'Facebook',
            'path' => 'M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z',
        ],
        'tiktok' => [
            'label' => 'TikTok',
            'path' =>
                'M19.6 7.2A5.8 5.8 0 0 1 16 5.9V15a4.8 4.8 0 1 1-4.8-4.8c.2 0 .5 0 .7.1v2.4a2.4 2.4 0 1 0 1.7 2.3V2h2.5a5.8 5.8 0 0 0 3.5 5.2z',
        ],
        'linktree' => [
            'label' => 'Linktree',
            'path' =>
                'M10 2v6.5L6.5 5 5 6.5 10 12l-5 5.5L6.5 19 10 15.5V22h2v-6.5L15.5 19 17 17.5 12 12l5-5.5L15.5 5 12 8.5V2h-2z',
        ],
        'instagram' => [
            'label' => 'Instagram',
            'path' =>
                'M7 2h10a5 5 0 0 1 5 5v10a5 5 0 0 1-5 5H7a5 5 0 0 1-5-5V7a5 5 0 0 1 5-5zm5 5a5 5 0 1 0 0 10 5 5 0 0 0 0-10zm6.5-.9a1.2 1.2 0 1 0 0 2.4 1.2 1.2 0 0 0 0-2.4zM12 9a3 3 0 1 1 0 6 3 3 0 0 1 0-6z',
        ],
    ];
    $kontak = $site['kontak'] ?? [];
@endphp

<footer class="ft">
    <div class="ft__wrap">

        {{-- @if (!empty($site['pmb']))
            <div class="ft__cta">
                <div>
                    <h3>Siap bergabung dengan {{ $site['nama'] }}?</h3>
                    <p>Pendaftaran mahasiswa baru terbuka. Mulai langkah pertamamu hari ini.</p>
                </div>
                <a href="{{ $site['pmb'] }}" class="ft__btn">
                    Daftar Sekarang
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M5 12h14M13 6l6 6-6 6" />
                    </svg>
                </a>
            </div>
        @endif --}}

        <div class="ft__grid">
            <div class="ft__brand">
                <a href="/" class="ft__logo">
                    <img src="{{ asset('landing/assets/img/logo.webp') }}" alt="" width="52" height="52"
                        loading="lazy" decoding="async" />
                    <span>
                        <b>{{ $site['nama'] }}</b>
                        <small>{{ $site['kota'] }}</small>
                    </span>
                </a>

                <ul class="ft__contact">
                    @php
                        $alamatList = $kontak['alamat'] ?? [];
                        if (is_string($alamatList) && $alamatList !== '') {
                            $alamatList = [['label' => null, 'isi' => $alamatList, 'maps' => null]];
                        }
                    @endphp

                    @foreach ($alamatList as $alamat)
                        <li class="ft__alamat">
                            <svg viewBox="0 0 24 24" aria-hidden="true">
                                <path d="M12 22s7-6.2 7-12a7 7 0 1 0-14 0c0 5.8 7 12 7 12z" />
                                <circle cx="12" cy="10" r="2.5" />
                            </svg>
                            <span>
                                @if (!empty($alamat['label']))
                                    <strong class="ft__alamat-label">{{ $alamat['label'] }}</strong>
                                @endif
                                @if (!empty($alamat['maps']))
                                    <a href="{{ $alamat['maps'] }}" target="_blank" rel="noopener noreferrer">
                                        {{ $alamat['isi'] }}
                                    </a>
                                @else
                                    {{ $alamat['isi'] }}
                                @endif
                            </span>
                        </li>
                    @endforeach
                    @if (!empty($kontak['telp']))
                        <li>
                            <svg viewBox="0 0 24 24" aria-hidden="true">
                                <path
                                    d="M5 4h4l2 5-2.5 1.5a11 11 0 0 0 5 5L15 13l5 2v4a2 2 0 0 1-2 2A16 16 0 0 1 3 6a2 2 0 0 1 2-2z" />
                            </svg>
                            <a href="tel:{{ preg_replace('/[^0-9+]/', '', $kontak['telp']) }}">{{ $kontak['telp'] }}</a>
                        </li>
                    @endif
                    @if (!empty($kontak['email']))
                        <li>
                            <svg viewBox="0 0 24 24" aria-hidden="true">
                                <rect x="3" y="5" width="18" height="14" rx="2" />
                                <path d="m3 7 9 6 9-6" />
                            </svg>
                            <a href="mailto:{{ $kontak['email'] }}">{{ $kontak['email'] }}</a>
                        </li>
                    @endif
                    @if (!empty($kontak['wa']))
                        <li>
                            <svg viewBox="0 0 24 24" aria-hidden="true">
                                <path d="M21 11.5a8.5 8.5 0 0 1-12.6 7.4L3 20l1.2-5.2A8.5 8.5 0 1 1 21 11.5z" />
                            </svg>
                            <a href="{{ $kontak['wa'] }}" target="_blank" rel="noopener noreferrer">Chat WhatsApp</a>
                        </li>
                    @endif
                </ul>

                @if ($sosial !== [])
                    <nav class="ft__sosial" aria-label="Media sosial">
                        @foreach ($sosial as $key => $url)
                            @continue(!isset($icons[$key]))
                            <a href="{{ $url }}" target="_blank" rel="noopener noreferrer"
                                aria-label="{{ $icons[$key]['label'] }}" title="{{ $icons[$key]['label'] }}">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"
                                    aria-hidden="true">
                                    <path d="{{ $icons[$key]['path'] }}" />
                                </svg>
                            </a>
                        @endforeach
                    </nav>
                @endif
            </div>

            <div class="ft__menus">
                @foreach ($menus ?? [] as $menuKey => $menu)
                    @php
                        $hasSubs = !empty($menu['subs']) && is_array($menu['subs']);
                        $hasLink = !empty($menu['link']);
                        $isFakultasMenu = $menuKey === 'pendidikan' && !empty($fakultas);
                    @endphp
                    @continue(!$hasSubs && !$hasLink && !$isFakultasMenu)

                    <nav class="ft__col" aria-label="{{ $menu['t'] }}">
                        <h4>{{ $menu['t'] }}</h4>
                        <ul>
                            @if ($isFakultasMenu)
                                @foreach ($fakultas as $namaFakultas)
                                    <li>
                                        <a
                                            href="{{ route('landing.prodi', [
                                                'menu' => 'pendidikan',
                                                'sub' => 'program-pendidikan',
                                                'prodi' => \Illuminate\Support\Str::slug($namaFakultas),
                                            ]) }}">
                                            Fakultas {{ $namaFakultas }}
                                        </a>
                                    </li>
                                @endforeach
                            @elseif ($hasSubs)
                                @foreach ($menu['subs'] as $subKey => $sub)
                                    <li>
                                        <a href="{{ route('landing.page', ['menu' => $menuKey, 'sub' => $subKey]) }}">
                                            {{ $sub['t'] }}
                                        </a>
                                    </li>
                                @endforeach
                            @elseif ($hasLink)
                                <li>
                                    <a href="{{ $menu['link'] }}" target="_blank" rel="noopener noreferrer">
                                        Pendaftaran Mahasiswa Baru
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </nav>
                @endforeach
            </div>
        </div>

        <div class="ft__bottom">
            <p>© {{ date('Y') }} {{ $site['nama'] }}. Hak cipta dilindungi.</p>

            @if (!empty($site['portal']))
                <ul class="ft__portal" aria-label="Portal">
                    @foreach ($site['portal'] as $p)
                        <li><a href="{{ $p[1] }}">{{ $p[0] }}</a></li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
</footer>
