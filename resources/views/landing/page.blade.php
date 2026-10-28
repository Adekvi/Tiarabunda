@php($title = $data['hero']['judul'] . ' — ' . config('landing.site.nama'))
<x-landing.layout.app :title="$title">
    <x-landing.layout.topbar />
    <x-landing.layout.navbar />
    <main id="main">
        <x-landing.section.pagehero :hero="$data['hero']" />
        @foreach ($data['sections'] as $s)
            @switch($s['type'])
                @case('text')
                    <x-landing.section.richtext :blok="$s['blok']" />
                @break

                @case('prodi')
                    <x-landing.section.prodi />
                @break

                @case('jalur')
                    <x-landing.section.jalur />
                @break

                @case('faq')
                    <x-landing.section.faq />
                @break

                @case('berita')
                    <x-landing.section.berita />
                @break

                @case('fasilitas')
                    <x-landing.section.fasilitas />
                @break
            @endswitch
        @endforeach
        <x-landing.section.cta />
    </main>
    <x-landing.layout.footer />
</x-landing.layout.app>
