@props(['data' => null, 'jajaran' => []])

@if ($data)
    @php
        $nama = data_get($data, 'nama', '');
        $jabatan = data_get($data, 'jabatan', 'Pimpinan Universitas');
        $fotoUrl = data_get($data, 'foto_url');
        $sambutan = (string) data_get($data, 'sambutan', '');
        $riwayat = (string) data_get($data, 'riwayat', '');

        $isSambutanHtml = strip_tags($sambutan) !== $sambutan;
        $isRiwayatHtml = strip_tags($riwayat) !== $riwayat;

        $inisial = collect(explode(' ', preg_replace('/[^a-zA-Z\s]/', '', $nama)))
            ->filter()
            ->take(2)
            ->map(fn($w) => mb_strtoupper(mb_substr($w, 0, 1)))
            ->implode('');
    @endphp

    @push('css')
        <link rel="stylesheet" href="{{ asset('landing/menu/profil/sambutan/sam.css') }}">
    @endpush

    <section class="smb">
        <div class="wrap smb__wrap">

            {{-- Header Judul Bagian Atas --}}
            <header class="smb__intro rv">
                <p class="smb__eyebrow">Sambutan Pimpinan</p>
                <h2 class="smb__title">Selamat Datang di {{ config('landing.site.nama') }}</h2>
                <p class="smb__lead">
                    Pesan dan komitmen pimpinan dalam mencetak lulusan vokasi yang kompeten, berintegritas, serta siap
                    bersaing di dunia industri.
                </p>
            </header>

            <div class="smb__grid">

                {{-- Kolom Kiri: Kartu Foto & Identitas Pimpinan --}}
                <aside class="smb__aside rv">
                    <figure class="smb__card">
                        <div class="smb__photo">
                            @if ($fotoUrl)
                                <img src="{{ $fotoUrl }}" alt="{{ $nama }}" loading="lazy"
                                    decoding="async">
                            @else
                                <div class="smb__placeholder" aria-hidden="true">
                                    <span>{{ $inisial ?: 'UTB' }}</span>
                                </div>
                            @endif
                        </div>

                        <figcaption class="smb__caption">
                            @if ($jabatan)
                                <span class="smb__role">{{ $jabatan }}</span>
                            @endif
                            <h3 class="smb__name">{{ $nama }}</h3>
                            <small class="smb__inst">{{ config('landing.site.nama') }}</small>
                        </figcaption>
                    </figure>
                </aside>

                {{-- Kolom Kanan: Isi Sambutan & Riwayat --}}
                <article class="smb__main rv">
                    <div class="smb__quote-mark" aria-hidden="true">“</div>

                    <div class="smb__body">
                        @if (filled($sambutan))
                            @if ($isSambutanHtml)
                                {!! $sambutan !!}
                            @else
                                @foreach (preg_split("/\r\n|\n|\r/", trim($sambutan)) as $paragraf)
                                    @if (filled(trim($paragraf)))
                                        <p>{{ trim($paragraf) }}</p>
                                    @endif
                                @endforeach
                            @endif
                        @else
                            <p>Sambutan pimpinan sedang diperbarui.</p>
                        @endif
                    </div>

                    <div class="smb__sign">
                        <strong>{{ $nama }}</strong>
                        @if ($jabatan)
                            <span>{{ $jabatan }}</span>
                        @endif
                    </div>

                    {{-- Riwayat Pendidikan / Karier (tampil otomatis jika kolom riwayat terisi) --}}
                    @if (filled(trim(strip_tags($riwayat))))
                        <div class="smb__riwayat">
                            <h4>Profil & Riwayat Singkat</h4>
                            <div class="smb__riwayat-body">
                                @if ($isRiwayatHtml)
                                    {!! $riwayat !!}
                                @else
                                    @foreach (preg_split("/\r\n|\n|\r/", trim($riwayat)) as $baris)
                                        @if (filled(trim($baris)))
                                            <p>{{ trim($baris) }}</p>
                                        @endif
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    @endif
                </article>
            </div>

            {{-- Opsional: Jika ada pimpinan lain berstatus aktif (urutan berikutnya) --}}
            @if (!empty($jajaran))
                <div class="smb__jajaran rv">
                    <h3 class="smb__jajaran-title">Jajaran Pimpinan</h3>
                    <div class="smb__jajaran-grid">
                        @foreach ($jajaran as $p)
                            <div class="smb__jajaran-card">
                                @if (!empty($p['foto_url']))
                                    <img src="{{ $p['foto_url'] }}" alt="{{ $p['nama'] }}" loading="lazy">
                                @endif
                                <div>
                                    @if (!empty($p['jabatan']))
                                        <small>{{ $p['jabatan'] }}</small>
                                    @endif
                                    <strong>{{ $p['nama'] }}</strong>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </section>
@endif
