@if (session('success'))
    <div class="al" role="status">{{ session('success') }}</div>
@endif
@if (session('error'))
    <div class="al w" role="alert">{{ session('error') }}</div>
@endif
