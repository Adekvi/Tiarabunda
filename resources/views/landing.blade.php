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
        <x-landing.section.prodi />
        <x-landing.section.fasilitas />
        <x-landing.section.berita />
        <x-landing.section.jalur />
        <x-landing.section.faq />
        <x-landing.section.cta />
    </main>
    <x-landing.layout.footer />
</x-landing.layout.app>
