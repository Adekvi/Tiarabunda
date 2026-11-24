@props(['fakultas' => [], 'prodi' => []])

@php
    $warna = [
        'D3|Kebidanan' => ['#2f6db5', '#eaf2fc', '#173b66'],
        'D4|Kebidanan' => ['#55a8e0', '#eaf6fd', '#1d5f8a'],
        'Kebidanan' => ['#2f6db5', '#eaf2fc', '#173b66'],
        'Farmasi' => ['#8cc63f', '#f3fae3', '#3f5f10'],
        'Keperawatan Bidan Anestesiologi' => ['#1f6b4f', '#e7f2ec', '#0f3d2e'],
        'Pendidikan Profesi Bidan' => ['#8a2b3d', '#f8ecee', '#561826'],
        'Pendidikan Guru PAUD' => ['#d0728c', '#fcedf1', '#7a2e45'],
        'Pendidikan Guru Sekolah Dasar (PGSD)' => ['#c99a2e', '#fbf3de', '#6e5210'],
        'Teknologi Perangkat Lunak' => ['#5a62c9', '#eceefb', '#2b3182'],
        'Sistem Informasi' => ['#2a9d8f', '#e5f5f2', '#14554d'],
        'Kecerdasan Buatan' => ['#7e5bc2', '#f0eafa', '#44297d'],
        'Ilmu Komputer' => ['#5f7189', '#edf1f6', '#2a3a4f'],
        'Logistik Bisnis' => ['#cf7a5a', '#fbeee8', '#7a3520'],
        'Bisnis Digital' => ['#b0629f', '#f8ebf5', '#66285a'],
        'Manajemen' => ['#8b7a66', '#f4f0ea', '#4a3e30'],
    ];
    $netral = ['#7a8aa0', '#f1f4f8', '#2c3a4d'];

    // Hitung jumlah prodi per fakultas & kumpulkan daftar jenjang unik
    $countPerFak = [];
    $daftarJenjang = [];
    foreach ($prodi as $p) {
        $fi = $p['fakultas'] ?? null;
        if ($fi !== null) {
            $countPerFak[$fi] = ($countPerFak[$fi] ?? 0) + 1;
        }
        if (!empty($p['jenjang']) && !in_array($p['jenjang'], $daftarJenjang, true)) {
            $daftarJenjang[] = $p['jenjang'];
        }
    }
@endphp

@if (count($prodi) > 0)
    <section class="sec prodi-sec bg-arch-light" id="prodi" aria-labelledby="prodi-title">
        {{-- Ornamen grid halus --}}
        <div class="prodi-bg" aria-hidden="true">
            <div class="prodi-bg-grid"></div>
        </div>

        <div class="wrap position-relative">
            {{-- Header & Ringkasan Akademik --}}
            <div class="prodi-head-wrap">
                <x-landing.ui.head kick="Program studi" title="Temukan jurusan yang cocok untukmu" :lead="count($prodi) . ' program studi pilihan di ' . count($fakultas) . ' fakultas unggulan.'"
                    id="prodi-title" />

                <div class="prodi-summary" aria-label="Ringkasan jenjang pendidikan">
                    <div class="prodi-summary-stat">
                        <strong>{{ count($prodi) }}</strong>
                        <span>Program Studi</span>
                    </div>
                    <span class="prodi-summary-div" aria-hidden="true"></span>
                    <div class="prodi-summary-levels">
                        <small>Jenjang Tersedia</small>
                        <div class="prodi-level-pills">
                            @forelse ($daftarJenjang as $j)
                                <span>{{ $j }}</span>
                            @empty
                                <span>Diploma &amp; Profesi</span>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tabs --}}
            <div class="prodi-tabs rv" id="tabs" role="tablist" aria-label="Filter Fakultas">
                <button type="button" class="prodi-tab on" role="tab" data-f="-1" aria-selected="true">
                    Semua
                    <span class="prodi-count">{{ count($prodi) }}</span>
                </button>

                @foreach ($fakultas as $i => $f)
                    <button type="button" class="prodi-tab" role="tab" data-f="{{ $i }}"
                        aria-selected="false">
                        {{ $f }}
                        @if (!empty($countPerFak[$i]))
                            <span class="prodi-count">{{ $countPerFak[$i] }}</span>
                        @endif
                    </button>
                @endforeach
            </div>

            {{-- Grid --}}
            <div class="prodi-grid" id="grid">
                @foreach ($prodi as $i => $p)
                    @php
                        $fi = $p['fakultas'] ?? null;
                        $nama = $p['nama'] ?? '';
                        $jenjang = $p['jenjang'] ?? '';
                        [$c, $cs, $cd] = $warna[$jenjang . '|' . $nama] ?? ($warna[$nama] ?? $netral);
                    @endphp

                    @if ($fi !== null && isset($fakultas[$fi]))
                        <a href="/pendidikan/program-pendidikan" class="pr" data-f="{{ $fi }}"
                            data-prodi-card
                            style="--i:{{ $i }}; --c:{{ $c }}; --c-s:{{ $cs }}; --c-d:{{ $cd }}">

                            <div class="pr-top">
                                <span class="pr-ring">{{ $jenjang ?: 'Prof' }}</span>
                                @if (!empty($p['gelar']))
                                    <span class="pr-gelar">{{ $p['gelar'] }}</span>
                                @endif
                            </div>

                            <h3>{{ $nama }}</h3>
                            <p class="pr-fak">{{ $fakultas[$fi] }}</p>

                            <div class="pr-foot">
                                <span>Pelajari selengkapnya</span>
                                <span class="pr-go"><x-landing.ui.arrow /></span>
                            </div>
                        </a>
                    @endif
                @endforeach
            </div>

            <div class="prodi-empty" id="prodi-empty" hidden>
                <p>Belum ada program studi di fakultas ini.</p>
            </div>
        </div>
    </section>
@endif
