<x-landing.layout.app :title="$page['t'] . ' — ' . config('landing.site.nama')" :description="$page['sub']">
    <x-landing.layout.topbar />
    <x-landing.layout.navbar />
    <main id="main">
        <x-landing.section.pagehero :menu="$menu" :page="$page" />
        @foreach ($page['blocks'] as $b)
            @switch($b['type'])
                @case('text')
                    <x-landing.section.richtext :block="$b" />
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

                @case('sambutan')
                    <x-landing.section.sambutan />
                @break
            @endswitch
        @endforeach
        <x-landing.section.cta />
    </main>
    <x-landing.layout.footer />
</x-landing.layout.app>
