@props(['alasan' => config('landing.home.alasan')])
<section class="sec alt" id="alasan"><div class="wrap">
  <x-landing.ui.head kick="Mengapa memilih kami" title="Belajar dengan arah yang jelas" />
  <div class="why rv"><div class="why-l" role="tablist">@foreach ($alasan as $i => $x)<button role="tab" class="{{ $i ? '' : 'on' }}" data-why="{{ $i }}" aria-selected="{{ $i ? 'false' : 'true' }}">{{ $x['t'] }}</button>@endforeach</div>
  <div class="why-r">@foreach ($alasan as $i => $x)<div class="fade" data-panel="{{ $i }}" @if ($i) hidden @endif><h3>{{ $x['t'] }}</h3><p>{{ $x['d'] }}</p><ul>@foreach ($x['p'] as $p)<li>{{ $p }}</li>@endforeach</ul></div>@endforeach</div></div></div></section>
