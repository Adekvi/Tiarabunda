<x-dashboard.layout.app title="Sejarah" page-title="Sejarah Kampus">
    <div class="sej-page">
        @include('superadmin.konten.profil.sejarah.partials.flash')

        {{-- Banner Sampah --}}
        @if (!$trashed && ($stats['terhapus'] ?? 0) > 0)
            @include('superadmin.konten.profil.sejarah.partials.trash-banner', [
                'count' => $stats['terhapus'],
            ])
        @endif

        <div class="sej-layout">
            <div class="sej-main">
                @include('superadmin.konten.profil.sejarah.partials.header')
                @include('superadmin.konten.profil.sejarah.partials.filter')

                <div class="card">
                    <div class="ch2">
                        <h2 style="margin:0;">
                            {{ $trashed ? 'Sampah Sejarah' : 'Daftar Konten Sejarah' }}
                        </h2>
                        <span class="sej-count">{{ $items->total() }} data</span>
                    </div>

                    @if ($trashed)
                        @include('superadmin.konten.profil.sejarah.partials.trash-table', [
                            'items' => $items,
                        ])
                    @elseif ($items->isEmpty())
                        @include('superadmin.konten.profil.sejarah.partials.empty')
                    @else
                        @include('superadmin.konten.profil.sejarah.partials.table', ['items' => $items])
                        @include('superadmin.konten.profil.sejarah.partials.paginate', ['items' => $items])
                    @endif
                </div>
            </div>

            @include('superadmin.konten.profil.sejarah.partials.sidebar', [
                'items' => $items,
                'stats' => $stats ?? null,
            ])
        </div>
    </div>

    @include('superadmin.konten.profil.sejarah.modal.confir')

    @push('css')
        <link rel="stylesheet" href="{{ asset('admin/landing/profil/sejarah/sej.css') }}">
    @endpush

    @push('js')
        <script src="{{ asset('admin/landing/profil/sejarah/sej.js') }}" defer></script>
    @endpush
</x-dashboard.layout.app>
