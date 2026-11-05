{{-- Beranda: dirender server-side oleh komponen Blade, JS hanya menyalakan interaksi.
     Konten ada di config/landing.php. --}}
<x-landing.layout.app page="home" :ssr="true">
    <x-landing.layout.topbar />
    <x-landing.layout.navbar />
    <main id="main">
        <x-landing.section.hero />
        <x-landing.section.info />
        <x-landing.section.mitra />
        <x-landing.section.sambutan />
        <x-landing.section.alasan />
        <x-landing.section.statistik />

        {{-- Pendidikan --}}
        <x-landing.section.prodi :fakultas="$home['fakultas_prodi']['fakultas'] ?? []" :prodi="$home['fakultas_prodi']['prodi'] ?? []" />

        {{-- Fasilitas --}}
        <x-landing.section.fasilitas :items="$home['fasilitas'] ?? []" />

        {{-- Berita --}}
        <x-landing.section.berita :items="$home['berita'] ?? []" />

        {{-- Jalur PMB Dinamis --}}
        <x-landing.section.jalur :items="$home['jalur'] ?? []" />

        {{-- Faq --}}
        <x-landing.section.faq :items="$home['faq'] ?? []" />

        <x-landing.section.cta />
    </main>
    <x-landing.layout.footer />
</x-landing.layout.app>
