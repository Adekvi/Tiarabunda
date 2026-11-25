@if (session('success'))
    <div class="card" role="status"
         style="padding: 12px 16px; border-left: 4px solid #16a34a; color: #15803d; font-size: 13.5px;">
        {{ session('success') }}
    </div>
@endif

@if (session('error'))
    <div class="card" role="alert"
         style="padding: 12px 16px; border-left: 4px solid var(--err); color: var(--err); font-size: 13.5px;">
        {{ session('error') }}
    </div>
@endif