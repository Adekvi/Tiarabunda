@props(['user' => null])

<aside class="side" id="sidebar">
    <a class="brand" href="{{ route('superadmin.dashboard') }}">
        <img src="{{ asset('landing/assets/img/logo.webp') }}" alt="" width="36" height="36">
        <span>
            {{ config('app.name') }}
            <small>{{ config('dashboard.brand_subtitle', 'Panel Superadmin') }}</small>
        </span>
    </a>

    <nav class="nav" aria-label="Navigasi utama">
        @foreach (config('dashboard.nav', []) as $item)
            <x-dashboard.include.nav-item :item="$item" />
        @endforeach
    </nav>

    <div class="me">
        <span class="av">{{ strtoupper(substr($user->name ?? 'SA', 0, 2)) }}</span>
        <span>
            <b>{{ $user->name ?? 'Super Admin' }}</b>
            <small>{{ $user->email ?? 'admin@tiarabunda.ac.id' }}</small>
        </span>
    </div>
</aside>
