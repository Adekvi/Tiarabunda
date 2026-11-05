<x-dashboard.layout.app title="Fasilitas" page-title="Fasilitas">
    <div class="g">
        @if (session('success'))
            <div class="al" role="status">{{ session('success') }}</div>
        @endif

        @if (session('error'))
            <div class="al al--err" role="alert">{{ session('error') }}</div>
        @endif

        <div class="g fasilitas-layout">

            {{-- ===== KONTEN UTAMA (KIRI) ===== --}}
            <div class="fasilitas-main">
                @include('superadmin.konten.layanan.fasilitas.partials.header', [
                    'trashed' => $trashed,
                ])

                <div class="card nw-list">
                    @include('superadmin.konten.layanan.fasilitas.partials.toolbar', [
                        'items' => $items,
                        'trashed' => $trashed,
                        'filtered' => $filtered,
                    ])

                    <div class="nw-list__body">
                        @if ($trashed)
                            @include('superadmin.konten.layanan.fasilitas.partials.trash-table', [
                                'items' => $items,
                            ])
                        @else
                            @include('superadmin.konten.layanan.fasilitas.partials.table', [
                                'items' => $items,
                            ])
                        @endif
                    </div>

                    @include('superadmin.konten.layanan.fasilitas.partials.paginate', [
                        'items' => $items,
                    ])
                </div>
            </div>

            {{-- ===== SIDEBAR (KANAN) ===== --}}
            @include('superadmin.konten.layanan.fasilitas.partials.sidebar', [
                'stats' => $stats,
                'trashed' => $trashed,
            ])
        </div>
    </div>

    @include('superadmin.konten.layanan.fasilitas.modal.confir-modal')

    @push('css')
        <link rel="stylesheet" href="{{ asset('admin/landing/layanan/fasilitas/fas.css') }}">
    @endpush

    @push('js')
        <script src="{{ asset('admin/landing/layanan/fasilitas/modal.js') }}" defer></script>
    @endpush
</x-dashboard.layout.app>
