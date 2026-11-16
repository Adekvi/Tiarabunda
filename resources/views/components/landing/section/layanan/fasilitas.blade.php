@props(['items' => []])

@php
    $items = collect($items);
@endphp

<section class="fsl">
    <div class="wrap fsl__wrap">

        <div class="fsl__intro rv">
            <p class="fsl__eyebrow">Sarana & Prasarana</p>
            <h2 class="fsl__title">Fasilitas Kampus</h2>
            <p class="fsl__lead">
                Lingkungan belajar yang mendukung praktik dan pengembangan diri, didesain untuk kenyamanan mahasiswa.
            </p>
        </div>

        @if ($items->isNotEmpty())
            <div class="fsl__grid">
                @foreach ($items as $item)
                    <div class="fsl__card rv">
                        <a href="{{ data_get($item, 'gambar_url') }}" class="fsl__img-wrap fsl-lightbox"
                            data-caption="{{ data_get($item, 'jeneng') }}">

                            {{-- MENGGUNAKAN THUMB_URL & LAZY LOADING AGAR RINGAN --}}
                            <img src="{{ data_get($item, 'thumb_url') }}" alt="{{ data_get($item, 'jeneng') }}"
                                loading="lazy" decoding="async">

                            <div class="fsl__img-overlay">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                                    <polyline points="7 10 12 15 17 10" />
                                    <line x1="12" y1="15" x2="12" y2="3" />
                                </svg>
                            </div>
                        </a>

                        <div class="fsl__content">
                            <h3 class="fsl__card-title">{{ data_get($item, 'jeneng') }}</h3>
                            @if (data_get($item, 'katrangan'))
                                <div class="fsl__card-desc prose">
                                    {!! data_get($item, 'katrangan') !!}
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="fsl__empty rv">
                <h3>Belum ada fasilitas</h3>
                <p>Data fasilitas akan tampil setelah diisi melalui panel admin.</p>
            </div>
        @endif

    </div>
</section>

{{-- ======================================================== --}}
{{-- MODAL LIGHTBOX (Tersembunyi di awal, muncul via JS)      --}}
{{-- ======================================================== --}}
<div id="fsl-lightbox" class="fsl-modal" aria-hidden="true">
    <button class="fsl-modal__close" aria-label="Tutup">&times;</button>
    <div class="fsl-modal__content">
        <img id="fsl-modal-img" src="" alt="">
        <p id="fsl-modal-caption"></p>
    </div>
</div>

{{-- JAVASCRIPT SEDERHANA UNTUK LIGHTBOX --}}
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const lightboxLinks = document.querySelectorAll('.fsl-lightbox');
            const modal = document.getElementById('fsl-lightbox');
            const modalImg = document.getElementById('fsl-modal-img');
            const captionText = document.getElementById('fsl-modal-caption');
            const closeBtn = document.querySelector('.fsl-modal__close');

            lightboxLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    modal.style.display = "flex";
                    // Set src ke gambar asli (resolusi penuh)
                    modalImg.src = this.getAttribute('href');
                    captionText.innerHTML = this.getAttribute('data-caption');
                    document.body.style.overflow = 'hidden'; // Disable scroll
                });
            });

            function closeModal() {
                modal.style.display = "none";
                document.body.style.overflow = 'auto'; // Enable scroll
            }

            closeBtn.addEventListener('click', closeModal);

            // Tutup jika klik di luar gambar
            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    closeModal();
                }
            });

            // Tutup dengan tombol ESC
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && modal.style.display === "flex") {
                    closeModal();
                }
            });
        });
    </script>
@endpush
