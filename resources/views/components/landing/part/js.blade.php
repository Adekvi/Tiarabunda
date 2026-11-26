@php
    $siteJsPath = public_path('landing/assets/js/site.js');
    $partialFiles = glob(public_path('landing/assets/js/partials/*.js')) ?: [];
    sort($partialFiles);
@endphp

<script src="{{ asset('landing/assets/js/site.js') }}?v={{ is_file($siteJsPath) ? filemtime($siteJsPath) : '1' }}"
    defer></script>

@foreach ($partialFiles as $partialPath)
    @php
        $partialName = basename($partialPath);
    @endphp
    <script src="{{ asset('landing/assets/js/partials/' . $partialName) }}?v={{ filemtime($partialPath) }}" defer></script>
@endforeach

@if (is_file(public_path('landing/menu/profil/sejarah/sej.js')))
    <script src="{{ asset('landing/menu/profil/sejarah/sej.js') }}" defer></script>
@endif
@if (is_file(public_path('landing/menu/informasi/faq/faq.js')))
    <script src="{{ asset('landing/menu/informasi/faq/faq.js') }}" defer></script>
@endif
