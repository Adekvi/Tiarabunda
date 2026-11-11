<x-dashboard.layout.app title="Perpustakaan" page-title="Perpustakaan">
    <div class="prp-page">
        @include('superadmin.konten.layanan.perpus.partials.flash')

        {{-- Banner Sampah: hanya muncul jika tidak sedang di halaman sampah & ada data terhapus --}}
        @if (!$trashed && ($stats['terhapus'] ?? 0) > 0)
            @include('superadmin.konten.layanan.perpus.partials.trash-banner', [
                'count' => $stats['terhapus'],
            ])
        @endif

        <div class="prp-layout">
            {{-- Kolom Konten Utama --}}
            <div class="prp-main">
                @include('superadmin.konten.layanan.perpus.partials.header')
                @include('superadmin.konten.layanan.perpus.partials.filter')

                <div class="card">
                    <div class="ch2">
                        <h2 style="margin:0;">Daftar Konten</h2>
                        <span class="prp-count">{{ $items->total() }} data</span>
                    </div>
                    @if ($trashed)
                        @include('superadmin.konten.layanan.perpus.partials.trash-table', [
                            'items' => $items,
                        ])
                    @elseif ($items->isEmpty())
                        @include('superadmin.konten.layanan.perpus.partials.empty')
                    @else
                        @include('superadmin.konten.layanan.perpus.partials.table', ['items' => $items])
                        @include('superadmin.konten.layanan.perpus.partials.paginate', ['items' => $items])
                    @endif
                </div>
            </div>

            {{-- Sidebar Kanan --}}
            @include('superadmin.konten.layanan.perpus.partials.sidebar', [
                'stats' => $stats ?? null,
            ])
        </div>
    </div>

    @include('superadmin.konten.layanan.perpus.modal.confir')

    @push('css')
        <link rel="stylesheet" href="{{ asset('admin/landing/layanan/perpus/prp.css') }}">
    @endpush

    @push('js')
        <script src="{{ asset('admin/landing/layanan/perpus/prp.js') }}" defer></script>
    @endpush
</x-dashboard.layout.app>
