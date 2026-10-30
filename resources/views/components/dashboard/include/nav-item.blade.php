@props(['item'])

@if (($item['type'] ?? '') === 'sep')
    <p class="sep">{{ $item['label'] }}</p>
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
        </button>
        <div class="sub">
            <div>
                @foreach ($item['children'] ?? [] as $child)
                    @php $url = route($child['route'], $child['params'] ?? []); @endphp
                    <a class="ni{{ request()->routeIs($child['route']) && request()->route('slug') === ($child['params']['slug'] ?? null) ? ' on' : '' }}"
                        href="{{ $url }}">{{ $child['label'] }}</a>
                @endforeach
            </div>
        </div>
    </div>
@endif
