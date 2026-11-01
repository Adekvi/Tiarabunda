@props(['item'])

@if (($item['type'] ?? '') === 'sep')
    <div class="sep" role="presentation">
        <span class="sep-label">{{ $item['label'] }}</span>
        <span class="sep-line" aria-hidden="true"></span>
    </div>
@elseif (($item['type'] ?? '') === 'link')
    @php
        $url = route($item['route'], $item['params'] ?? []);
        $on = request()->routeIs($item['route']);
    @endphp
    <a class="ni{{ $on ? ' on' : '' }}" href="{{ $url }}">
        <span>{{ $item['label'] }}</span>
    </a>
@elseif (($item['type'] ?? '') === 'group')
    @php
        $childOn = collect($item['children'] ?? [])->contains(fn($c) => request()->routeIs($c['route']));
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
                @foreach ($item['children'] ?? [] as $child)
                    @php
                        $url = route($child['route'], $child['params'] ?? []);
                        $isOn =
                            request()->routeIs($child['route']) &&
                            (!isset($child['params']['slug']) || request()->route('slug') === $child['params']['slug']);
                    @endphp
                    <a class="ni{{ $isOn ? ' on' : '' }}" href="{{ $url }}">
                        {{ $child['label'] }}
                    </a>
                @endforeach
            </div>
        </div>
    </div>
@endif
