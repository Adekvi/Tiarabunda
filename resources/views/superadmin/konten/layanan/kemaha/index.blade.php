<x-dashboard.layout.app title="Kemahasiswaan" page-title="Kemahasiswaan">
    <div class="kmh-page">

        @include('superadmin.konten.layanan.kemaha.partials.flash')

        {{-- Banner Sampah: hanya muncul jika tidak sedang di halaman sampah & ada data terhapus --}}
        @if (!($trashed ?? false) && ($stats['terhapus'] ?? 0) > 0)
            @include('superadmin.konten.layanan.kemaha.partials.trash-banner', [
                'count' => $stats['terhapus'],
            ])
        @endif

        <div class="kmh-layout">
            {{-- Kolom Konten Utama --}}
            <div class="kmh-main">
                @include('superadmin.konten.layanan.kemaha.partials.header')
                @include('superadmin.konten.layanan.kemaha.partials.filter')

                <div class="card">
                    <div class="ch2">
                        <h2 style="margin:0;">{{ $trashed ?? false ? 'Data terhapus' : 'Daftar Data' }}</h2>
                        <span class="kmh-count">{{ $items->total() }} data</span>
                    </div>

                    @if ($items->isEmpty())
                        @include('superadmin.konten.layanan.kemaha.partials.empty')
                    @else
                        @if ($trashed ?? false)
                            @include('superadmin.konten.layanan.kemaha.partials.trash-table')
                        @else
                            @include('superadmin.konten.layanan.kemaha.partials.table')
                        @endif

                        @include('superadmin.konten.layanan.kemaha.partials.paginate')
                    @endif
                </div>
            </div>

            {{-- KOLOM KANAN --}}
            <aside class="kmh-aside">
                @include('superadmin.konten.layanan.kemaha.partials.sidebar')
            </aside>
        </div>
    </div>

    @include('superadmin.konten.layanan.kemaha.modal.confir')

    @push('css')
        <link rel="stylesheet" href="{{ asset('admin/landing/layanan/kemaha/kmh.css') }}">
    @endpush

    @push('js')
        <script src="{{ asset('admin/landing/layanan/kemaha/kmh.js') }}" defer></script>
    @endpush
</x-dashboard.layout.app>
