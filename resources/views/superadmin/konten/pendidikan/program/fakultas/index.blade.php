<x-dashboard.layout.app title="Fakultas" page-title="Fakultas">
    <div class="g">
        @include('superadmin.konten.pendidikan.program.fakultas.partials.flash')

        @if (!$trashed && ($stats['terhapus'] ?? 0) > 0)
            @include('superadmin.konten.pendidikan.program.fakultas.partials.trash-banner', [
                'count' => $stats['terhapus'],
            ])
        @endif

        <div class="g g2" style="align-items:start;">
            <div class="g" style="min-width:0;">
                @include('superadmin.konten.pendidikan.program.fakultas.partials.header', [
                    'trashed' => $trashed,
                ])

                <div class="card">
                    <div class="ch2">
                        <h2 style="margin:0;">
                            {{ $trashed ? 'Fakultas di sampah' : 'Daftar fakultas' }}
                        </h2>
                        <span class="faq-count">{{ $items->total() }} data</span>
                    </div>

                    @if ($items->isEmpty())
                        @include('superadmin.konten.pendidikan.program.fakultas.partials.empty', [
                            'trashed' => $trashed,
                            'filtered' => $filtered ?? false,
                        ])
                    @else
                        @if ($trashed)
                            @include('superadmin.konten.pendidikan.program.fakultas.partials.trash-table', [
                                'count' => $stats['terhapus'],
                                'items' => $items,
                            ])
                        @else
                            @include('superadmin.konten.pendidikan.program.fakultas.partials.table', [
                                'items' => $items,
                            ])
                        @endif

                        @include('superadmin.konten.pendidikan.program.fakultas.partials.paginate', [
                            'items' => $items,
                        ])
                    @endif
                </div>
            </div>

            @include('superadmin.konten.pendidikan.program.fakultas.partials.sidebar', [
                'stats' => $stats,
                'trashed' => $trashed,
            ])
        </div>
    </div>

    {{-- Modal konfirmasi hapus / pulihkan / force-delete --}}
    @include('superadmin.konten.pendidikan.program.fakultas.modal.confir')

    @push('css')
        <link rel="stylesheet" href="{{ asset('admin/landing/pendidikan/fakultas/fak.css') }}">
    @endpush

    @push('js')
        <script src="{{ asset('admin/landing/pendidikan/fakultas/fak.js') }}" defer></script>
    @endpush
</x-dashboard.layout.app>
