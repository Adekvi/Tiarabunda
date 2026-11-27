<x-dashboard.layout.app title="Kelola Testimoni" page-title="Testimoni Mahasiswa & Alumni">

    @include('superadmin.konten.landing.testi.partials.header')
    @include('superadmin.konten.landing.testi.partials.flash')
    @include('superadmin.konten.landing.testi.partials.kpi')

    <div class="tst-layout tst-layout--right-sidebar">
        {{-- KOLOM UTAMA (KIRI) --}}
        <div class="tst-main">
            @include('superadmin.konten.landing.testi.partials.filter')

            @if ($isTrash)
                @include('superadmin.konten.landing.testi.partials.trash-banner')
            @endif

            <div class="card tst-card">
                @if ($items->count())
                    @if ($isTrash)
                        @include('superadmin.konten.landing.testi.partials.trash-table')
                    @else
                        @include('superadmin.konten.landing.testi.partials.table')
                    @endif

                    @include('superadmin.konten.landing.testi.partials.paginate')
                @else
                    @include('superadmin.konten.landing.testi.partials.empty')
                @endif
            </div>
        </div>

        {{-- SIDEBAR KANAN --}}
        @include('superadmin.konten.landing.testi.partials.sidebar')
    </div>

    @include('superadmin.konten.landing.testi.modal.preview')
    @include('superadmin.konten.landing.testi.modal.confir')

    @push('js')
        <script src="{{ asset('admin/landing/testi/testi.js') }}" defer></script>
    @endpush

</x-dashboard.layout.app>
