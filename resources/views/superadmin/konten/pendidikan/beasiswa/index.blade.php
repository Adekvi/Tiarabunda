<x-dashboard.layout.app title="Beasiswa" page-title="Beasiswa">
    <div class="beasiswa-page">
        @include('superadmin.konten.pendidikan.beasiswa.partials.flash')

        {{-- Banner Sampah: hanya muncul jika tidak sedang di halaman sampah & ada data terhapus --}}
        @if (!$trashed && ($stats['terhapus'] ?? 0) > 0)
            @include('superadmin.konten.pendidikan.beasiswa.partials.trash-banner', [
                'count' => $stats['terhapus'],
            ])
        @endif

        <div class="beasiswa-layout">
            {{-- Kolom Konten Utama --}}
            <div class="beasiswa-main">
                @include('superadmin.konten.pendidikan.beasiswa.partials.header')
                @include('superadmin.konten.pendidikan.beasiswa.partials.filter')

                <div class="card">
                    <div class="ch2">
                        <h2 style="margin:0;">Daftar Beasiswa</h2>
                        <span class="beasiswa-count">{{ $items->total() }} data</span>
                    </div>

                    @if ($trashed)
                        @include('superadmin.konten.pendidikan.beasiswa.partials.trash-table', [
                            'items' => $items,
                        ])
                    @elseif ($items->isEmpty())
                        @include('superadmin.konten.pendidikan.beasiswa.partials.empty')
                    @else
                        @include('superadmin.konten.pendidikan.beasiswa.partials.table', [
                            'items' => $items,
                        ])
                        @include('superadmin.konten.pendidikan.beasiswa.partials.paginate', [
                            'items' => $items,
                        ])
                    @endif
                </div>
            </div>

            {{-- Sidebar Kanan --}}
            @include('superadmin.konten.pendidikan.beasiswa.partials.sidebar', [
                'stats' => $stats ?? null,
            ])
        </div>
    </div>

    @include('superadmin.konten.pendidikan.beasiswa.modal.confir')

    @push('css')
        <link rel="stylesheet" href="{{ asset('admin/landing/pendidikan/beasiswa/beasiswa.css') }}">
    @endpush

    @push('js')
        <script src="{{ asset('admin/landing/pendidikan/beasiswa/beasiswa.js') }}" defer></script>
    @endpush
</x-dashboard.layout.app>
