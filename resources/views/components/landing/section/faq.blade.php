@props(['items' => config('landing.home.faq')])
<section class="sec" id="faq"><div class="wrap faq-in">
  <x-landing.ui.head kick="FAQ" title="Pertanyaan yang sering diajukan" />
  <div class="faq rv">@foreach ($items as $i => $q)<div class="qa"><button type="button" aria-expanded="false" aria-controls="qa{{ $i }}">{{ $q[0] }}<i></i></button><div class="qa-b" id="qa{{ $i }}"><p>{{ $q[1] }}</p></div></div>@endforeach</div></div></section>
