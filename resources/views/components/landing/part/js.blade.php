<script src="{{ asset('landing/assets/js/site.js') }}?v={{ filemtime(public_path('landing/assets/js/site.js')) }}"
    defer></script>

{{-- Sejarah --}}
<script src="{{ asset('landing/menu/profil/sejarah/sej.js') }}" defer></script>

<script>
    /* Toggle nested dropdown Program Pendidikan */
    document.querySelectorAll('.dd-nested-toggle').forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();

            const parent = btn.closest('.dd-nested');
            const isOpen = parent.classList.contains('open');

            // Tutup semua nested dropdown lain (opsional, agar hanya 1 terbuka)
            document.querySelectorAll('.dd-nested.open').forEach(function(other) {
                if (other !== parent) {
                    other.classList.remove('open');
                    const t = other.querySelector('.dd-nested-toggle');
                    if (t) t.setAttribute('aria-expanded', 'false');
                }
            });

            // Toggle current
            parent.classList.toggle('open', !isOpen);
            btn.setAttribute('aria-expanded', String(!isOpen));
        });
    });

    /* Tutup jika klik di luar */
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.dd-nested')) {
            document.querySelectorAll('.dd-nested.open').forEach(function(el) {
                el.classList.remove('open');
                const t = el.querySelector('.dd-nested-toggle');
                if (t) t.setAttribute('aria-expanded', 'false');
            });
        }
    });

    /* Tutup saat ESC */
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            document.querySelectorAll('.dd-nested.open').forEach(function(el) {
                el.classList.remove('open');
                const t = el.querySelector('.dd-nested-toggle');
                if (t) t.setAttribute('aria-expanded', 'false');
            });
        }
    });
</script>
