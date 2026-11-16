<script src="{{ asset('landing/assets/js/site.js') }}?v={{ filemtime(public_path('landing/assets/js/site.js')) }}"
    defer></script>

{{-- Sejarah --}}
<script src="{{ asset('landing/menu/profil/sejarah/sej.js') }}" defer></script>
<script src="{{ asset('landing/menu/informasi/faq/faq.js') }}" defer></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const DESKTOP_MIN = 961;
        const PANEL_W = 360; // sama dengan width di CSS
        const NEED = PANEL_W + 8 + 16; // lebar panel + jarak + margin layar

        function clearPanel(panel) {
            panel.classList.remove('flyout-left', 'flyout-stack');
            panel.style.maxHeight = '';
        }

        function closeNested(el) {
            el.classList.remove('open');
            el.querySelector('.dd-nested-toggle')?.setAttribute('aria-expanded', 'false');
            const pnl = el.querySelector('.dd-nested-panel');
            if (pnl) clearPanel(pnl);
        }

        function closeAllNested(except) {
            document.querySelectorAll('.dd-nested.open').forEach(function(el) {
                if (el !== except) closeNested(el);
            });
        }

        function place(parent) {
            const panel = parent.querySelector('.dd-nested-panel');
            if (!panel) return;
            clearPanel(panel);
            if (window.innerWidth < DESKTOP_MIN) return;

            const vw = document.documentElement.clientWidth;
            const vh = document.documentElement.clientHeight;
            const outer = parent.closest('.dd-p') || parent;
            const a = outer.getBoundingClientRect();

            if (vw - a.right >= NEED) {
                /* muat di kanan: default CSS */
            } else if (a.left >= NEED) {
                panel.classList.add('flyout-left');
            } else {
                panel.classList.add('flyout-stack');
                return;
            }

            const top = parent.getBoundingClientRect().top;
            panel.style.maxHeight = Math.max(vh - top - 16, 200) + 'px';
        }

        // fase capture: handler lama tidak ikut menangani tombol ini
        document.addEventListener('click', function(e) {
            const btn = e.target.closest('.dd-nested-toggle');
            if (!btn) return;
            e.preventDefault();
            e.stopPropagation();

            const parent = btn.closest('.dd-nested');
            const willOpen = !parent.classList.contains('open');

            closeAllNested(parent);
            parent.classList.toggle('open', willOpen);
            btn.setAttribute('aria-expanded', String(willOpen));

            // jaga dropdown induk tetap terbuka saat kursor menyeberang ke flyout
            const top = parent.parentElement.closest('.dd');
            if (top && willOpen) top.classList.add('open');

            const panel = parent.querySelector('.dd-nested-panel');
            if (willOpen && panel) {
                panel.scrollTop = 0;
                place(parent);
            } else if (panel) {
                clearPanel(panel);
            }
        }, true);

        let rt;
        window.addEventListener('resize', function() {
            clearTimeout(rt);
            rt = setTimeout(function() {
                document.querySelectorAll('.dd-nested.open').forEach(place);
            }, 100);
        });

        document.addEventListener('click', function(e) {
            if (!e.target.closest('.dd-nested')) closeAllNested();
        });

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') closeAllNested();
        });
    });
</script>
