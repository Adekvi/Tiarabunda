@props(['item'])

@php
    /**
     * Cek apakah route saat ini cocok dengan item nav.
     * - "active" => pola wildcard, mis. superadmin.sejarah.*
     * - fallback ke nama route + params (slug, dll.)
     */
    $isRouteOn = function (array $entry): bool {
        $pattern = $entry['active'] ?? ($entry['route'] ?? null);
        if (!$pattern) {
            return false;
        }

        if (!request()->routeIs($pattern)) {
            return false;
        }

        // Jika masih pakai params (mis. konten lama), cocokkan parameter
        foreach ($entry['params'] ?? [] as $key => $value) {
            if ((string) request()->route($key) !== (string) $value) {
                return false;
            }
        }

        return true;
    };
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
