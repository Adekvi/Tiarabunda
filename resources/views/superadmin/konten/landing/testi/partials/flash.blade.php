@if (session('success'))
    <div class="lok-alert lok-alert--ok" role="status">
        <div>{{ session('success') }}</div>
    </div>
@endif

@if (session('error'))
    <div class="lok-alert lok-alert--err" role="alert">
        <div>{{ session('error') }}</div>
    </div>
@endif
