<x-dashboard.layout.app title="Sambutan" page-title="Sambutan">
    <div class="pim-page">
        @include('superadmin.konten.profil.pimpinan.partials.flash')

        {{-- Banner Sampah --}}
        @if (!$trashed && ($stats['terhapus'] ?? 0) > 0)
            @include('superadmin.konten.profil.pimpinan.partials.trash-banner', [
                'count' => $stats['terhapus'],
            ])
        @endif

        <div class="pim-layout">
            {{-- Kolom Konten Utama --}}
            <div class="pim-main">
                @include('superadmin.konten.profil.pimpinan.partials.header')
                @include('superadmin.konten.profil.pimpinan.partials.filter')

                <div class="card">
                    <div class="ch2">
                        <h2 style="margin:0;">Daftar Sambutan</h2>
                        <span class="pim-count">{{ $items->total() }} data</span>
                    </div>

                    @if ($trashed)
                        @include('superadmin.konten.profil.pimpinan.partials.trash-table', [
                            'items' => $items,
                        ])
                    @elseif ($items->isEmpty())
                        @include('superadmin.konten.profil.pimpinan.partials.empty')
                    @else
                        @include('superadmin.konten.profil.pimpinan.partials.table', [
                            'items' => $items,
                        ])
                        @include('superadmin.konten.profil.pimpinan.partials.paginate', [
                            'items' => $items,
                        ])
                    @endif
                </div>
            </div>

            {{-- Sidebar Kanan --}}
            @include('superadmin.konten.profil.pimpinan.partials.sidebar', [
                'stats' => $stats ?? null,
            ])
        </div>
    </div>

    @include('superadmin.konten.profil.pimpinan.modal.confir')

    @push('css')
        <link rel="stylesheet" href="{{ asset('admin/landing/profil/pimpinan/pim.css') }}">
    @endpush

    @push('js')
        <script src="{{ asset('admin/landing/profil/pimpinan/pim.js') }}" defer></script>
    @endpush
</x-dashboard.layout.app>
