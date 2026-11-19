@props(['alasan' => config('landing.home.alasan')])

<section class="sec why-section" id="alasan" data-why-section>
    {{-- Ornamen Background --}}
    <div class="why-bg" aria-hidden="true">
        <div class="why-bg-grid"></div>
        <div class="why-bg-glow"></div>
    </div>

    <div class="wrap position-relative">
        <x-landing.ui.head kick="Mengapa memilih kami" title="Belajar dengan arah yang jelas" />

        <div class="why-layout rv">
            {{-- Kiri: Navigasi Pilar --}}
            <div class="why-nav" role="tablist" aria-label="Keunggulan Kampus">
                @foreach ($alasan as $i => $x)
                    <button type="button" class="why-nav-item {{ $i === 0 ? 'is-active' : '' }}" role="tab"
                        data-why="{{ $i }}" aria-selected="{{ $i === 0 ? 'true' : 'false' }}"
                        aria-controls="panel-{{ $i }}">
                        <span class="why-nav-num">0{{ $i + 1 }}</span>
                        <span class="why-nav-text">{{ $x['t'] }}</span>
                        <span class="why-nav-arrow">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path d="M5 12h14M12 5l7 7-7 7" />
                            </svg>
                        </span>
                    </button>
                @endforeach
            </div>

            {{-- Kanan: Panel Detail --}}
            <div class="why-content">
                @foreach ($alasan as $i => $x)
                    <div class="why-panel {{ $i === 0 ? 'is-active' : '' }}" id="panel-{{ $i }}"
                        data-panel="{{ $i }}" role="tabpanel"
                        @if ($i !== 0) hidden @endif>

                        <div class="why-panel-head">
                            <span class="why-badge">Pilar 0{{ $i + 1 }}</span>
                            <h3>{{ $x['t'] }}</h3>
                        </div>

                        <p class="why-desc">{{ $x['d'] }}</p>

                        <div class="why-points">
                            @foreach ($x['p'] as $p)
                                <div class="why-point">
                                    <span class="why-point-icon">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                                            stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M20 6L9 17l-5-5" />
                                        </svg>
                                    </span>
                                    <span class="why-point-text">{{ $p }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

@push('js')
    <script @if (csp_nonce()) nonce="{{ csp_nonce() }}" @endif>
        (function() {
            const section = document.querySelector('[data-why-section]');
            if (!section) return;

            const navs = section.querySelectorAll('.why-nav-item');
            const panels = section.querySelectorAll('.why-panel');
            const navContainer = section.querySelector('.why-nav');

            function activateTab(id) {
                navs.forEach(n => {
                    const isActive = n.dataset.why === id;
                    n.classList.toggle('is-active', isActive);
                    n.setAttribute('aria-selected', isActive ? 'true' : 'false');
                });

                // Update panel
                panels.forEach(p => {
                    if (p.dataset.panel === id) {
                        p.removeAttribute('hidden');
                        void p.offsetWidth;
                        p.classList.add('is-active');
                    } else {
                        p.classList.remove('is-active');
                        p.setAttribute('hidden', '');
                    }
                });

                const activeNav = section.querySelector(`.why-nav-item[data-why="${id}"]`);
                if (activeNav && navContainer) {
                    const containerWidth = navContainer.offsetWidth;
                    const itemLeft = activeNav.offsetLeft;
                    const itemWidth = activeNav.offsetWidth;

                    navContainer.scrollTo({
                        left: itemLeft - (containerWidth / 2) + (itemWidth / 2),
                        behavior: 'smooth'
                    });
                }
            }

            navs.forEach(nav => {
                nav.addEventListener('click', () => {
                    activateTab(nav.dataset.why);
                });
            });
        })();
    </script>
@endpush
