@props(['program' => [], 'selectedProdi' => null])

@php
    $items = collect($program);
@endphp

<section class="prog">
    <div class="wrap prog__wrap">

        {{-- ========================================== --}}
        {{-- JIKA PRODI DIPILIH (Halaman Detail Prodi) --}}
        {{-- ========================================== --}}
        @if ($selectedProdi)
            <div class="prog__intro rv">
                <div style="margin-bottom: 24px;">
                    <a href="/pendidikan/program-pendidikan"
                        style="color: var(--blue); font-weight: 600; font-size: 0.9rem; display: inline-flex; align-items: center; gap: 6px; text-decoration: none;">
                        &larr; Kembali ke Daftar Program
                    </a>
                </div>

                {{-- Breadcrumb kecil: Fakultas > Prodi --}}
                <p style="margin: 0 0 8px; font-size: 0.85rem; color: var(--mut);">
                    <span>{{ data_get($selectedProdi, 'fakultas_jeneng') }}</span>
                    <span style="margin: 0 6px;">/</span>
                    <span style="color: var(--blue); font-weight: 600;">{{ data_get($selectedProdi, 'jeneng') }}</span>
                </p>

                <p class="prog__eyebrow">Detail Program Studi</p>
                <h2 class="prog__title">{{ data_get($selectedProdi, 'jeneng') }}</h2>

                <div style="display: flex; gap: 12px; margin-bottom: 24px; flex-wrap: wrap;">
                    @if (data_get($selectedProdi, 'undhak'))
                        <span class="prog__jenjang">{{ data_get($selectedProdi, 'undhak') }}</span>
                    @endif
                    @if (data_get($selectedProdi->akreditasi ?? null, 'status_akred'))
                        <span class="prog__akred">Akreditasi
                            {{ data_get($selectedProdi->akreditasi, 'status_akred') }}</span>
                    @endif
                    @if (data_get($selectedProdi, 'fakultas_jeneng'))
                        <span
                            style="padding: 4px 10px; border-radius: 999px; background: var(--sky); color: var(--blue); font-size: 0.72rem; font-weight: 700;">
                            {{ data_get($selectedProdi, 'fakultas_jeneng') }}
                        </span>
                    @endif
                </div>

                <div class="prog__lead" style="max-width: 75ch; font-size: 1.05rem; line-height: 1.8;">
                    @if (data_get($selectedProdi, 'katrangan'))
                        {!! nl2br(e(data_get($selectedProdi, 'katrangan'))) !!}
                    @else
                        <p>Informasi lebih lanjut mengenai program studi ini belum tersedia. Silakan hubungi bagian
                            akademik untuk detail kurikulum dan pendaftaran.</p>
                    @endif
                </div>
            </div>

            {{-- ========================================== --}}
            {{-- JIKA TIDAK ADA PRODI DIPILIH (Daftar Fakultas) --}}
            {{-- ========================================== --}}
        @else
            <div class="prog__intro rv">
                <p class="prog__eyebrow">Program Pendidikan</p>
                <h2 class="prog__title">Fakultas & program studi</h2>
                <p class="prog__lead">
                    Pilih rumpun ilmu dan program studi yang sesuai minat. Setiap program menampilkan jenjang
                    dan status akreditasi bila tersedia.
                </p>
            </div>

            @forelse ($items as $fak)
                @php
                    $prodis = collect(data_get($fak, 'activeProdis', data_get($fak, 'prodis', [])));
                @endphp

                <section class="prog__fak rv">
                    <header class="prog__fak-head">
                        <div>
                            @if (data_get($fak, 'kodhe'))
                                <span class="prog__code">{{ data_get($fak, 'kodhe') }}</span>
                            @endif
                            <h3 class="prog__fak-name">{{ data_get($fak, 'jeneng') }}</h3>
                            @if (data_get($fak, 'katrangan'))
                                <p class="prog__fak-desc">{{ data_get($fak, 'katrangan') }}</p>
                            @endif
                        </div>
                        <span class="prog__count">{{ $prodis->count() }} Prodi</span>
                    </header>

                    @if ($prodis->isNotEmpty())
                        <div class="prog__grid">
                            @foreach ($prodis as $prodi)
                                @php
                                    $status = data_get($prodi, 'akreditasi.status_akred');
                                    $slug = data_get($prodi, 'tandha') ?? data_get($prodi, 'id');
                                @endphp
                                <article class="prog__card" id="{{ $slug }}">
                                    <div class="prog__card-top">
                                        @if (data_get($prodi, 'undhak'))
                                            <span class="prog__jenjang">{{ data_get($prodi, 'undhak') }}</span>
                                        @endif
                                        @if ($status)
                                            <span class="prog__akred">Akreditasi {{ $status }}</span>
                                        @endif
                                    </div>
                                    <h4 class="prog__prodi">{{ data_get($prodi, 'jeneng') }}</h4>
                                    @if (data_get($prodi, 'katrangan'))
                                        <p class="prog__prodi-desc">{{ data_get($prodi, 'katrangan') }}</p>
                                    @endif

                                    <a href="/pendidikan/program-pendidikan/{{ $slug }}"
                                        style="margin-top: 16px; font-size: 0.9rem; font-weight: 700; color: var(--blue); text-decoration: none; display: inline-flex; align-items: center; gap: 4px;">
                                        Lihat Detail <span aria-hidden="true">&rarr;</span>
                                    </a>
                                </article>
                            @endforeach
                        </div>
                    @else
                        <p class="prog__empty">Belum ada program studi aktif pada fakultas ini.</p>
                    @endif
                </section>
            @empty
                <div class="prog__empty-all rv">
                    <h3>Data program belum tersedia</h3>
                    <p>Daftar fakultas dan program studi akan tampil setelah diisi melalui panel superadmin.</p>
                </div>
            @endforelse
        @endif

    </div>
</section>
