<x-dashboard.layout.app title="Program Studi" page-title="Program Studi">
    <div class="prodi-page">
        @include('superadmin.konten.pendidikan.program.prodi.partials.flash')

        @if (!$trashed && ($stats['terhapus'] ?? 0) > 0)
            @include('superadmin.konten.pendidikan.program.prodi.partials.trash-banner', [
                'count' => $stats['terhapus'],
            ])
        @endif

        <div class="prodi-layout">
            <div class="prodi-main">
                @include('superadmin.konten.pendidikan.program.prodi.partials.header', [
                    'fakultasList' => $fakultasList,
                    'trashed' => $trashed,
                ])

                <div class="card">
                    <div class="ch2">
                        <h2 style="margin:0;">
                            {{ $trashed ? 'Prodi di sampah' : 'Daftar program studi' }}
                        </h2>
                        <span class="faq-count">{{ $items->total() }} data</span>
                    </div>

                    @if ($trashed)
                        @include('superadmin.konten.pendidikan.program.prodi.partials.trash-table', [
                            'items' => $items,
                        ])
                        {{-- trash-table sudah handle empty + toolbar + table --}}
                    @elseif ($items->isEmpty())
                        @include('superadmin.konten.pendidikan.program.prodi.partials.empty', [
                            'filtered' => $filtered ?? false,
                        ])
                    @else
                        @include('superadmin.konten.pendidikan.program.prodi.partials.table', [
                            'items' => $items,
                        ])
                    @endif

                    @if (!$items->isEmpty())
                        @include('superadmin.konten.pendidikan.program.prodi.partials.paginate', [
                            'items' => $items,
                        ])
                    @endif
                </div>
            </div>

            @include('superadmin.konten.pendidikan.program.prodi.partials.sidebar', [
                'items' => $items,
                'stats' => $stats ?? null,
            ])
        </div>
    </div>

    @include('superadmin.konten.pendidikan.program.prodi.modal.confir')

    @push('css')
        <link rel="stylesheet" href="{{ asset('admin/landing/pendidikan/prodi/prodi.css') }}">
    @endpush

    @push('js')
        <script src="{{ asset('admin/landing/pendidikan/prodi/prodi.js') }}" defer></script>
    @endpush
</x-dashboard.layout.app>
