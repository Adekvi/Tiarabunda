@php
    $pageLabel = $isTrash ? 'Sampah Layanan Kerjasama' : 'Layanan Kerjasama';
@endphp

<x-dashboard.layout.app :title="$pageLabel" :pageTitle="$pageLabel">

    @push('css')
        <link rel="stylesheet" href="{{ asset('admin/landing/layanan/kerjasama/krj.css') }}">
    @endpush

    <div class="kjs-shell">
        <div class="kjs-main">
            @include('superadmin.konten.layanan.kerjasama.partials.header')
            @include('superadmin.konten.layanan.kerjasama.partials.flash')
            @include('superadmin.konten.layanan.kerjasama.partials.trash-banner')

            @unless ($isTrash)
                @include('superadmin.konten.layanan.kerjasama.partials.filter')
            @endunless

            <div class="card">
                @if ($items->isEmpty())
                    @include('superadmin.konten.layanan.kerjasama.partials.empty')
                @elseif ($isTrash)
                    @include('superadmin.konten.layanan.kerjasama.partials.trash-table')
                @else
                    @include('superadmin.konten.layanan.kerjasama.partials.table')
                @endif

                @include('superadmin.konten.layanan.kerjasama.partials.paginate')
            </div>
        </div>

        @include('superadmin.konten.layanan.kerjasama.partials.sidebar')
    </div>

    @include('superadmin.konten.layanan.kerjasama.modal.confir')
</x-dashboard.layout.app>
