@props(['kick', 'title', 'lead' => null])
<div class="head rv">
    <p class="kick">{{ $kick }}</p>
    <h2>{{ $title }}</h2>
    @if ($lead)
        <p class="lead">{{ $lead }}</p>
    @endif
</div>
