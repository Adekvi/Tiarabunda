<x-dashboard.layout.app title="Akreditasi" page-title="Akreditasi">
    <div class="akr-page">
        @include('superadmin.konten.pendidikan.akreditasi.partials.flash')

        {{-- Banner Sampah --}}
        @if (!$trashed && ($stats['terhapus'] ?? 0) > 0)
            @include('superadmin.konten.pendidikan.akreditasi.partials.trash-banner', [
                'count' => $stats['terhapus'],
            ])
        @endif

        <div class="akr-layout">
            {{-- Kolom Konten Utama --}}
            <div class="akr-main">
                @include('superadmin.konten.pendidikan.akreditasi.partials.header')
                @include('superadmin.konten.pendidikan.akreditasi.partials.filter')

                <div class="card">
                    <div class="ch2">
                        <h2 style="margin:0;">Daftar Akreditasi</h2>
                        <span class="akr-count">{{ $items->total() }} data</span>
                    </div>

                    @if ($trashed)
                        @include('superadmin.konten.pendidikan.akreditasi.partials.trash-table', [
                            'items' => $items,
                        ])
                    @elseif ($items->isEmpty())
                        @include('superadmin.konten.pendidikan.akreditasi.partials.empty')
                    @else
                        @include('superadmin.konten.pendidikan.akreditasi.partials.table', [
                            'items' => $items,
                        ])
                        @include('superadmin.konten.pendidikan.akreditasi.partials.paginate', [
                            'items' => $items,
                        ])
                    @endif
                </div>
            </div>

            {{-- Sidebar Kanan --}}
            @include('superadmin.konten.pendidikan.akreditasi.partials.sidebar', [
                'stats' => $stats ?? null,
            ])
        </div>
    </div>

    @include('superadmin.konten.pendidikan.akreditasi.modal.confir')

    @push('css')
        <link rel="stylesheet" href="{{ asset('admin/landing/pendidikan/akreditasi/akr.css') }}">
    @endpush

    @push('js')
        <script src="{{ asset('admin/landing/pendidikan/akreditasi/akr.js') }}" defer></script>
    @endpush
</x-dashboard.layout.app>
