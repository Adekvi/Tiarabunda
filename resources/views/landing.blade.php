<x-landing.layout.app page="home" :ssr="true">
    <x-landing.layout.topbar />
    <x-landing.layout.navbar />
    <main id="main">

        {{-- Hero --}}
        <x-landing.section.hero :data="$home['hero'] ?? []" />

        {{-- Berita --}}
        <x-landing.section.berita :items="$home['berita'] ?? []" />

        {{-- Lokasi Kampus --}}
        <x-landing.section.lokasi :items="$home['lokasi'] ?? null" />

        {{-- Testimoni --}}
        <x-landing.section.testi :items="$home['testimoni'] ?? null" />

        {{-- Kerja sama --}}
        <x-landing.section.mitra />

        {{-- Faq --}}
        <x-landing.section.faq :items="$home['faq'] ?? []" />

        <x-landing.section.cta />
    </main>
    <x-landing.layout.footer />
</x-landing.layout.app>
