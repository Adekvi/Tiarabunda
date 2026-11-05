<x-dashboard.layout.app title="PMB" page-title="PMB">
    <div class="pmb-page">
        @include('superadmin.konten.pendidikan.pmb.partials.flash')

        {{-- Banner Sampah: hanya muncul jika tidak sedang di halaman sampah & ada data terhapus --}}
        @if (!$trashed && ($stats['terhapus'] ?? 0) > 0)
            @include('superadmin.konten.pendidikan.pmb.partials.trash-banner', [
                'count' => $stats['terhapus'],
            ])
        @endif

        <div class="pmb-layout">
            <div class="pmb-main">
                @include('superadmin.konten.pendidikan.pmb.partials.header')
                @include('superadmin.konten.pendidikan.pmb.partials.filter')

                <div class="card">
                    <div class="ch2">
                        <h2 style="margin:0;">
                            {{ $trashed ? 'Sampah Jalur PMB' : 'Daftar Jalur PMB' }}
                        </h2>
                        <span class="pmb-count">{{ $items->total() }} data</span>
                    </div>

                    {{-- Logika Percabangan --}}
                    @if ($trashed)
                        @include('superadmin.konten.pendidikan.pmb.partials.trash-table', [
                            'items' => $items,
                        ])
                    @elseif ($items->isEmpty())
                        @include('superadmin.konten.pendidikan.pmb.partials.empty')
                    @else
                        @include('superadmin.konten.pendidikan.pmb.partials.table', ['items' => $items])
                        @include('superadmin.konten.pendidikan.pmb.partials.paginate', ['items' => $items])
                    @endif
                </div>
            </div>

            @include('superadmin.konten.pendidikan.pmb.partials.sidebar', [
                'items' => $items,
                'stats' => $stats ?? null,
            ])
        </div>
    </div>

    @include('superadmin.konten.pendidikan.pmb.modal.confir')

    @push('css')
        <link rel="stylesheet" href="{{ asset('admin/landing/pendidikan/pmb/pmb.css') }}">
    @endpush

    @push('js')
        <script src="{{ asset('admin/landing/pendidikan/pmb/pmb.js') }}" defer></script>
    @endpush
</x-dashboard.layout.app>
