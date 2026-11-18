@props(['institusi' => [], 'prodi' => []])

@php
    $inst = collect($institusi);
    $prodis = collect($prodi);

    // Helper warna badge berdasarkan status
    $getStatusClass = function ($status) {
        $status = strtolower($status ?? '');
        if (str_contains($status, 'unggul') || str_contains($status, 'a')) {
            return 'akr__badge--green';
        }
        if (str_contains($status, 'baik sekali') || str_contains($status, 'b')) {
            return 'akr__badge--blue';
        }
        if (str_contains($status, 'baik') || str_contains($status, 'c')) {
            return 'akr__badge--orange';
        }
        return 'akr__badge--gray';
    };
@endphp

<section class="akr">
    <div class="wrap akr__wrap">

        <div class="akr__intro rv">
            <p class="akr__eyebrow">Penjaminan Mutu</p>
            <h2 class="akr__title">Status Akreditasi</h2>
            <p class="akr__lead">
                Universitas Tiara Bunda berkomitmen menjaga kualitas pendidikan melalui akreditasi institusi dan program
                studi yang transparan.
            </p>
        </div>

        @if ($inst->isNotEmpty())
            <div class="akr__section rv">
                <h3 class="akr__section-title">Akreditasi Institusi</h3>

                <div class="akr__grid-inst">
                    @foreach ($inst as $i)
                        <div class="akr__card akr__card--inst">
                            @if (data_get($i, 'gambar_url'))
                                <div class="akr__card-img">
                                    <a href="{{ data_get($i, 'gambar_url') }}" target="_blank" title="Lihat Sertifikat">
                                        <img src="{{ data_get($i, 'gambar_url') }}"
                                            alt="Sertifikat {{ data_get($i, 'jeneng') }}" loading="lazy">
                                        <span class="akr__img-overlay">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="2">
                                                <circle cx="11" cy="11" r="8" />
                                                <path d="M21 21l-4.3-4.3" />
                                            </svg>
                                            Lihat Sertifikat
                                        </span>
                                    </a>
                                </div>
                            @endif

                            <div class="akr__card-body">
                                <div class="akr__card-top">
                                    <h4 class="akr__card-title">{{ data_get($i, 'jeneng') }}</h4>
                                    @if (data_get($i, 'status_akred'))
                                        <span class="akr__badge {{ $getStatusClass(data_get($i, 'status_akred')) }}">
                                            {{ data_get($i, 'status_akred') }}
                                        </span>
                                    @endif
                                </div>

                                @if (data_get($i, 'katrangan'))
                                    <div class="akr__card-desc prose">
                                        {!! data_get($i, 'katrangan') !!}
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        @if ($prodis->isNotEmpty())
            <div class="akr__section rv" style="margin-top: 48px;">
                <h3 class="akr__section-title">Akreditasi Program Studi</h3>

                <div class="akr__grid-prodi">
                    @foreach ($prodis as $p)
                        <div class="akr__card akr__card--prodi">
                            @if (data_get($p, 'gambar_url'))
                                <div class="akr__card-img">
                                    <a href="{{ data_get($p, 'gambar_url') }}" target="_blank" title="Lihat Sertifikat">
                                        <img src="{{ data_get($p, 'gambar_url') }}"
                                            alt="Sertifikat {{ data_get($p, 'jeneng') }}" loading="lazy">
                                        <span class="akr__img-overlay">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="2">
                                                <circle cx="11" cy="11" r="8" />
                                                <path d="M21 21l-4.3-4.3" />
                                            </svg>
                                            Lihat Sertifikat
                                        </span>
                                    </a>
                                </div>
                            @endif

                            <div class="akr__card-body">
                                <div class="akr__card-top">
                                    <h4 class="akr__card-title">{{ data_get($p, 'jeneng') }}</h4>
                                    @if (data_get($p, 'status_akred'))
                                        <span class="akr__badge {{ $getStatusClass(data_get($p, 'status_akred')) }}">
                                            {{ data_get($p, 'status_akred') }}
                                        </span>
                                    @endif
                                </div>

                                @if (data_get($p, 'katrangan'))
                                    <div class="akr__card-desc prose">
                                        {!! data_get($p, 'katrangan') !!}
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        @if ($inst->isEmpty() && $prodis->isEmpty())
            <div class="akr__empty-all rv">
                <h3>Data akreditasi belum tersedia</h3>
                <p>Informasi akreditasi institusi dan program studi akan tampil setelah diisi melalui panel superadmin.
                </p>
            </div>
        @endif

    </div>
</section>
