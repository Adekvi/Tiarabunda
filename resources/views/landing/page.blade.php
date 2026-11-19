@php
    $blocks = $page['blocks'] ?? [];
    $sejarah = $sejarah ?? null;
    $visimisi = $visimisi ?? null;
    $sambutan = $sambutan ?? null;
    $jajaran = $jajaran ?? [];
    $struktur = $struktur ?? null;
    $program = $program ?? null;
    $selectedProdi = $selectedProdi ?? null;
    $pmbJalur = $pmbJalur ?? null;
    $beasiswaList = $beasiswaList ?? null;
    $akreditasiInstitusi = $akreditasiInstitusi ?? null;
    $perpusHighlight = $perpusHighlight ?? null;
    $perpusItems = $perpusItems ?? null;
    $kemahasiswaanHighlight = $kemahasiswaanHighlight ?? null;
    $kemahasiswaanItems = $kemahasiswaanItems ?? null;
    $karierHighlight = $karierHighlight ?? null;
    $karierItems = $karierItems ?? null;
    $lppmHighlight = $lppmHighlight ?? null;
    $lppmItems = $lppmItems ?? null;
    $selectedLppm = $selectedLppm ?? null;
    $fasilitasItems = $fasilitasItems ?? null;
    $beritaList = $beritaList ?? null;
    $kegiatanList = $kegiatanList ?? null;
    $faqList = $faqList ?? null;
@endphp

<x-landing.layout.app :title="$page['t'] . ' — ' . config('landing.site.nama')" :description="$page['sub']">
    <x-landing.layout.topbar />
    <x-landing.layout.navbar />

    <main id="main">
        <x-landing.section.pagehero :menu="$menu" :page="$page" />

        {{-- Sejarah --}}
        @if ($sejarah)
            <x-landing.section.profil.sejarah :item="$sejarah" />

            {{-- Visi misi --}}
        @elseif ($visimisi)
            <x-landing.section.profil.visimisi :data="$visimisi" />

            {{-- Sambutan --}}
        @elseif ($sambutan)
            <x-landing.section.profil.sambutan :data="$sambutan" :jajaran="$jajaran" />

            {{-- Struktur --}}
        @elseif ($struktur)
            <x-landing.section.profil.struktur :tree="$struktur" />

            {{-- Fakultas dan prodi --}}
        @elseif ($program || $selectedProdi)
            <x-landing.section.pendidikan.program :program="$program ?? []" :selected-prodi="$selectedProdi" />

            {{-- PMB --}}
        @elseif (isset($pmbJalur) && $pmbJalur)
            <x-landing.section.pendidikan.pmb :pmb-jalur="$pmbJalur" :selected-jalur="$selectedJalur ?? null" />

            {{-- @elseif ($beasiswaList)
            <x-landing.section.pendidikan.beasiswa :beasiswa-list="$beasiswaList" :selected-beasiswa="$selectedBeasiswa ?? null" /> --}}

            {{-- Akreditasi --}}
        @elseif ($akreditasiInstitusi)
            <x-landing.section.pendidikan.akreditasi :institusi="$akreditasiInstitusi" :prodi="$akreditasiProdi ?? []" />

            {{-- Perpustakaan --}}
        @elseif ($perpusHighlight || ($selectedPerpus ?? null))
            <x-landing.section.layanan.perpus :highlight="$perpusHighlight" :items="$perpusItems ?? []" :selected-perpus="$selectedPerpus ?? null" />

            {{-- Kemahasiswaan --}}
        @elseif (
            $kemahasiswaanHighlight ||
                ($selectedKemahasiswaan ?? null) ||
                ($showKemahasiswaanBeasiswa ?? false) ||
                ($selectedKemahasiswaanBeasiswa ?? null))
            <x-landing.section.layanan.kemaha :highlight="$kemahasiswaanHighlight" :items="$kemahasiswaanItems ?? []" :beasiswa-info="$kemahasiswaanBeasiswaInfo ?? null"
                :selected-kemahasiswaan="$selectedKemahasiswaan ?? null" :show-beasiswa-list="$showKemahasiswaanBeasiswa ?? false" :beasiswa-list="$kemahasiswaanBeasiswaList ?? []" :selected-beasiswa="$selectedKemahasiswaanBeasiswa ?? null" />

            {{-- Pusat Karir --}}
        @elseif ($karierHighlight)
            <x-landing.section.layanan.karier :highlight="$karierHighlight" :items="$karierItems ?? []" />

            {{-- Fasilitas --}}
        @elseif ($fasilitasItems)
            <x-landing.section.layanan.fasilitas :items="$fasilitasItems" />

            {{-- Lppm --}}
        @elseif ($lppmHighlight || $selectedLppm || $isLppmPortalProgress)
            <x-landing.section.lembaga.lppm :highlight="$lppmHighlight" :items="$lppmItems ?? []" :selected-lppm="$selectedLppm"
                :is-portal-progress="$isLppmPortalProgress" />

            {{-- Berita --}}
        @elseif ($beritaList)
            <x-landing.section.informasi.berita :berita-list="$beritaList" :selected-berita="$selectedBerita ?? null" />

            {{-- Kegiatan --}}
        @elseif ($kegiatanList)
            <x-landing.section.informasi.kegiatan :kegiatan-list="$kegiatanList" :selected-kegiatan="$selectedKegiatan ?? null" />

            {{-- Faq --}}
        @elseif ($faqList)
            <x-landing.section.informasi.faq :faq-list="$faqList" />
        @else
            <section class="sec page-body">
                <div class="wrap page-wrap">
                    <div class="page-main prose page-main--full">
                        @forelse ($blocks as $i => $b)
                            @php
                                $type = $b['type'] ?? 'text';
                                $anchor = 'bagian-' . ($i + 1);
                            @endphp
                            @switch($type)
                                @case('text')
                                    <x-landing.section.richtext :block="$b" :anchor="$anchor" />
                                @break

                                @case('cards')
                                    <x-landing.section.block.block-card :block="$b" :anchor="$anchor" />
                                @break

                                @case('timeline')
                                    <x-landing.section.block.block-timeline :block="$b" :anchor="$anchor" />
                                @break

                                @case('notice')
                                    <x-landing.section.block.block-notice :block="$b" :anchor="$anchor" />
                                @break

                                @case('split')
                                    <x-landing.section.block.block-split :block="$b" :anchor="$anchor" />
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

                                @default
                                    <x-landing.section.richtext :block="$b" :anchor="$anchor" />
                            @endswitch
                            @empty
                                <article class="page-empty rv">
                                    <h2>Konten segera tersedia</h2>
                                    <p>Halaman <strong>{{ $page['t'] }}</strong> sedang disiapkan.</p>
                                    <a class="btn" href="/">Kembali ke beranda</a>
                                </article>
                            @endforelse
                        </div>
                    </div>
                </section>
            @endif

            <x-landing.section.cta />
        </main>

        <x-landing.layout.footer />
    </x-landing.layout.app>
