@props(['fakultas' => [], 'prodi' => []])

@if (count($prodi) > 0)
    <section class="sec" id="prodi">
        <div class="wrap">
            <x-landing.ui.head kick="Program studi" title="Temukan jurusan yang cocok untukmu" :lead="count($prodi) . ' program studi di ' . count($fakultas) . ' fakultas.'" />
            <div class="tabs rv" id="tabs" role="tablist">
                <button role="tab" class="on" data-f="-1">Semua</button>
                @foreach ($fakultas as $i => $f)
                    <button role="tab" data-f="{{ $i }}">{{ $f }}</button>
                @endforeach
            </div>
            <div class="grid" id="grid">
                @foreach ($prodi as $i => $p)
                    @php $fi = $p['fakultas'] ?? null; @endphp
                    @if ($fi !== null && isset($fakultas[$fi]))
                        <a class="pr" data-f="{{ $fi }}" style="--i:{{ $i }}"
                            href="/pendidikan/program-pendidikan"><span class="lv">{{ $p['jenjang'] }}</span>
                            <h3>{{ $p['nama'] }}</h3>
                            <p>{{ $fakultas[$fi] }}</p><span class="more">Pelajari <x-landing.ui.arrow /></span>
                        </a>
                    @endif
                @endforeach
            </div>
        </div>
    </section>
@endif
