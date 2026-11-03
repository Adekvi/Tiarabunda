@props([
    'items' => [],
])

@php
    // Fallback ke config hanya jika service belum kirim / kosong total
    if ($items instanceof \Illuminate\Support\Collection) {
        $items = $items->all();
    }
    if ($items === [] || $items === null) {
        $items = config('landing.home.faq', []);
    }
@endphp

@if (count($items))
    <section class="sec" id="faq">
        <div class="wrap faq-in">
            <x-landing.ui.head kick="FAQ" title="Pertanyaan yang sering diajukan" />
            <div class="faq rv">
                @foreach ($items as $i => $q)
                    <div class="qa">
                        <button type="button" aria-expanded="false" aria-controls="qa{{ $i }}">
                            {{ is_array($q) ? $q[0] ?? '' : $q['pitakon'] ?? '' }}
                            <i></i>
                        </button>
                        <div class="qa-b" id="qa{{ $i }}">
                            <p>{{ is_array($q) ? $q[1] ?? '' : $q['wangsulan'] ?? '' }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endif
