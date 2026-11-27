@props(['items' => null])

@php
    if ($items instanceof \Illuminate\Support\Collection) {
        $items = $items->all();
    }

    $title = $isTrashed ? 'Data Sampah Lokasi' : 'Lokasi Kampus';
    $pageTitle = $isTrashed ? 'Data Sampah — Lokasi Kampus' : 'Lokasi & Lingkungan Kampus';
@endphp

<x-dashboard.layout.app :title="$title" :page-title="$pageTitle">
    @include('superadmin.konten.landing.lokasi.partials.header')
    @include('superadmin.konten.landing.lokasi.partials.flash')

    @if ($isTrashed)
        @include('superadmin.konten.landing.lokasi.partials.trash-banner')
    @endif

    <div class="lok-grid">
        <div class="lok-main">
            @include('superadmin.konten.landing.lokasi.partials.filter')

            <div class="card lok-table-card">
                @if ($items->isEmpty())
                    @include('superadmin.konten.landing.lokasi.partials.empty')
                @elseif ($isTrashed)
                    @include('superadmin.konten.landing.lokasi.partials.trash-table')
                @else
                    @include('superadmin.konten.landing.lokasi.partials.table')
                @endif

                @include('superadmin.konten.landing.lokasi.partials.paginate')
            </div>
        </div>

        <aside class="lok-side">
            @include('superadmin.konten.landing.lokasi.partials.sidebar')
        </aside>
    </div>

    @include('superadmin.konten.landing.lokasi.modal.confir')

</x-dashboard.layout.app>
