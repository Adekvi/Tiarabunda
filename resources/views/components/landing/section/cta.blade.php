@props(['site' => config('landing.site')])
<section class="cta">
    <div class="wrap rv">
        <h2>Siap mulai kuliah di {{ $site['nama'] }}?</h2>
        <p>Tim admisi kami siap membantu lewat WhatsApp.</p>
        <div class="row"><a class="btn" href="{{ $site['pmb'] }}">Daftar PMB</a><a class="btn ghost w"
                href="{{ $site['kontak']['wa'] }}">Konsultasi via WhatsApp</a></div>
    </div>
</section>
