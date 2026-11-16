@props(['faqList' => []])

@php
    $items = collect($faqList);
@endphp

<section class="faq">
    <div class="wrap faq__wrap">

        <div class="faq__intro rv">
            <p class="faq__eyebrow">Pusat Bantuan</p>
            <h2 class="faq__title">Pertanyaan yang Sering Diajukan</h2>
            <p class="faq__lead">
                Temukan jawaban cepat untuk pertanyaan umum seputar pendaftaran, akademik, dan layanan kampus.
            </p>
        </div>

        @if ($items->isNotEmpty())
            <div class="faq__list">
                @foreach ($items as $index => $item)
                    <div class="faq__item rv">
                        <button class="faq__trigger" aria-expanded="false" aria-controls="faq-answer-{{ $index }}">
                            <span class="faq__question">{{ data_get($item, 'pitakon') }}</span>
                            <span class="faq__icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24" width="24" height="24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <polyline points="6 9 12 15 18 9"></polyline>
                                </svg>
                            </span>
                        </button>
                        <div class="faq__answer" id="faq-answer-{{ $index }}" hidden>
                            <div class="faq__answer-inner prose">
                                {!! nl2br(data_get($item, 'wangsulan')) !!}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="faq__empty rv">
                <h3>Belum ada FAQ</h3>
                <p>Pertanyaan yang sering diajukan akan tampil setelah diisi melalui panel admin.</p>
            </div>
        @endif

    </div>
</section>
