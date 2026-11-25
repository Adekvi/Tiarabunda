@props(['item'])

@php
    $isRouteOn = function (array $entry): bool {
        $pattern = $entry['active'] ?? ($entry['route'] ?? null);
        if (!$pattern) {
            return false;
        }

        if (!request()->routeIs($pattern)) {
            return false;
        }

        foreach ($entry['params'] ?? [] as $key => $value) {
            if ((string) request()->route($key) !== (string) $value) {
                return false;
            }
        }

        return true;
    };

    $icons = [
        'home' =>
            '<path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/>',
        'layout' =>
            '<rect x="3" y="3" width="18" height="18" rx="2"/><line x1="3" y1="9" x2="21" y2="9"/><line x1="9" y1="21" x2="9" y2="9"/>',
        'bldg' =>
            '<rect x="4" y="2" width="16" height="20" rx="2"/><path d="M9 22v-4h6v4"/><path d="M8 6h.01M16 6h.01M12 6h.01M12 10h.01M12 14h.01M16 10h.01M16 14h.01M8 10h.01M8 14h.01"/>',
        'book' =>
            '<path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/>',
        'box' =>
            '<path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/><polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/>',
        'info' =>
            '<circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/>',
        'users' =>
            '<path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>',
        'img' =>
            '<rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/>',
        'cal' =>
            '<rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>',
    ];
@endphp

@if (($item['type'] ?? '') === 'sep')
    <div class="sep" role="presentation">
        <span class="sep-label">{{ $item['label'] }}</span>
        <span class="sep-line" aria-hidden="true"></span>
    </div>
@elseif (($item['type'] ?? '') === 'link')
    @php
        $url = route($item['route'], $item['params'] ?? []);
        $on = $isRouteOn($item);
    @endphp
    <a class="ni{{ $on ? ' on' : '' }}" href="{{ $url }}">
        @if (!empty($item['icon']) && isset($icons[$item['icon']]))
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                stroke-linecap="round" stroke-linejoin="round" style="flex-shrink: 0;" aria-hidden="true">
                {!! $icons[$item['icon']] !!}
            </svg>
        @endif
        <span>{{ $item['label'] }}</span>
    </a>
@elseif (($item['type'] ?? '') === 'group')
    @php
        $children = $item['children'] ?? [];
        $childOn = collect($children)->contains(fn(array $c) => $isRouteOn($c));
    @endphp
    <div class="grp{{ $childOn ? ' open' : '' }}" data-nav-group>
        <button type="button" class="ni{{ $childOn ? ' on' : '' }}" data-nav-toggle
            aria-expanded="{{ $childOn ? 'true' : 'false' }}">
            @if (!empty($item['icon']) && isset($icons[$item['icon']]))
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink: 0;"
                    aria-hidden="true">
                    {!! $icons[$item['icon']] !!}
                </svg>
            @endif
            <span>{{ $item['label'] }}</span>
            <svg class="ch" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                stroke-width="2" stroke-linecap="round" aria-hidden="true">
                <path d="M6 9l6 6 6-6" />
            </svg>
        </button>
        <div class="sub">
            <div>
                @foreach ($children as $child)
                    @php
                        $url = route($child['route'], $child['params'] ?? []);
                        $isOn = $isRouteOn($child);
                    @endphp
                    <a class="ni{{ $isOn ? ' on' : '' }}" href="{{ $url }}">
                        {{ $child['label'] }}
                    </a>
                @endforeach
            </div>
        </div>
    </div>
@endif
