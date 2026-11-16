<x-dashboard.layout.app title="Visi & Misi" page-title="Visi & Misi">
    <div class="vis-page">
        @include('superadmin.konten.profil.visimisi.partials.flash')

        @if (!$trashed && ($stats['terhapus'] ?? 0) > 0)
            @include('superadmin.konten.profil.visimisi.partials.trash-banner', [
                'count' => $stats['terhapus'],
            ])
        @endif

        <div class="vis-layout">
            <div class="vis-main">
                @include('superadmin.konten.profil.visimisi.partials.header')
                @include('superadmin.konten.profil.visimisi.partials.filter')

                <div class="card">
                    <div class="ch2">
                        <h2 style="margin:0;">
                            {{ $trashed ? 'Sampah Visi & Misi' : 'Daftar Visi & Misi' }}
                        </h2>
                        <span class="vis-count">{{ $items->total() }} data</span>
                    </div>

                    @if ($trashed)
                        @include('superadmin.konten.profil.visimisi.partials.trash-table', [
                            'items' => $items,
                        ])
                    @elseif ($items->isEmpty())
                        @include('superadmin.konten.profil.visimisi.partials.empty')
                    @else
                        @include('superadmin.konten.profil.visimisi.partials.table', ['items' => $items])
                        @include('superadmin.konten.profil.visimisi.partials.paginate', [
                            'items' => $items,
                        ])
                    @endif
                </div>
            </div>

            @include('superadmin.konten.profil.visimisi.partials.sidebar', [
                'items' => $items,
                'stats' => $stats ?? null,
                'trashed' => $trashed,
            ])
        </div>
    </div>

    @include('superadmin.konten.profil.visimisi.modal.confir')

    @push('css')
        <link rel="stylesheet" href="{{ asset('admin/landing/profil/visi/vis.css') }}">
    @endpush

    @push('js')
        <script src="{{ asset('admin/landing/profil/visi/vis.js') }}" defer></script>
    @endpush
</x-dashboard.layout.app>
