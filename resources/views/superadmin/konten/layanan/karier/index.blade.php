<x-dashboard.layout.app title="Pusat Karier" page-title="Pusat Karier">
    <div class="krr-page">

        @include('superadmin.konten.layanan.karier.partials.flash')

        {{-- Banner Sampah: hanya muncul di halaman index & ada data terhapus --}}
        @if (!($trashed ?? false) && ($stats['terhapus'] ?? 0) > 0)
            @include('superadmin.konten.layanan.karier.partials.trash-banner', [
                'count' => $stats['terhapus'],
            ])
        @endif

        <div class="krr-layout">
            {{-- Kolom Konten Utama --}}
            <div class="krr-main">
                @include('superadmin.konten.layanan.karier.partials.header')
                @include('superadmin.konten.layanan.karier.partials.filter')

                <div class="card">
                    <div class="ch2">
                        <h2 style="margin:0;">{{ $trashed ?? false ? 'Data terhapus' : 'Daftar data' }}</h2>
                        <span class="krr-count">{{ $items->total() }} data</span>
                    </div>

                    @if ($items->isEmpty())
                        @include('superadmin.konten.layanan.karier.partials.empty')
                    @else
                        @if ($trashed ?? false)
                            @include('superadmin.konten.layanan.karier.partials.trash-table')
                        @else
                            @include('superadmin.konten.layanan.karier.partials.table')
                        @endif

                        @include('superadmin.konten.layanan.karier.partials.paginate')
                    @endif
                </div>
            </div>

            {{-- Kolom Kanan --}}
            <aside class="krr-aside">
                @include('superadmin.konten.layanan.karier.partials.sidebar')
            </aside>
        </div>
    </div>

    @include('superadmin.konten.layanan.karier.modal.confir')

    @push('css')
        <link rel="stylesheet" href="{{ asset('admin/landing/layanan/karir/krr.css') }}">
    @endpush

    @push('js')
        <script src="{{ asset('admin/landing/layanan/karir/krr.js') }}" defer></script>
    @endpush
</x-dashboard.layout.app>
