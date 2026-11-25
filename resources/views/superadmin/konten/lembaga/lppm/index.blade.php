@php
    $pageLabel = $isTrash ? 'Sampah Program & Layanan LPPM' : 'Program & Layanan LPPM';
@endphp

<x-dashboard.layout.app :title="$pageLabel" :pageTitle="$pageLabel">

    @push('css')
        <link rel="stylesheet" href="{{ asset('admin/landing/lembaga/lppm/lppm.css') }}">
    @endpush

    <div class="lppm-shell">
        <div class="lppm-main">
            @include('superadmin.konten.lembaga.lppm.partials.header')
            @include('superadmin.konten.lembaga.lppm.partials.flash')
            @include('superadmin.konten.lembaga.lppm.partials.trash-banner')

            @unless ($isTrash)
                @include('superadmin.konten.lembaga.lppm.partials.filter')
            @endunless

            <div class="card">
                @if ($items->isEmpty())
                    @include('superadmin.konten.lembaga.lppm.partials.empty')
                @elseif ($isTrash)
                    @include('superadmin.konten.lembaga.lppm.partials.trash-table')
                @else
                    @include('superadmin.konten.lembaga.lppm.partials.table')
                @endif

                @include('superadmin.konten.lembaga.lppm.partials.paginate')
            </div>
        </div>

        @include('superadmin.konten.lembaga.lppm.partials.sidebar')
    </div>

    @include('superadmin.konten.lembaga.lppm.modal.confir')
</x-dashboard.layout.app>
