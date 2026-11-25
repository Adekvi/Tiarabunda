@php
    $pageLabel = $isTrash ? 'Sampah Dokumen & Program LPM' : 'Lembaga Penjaminan Mutu (LPM)';
@endphp

<x-dashboard.layout.app :title="$pageLabel" :pageTitle="$pageLabel">
    <div class="lpm-shell">
        <div class="lpm-main">
            @include('superadmin.konten.lembaga.lpm.partials.header')
            @include('superadmin.konten.lembaga.lpm.partials.flash')
            @include('superadmin.konten.lembaga.lpm.partials.trash-banner')

            @unless ($isTrash)
                @include('superadmin.konten.lembaga.lpm.partials.filter')
            @endunless

            <div class="card">
                @if ($items->isEmpty())
                    @include('superadmin.konten.lembaga.lpm.partials.empty')
                @elseif ($isTrash)
                    @include('superadmin.konten.lembaga.lpm.partials.trash-table')
                @else
                    @include('superadmin.konten.lembaga.lpm.partials.table')
                @endif

                @include('superadmin.konten.lembaga.lpm.partials.paginate')
            </div>
        </div>

        @include('superadmin.konten.lembaga.lpm.partials.sidebar')
    </div>

    @include('superadmin.konten.lembaga.lpm.modal.confir')
</x-dashboard.layout.app>