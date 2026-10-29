@props(['fakultas' => config('landing.home.fakultas'), 'prodi' => config('landing.home.prodi')])
<section class="sec" id="prodi">
    <div class="wrap">
        <x-landing.ui.head kick="Program studi" title="Temukan jurusan yang cocok untukmu" :lead="count($prodi) . ' program studi di ' . count($fakultas) . ' fakultas.'" />
        <div class="tabs rv" id="tabs" role="tablist"><button role="tab" class="on"
                data-f="-1">Semua</button>
            @foreach ($fakultas as $i => $f)
                <button role="tab" data-f="{{ $i }}">{{ $f }}</button>
            @endforeach
        </div>
        <div class="grid" id="grid">
            @foreach ($prodi as $i => $p)
                <a class="pr" data-f="{{ $p[2] }}" style="--i:{{ $i }}"
                    href="/pendidikan/program-pendidikan"><span class="lv">{{ $p[1] }}</span>
                    <h3>{{ $p[0] }}</h3>
                    <p>{{ $fakultas[$p[2]] }}</p><span class="more">Pelajari <x-landing.ui.arrow /></span>
                </a>
            @endforeach
        </div>
    </div>
</section>
