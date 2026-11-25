@php
    $pageLabel = $isTrash ? 'Sampah Layanan Akademik' : 'Layanan Akademik';
@endphp

<x-dashboard.layout.app :title="$pageLabel" :pageTitle="$pageLabel">
    @push('css')
        <link rel="stylesheet" href="{{ asset('admin/landing/layanan/akademik/aka.css') }}">
    @endpush

    <div class="aka-shell">
        <div class="aka-main">
            @include('superadmin.konten.layanan.akademik.partials.header')
            @include('superadmin.konten.layanan.akademik.partials.flash')
            @include('superadmin.konten.layanan.akademik.partials.trash-banner')

            @unless ($isTrash)
                @include('superadmin.konten.layanan.akademik.partials.filter')
            @endunless

            <div class="card">
                @if ($items->isEmpty())
                    @include('superadmin.konten.layanan.akademik.partials.empty')
                @elseif ($isTrash)
                    @include('superadmin.konten.layanan.akademik.partials.trash-table')
                @else
                    @include('superadmin.konten.layanan.akademik.partials.table')
                @endif

                @include('superadmin.konten.layanan.akademik.partials.paginate')
            </div>
        </div>

        @include('superadmin.konten.layanan.akademik.partials.sidebar')
    </div>

    @include('superadmin.konten.layanan.akademik.modal.confir')
</x-dashboard.layout.app>
