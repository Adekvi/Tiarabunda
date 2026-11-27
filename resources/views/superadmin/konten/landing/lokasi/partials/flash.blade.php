@if (session('success'))
    <div class="lok-alert lok-alert--ok" role="status">
        <span>{{ session('success') }}</span>
        <button type="button" class="lok-alert-close" onclick="this.parentElement.remove()"
            aria-label="Tutup">&times;</button>
    </div>
@endif

@if (session('error'))
    <div class="lok-alert lok-alert--err" role="alert">
        <span>{{ session('error') }}</span>
        <button type="button" class="lok-alert-close" onclick="this.parentElement.remove()"
            aria-label="Tutup">&times;</button>
    </div>
@endif
