<x-dashboard.layout.app title="Info Terkini" page-title="Info Terkini">
    <div class="info-page">

        @include('superadmin.konten.landing.info.partials.flash')

        {{-- Banner Sampah: hanya muncul di halaman index & ada data terhapus --}}
        @if (!($trashed ?? false) && ($stats['terhapus'] ?? 0) > 0)
            @include('superadmin.konten.landing.info.partials.trash-banner', [
                'count' => $stats['terhapus'],
            ])
        @endif

        <div class="info-layout">
            {{-- Kolom Konten Utama --}}
            <div class="info-main">
                @include('superadmin.konten.landing.info.partials.header')
                @include('superadmin.konten.landing.info.partials.filter')

                <div class="card">
                    <div class="ch2">
                        <h2 style="margin:0;">{{ $trashed ?? false ? 'Data terhapus' : 'Daftar data' }}</h2>
                        <span class="info-count">{{ $items->total() }} data</span>
                    </div>

                    @if ($items->isEmpty())
                        @include('superadmin.konten.landing.info.partials.empty')
                    @else
                        @if ($trashed ?? false)
                            @include('superadmin.konten.landing.info.partials.trash-table')
                        @else
                            @include('superadmin.konten.landing.info.partials.table')
                        @endif

                        @include('superadmin.konten.landing.info.partials.paginate')
                    @endif
                </div>
            </div>

            {{-- Kolom Kanan --}}
            <aside class="info-aside">
                @include('superadmin.konten.landing.info.partials.sidebar')
            </aside>
        </div>
    </div>

    @include('superadmin.konten.landing.info.modal.confir')

    @push('css')
        <link rel="stylesheet" href="{{ asset('admin/landing/info/info.css') }}">
    @endpush

    @push('js')
        <script src="{{ asset('admin/landing/info/info.js') }}" defer></script>
    @endpush
</x-dashboard.layout.app>
