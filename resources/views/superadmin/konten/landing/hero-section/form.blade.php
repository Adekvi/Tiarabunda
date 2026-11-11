<x-dashboard.layout.app title="Pengaturan Hero" page-title="Pengaturan Hero">
    <div class="g">

        {{-- Flash Message --}}
        @if (session('success'))
            <div class="al" role="status" style="display: flex; align-items: center; gap: 10px;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                    stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
                    <path d="M22 4L12 14.01l-3-3" />
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        {{-- Error Validasi Global --}}
        @if ($errors->any())
            <div class="al w" role="alert" style="display: flex; flex-direction: column; gap: 6px;">
                <strong style="display: flex; align-items: center; gap: 8px;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10" />
                        <line x1="12" y1="8" x2="12" y2="12" />
                        <line x1="12" y1="16" x2="12.01" y2="16" />
                    </svg>
                    Terjadi Kesalahan
                </strong>
                <ul style="margin: 0; padding-left: 24px; font-size: 0.85rem;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Header --}}
        <div class="card">
            <div class="ch2">
                <div>
                    <h2 style="margin-bottom: 4px;">Hero Section (Beranda)</h2>
                    <p style="margin: 0; font-size: 0.85rem; color: var(--mut);">
                        Atur teks, gambar, atau video yang tampil di bagian paling atas halaman utama.
                    </p>
                </div>
            </div>
        </div>

        <form action="{{ route('superadmin.hero.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="g2">

                {{-- KOLOM KIRI: Teks & Tombol --}}
                <div class="g">
                    <div class="card">
                        <h3>Teks Utama</h3>

                        <div class="f">
                            <label for="judul">Judul Utama</label>
                            <input type="text" id="judul" name="judul"
                                value="{{ old('judul', $item->judul ?? 'Kuliah yang langsung membawamu ke dunia kerja') }}"
                                required>
                            <small>Gunakan kalimat yang singkat dan menarik.</small>
                        </div>

                        <div class="f">
                            <label for="sub_judul">Sub Judul (Deskripsi)</label>
                            <textarea id="sub_judul" name="sub_judul" rows="3">{{ old('sub_judul', $item->sub_judul ?? '') }}</textarea>
                        </div>
                    </div>

                    <div class="card">
                        <h3>Tombol Aksi</h3>
                        <div class="g2">
                            <div class="f">
                                <label>Tombol 1 (Utama)</label>
                                <input type="text" name="teks_tombol_1"
                                    value="{{ old('teks_tombol_1', $item->teks_tombol_1 ?? 'Daftar sekarang') }}"
                                    placeholder="Teks tombol..." required>
                                <input type="text" name="link_tombol_1"
                                    value="{{ old('link_tombol_1', $item->link_tombol_1 ?? '#pmb') }}"
                                    placeholder="Link (contoh: #pmb)" required style="margin-top: 6px;">
                            </div>
                            <div class="f">
                                <label>Tombol 2 (Sekunder)</label>
                                <input type="text" name="teks_tombol_2"
                                    value="{{ old('teks_tombol_2', $item->teks_tombol_2 ?? 'Lihat program studi') }}"
                                    placeholder="Teks tombol..." required>
                                <input type="text" name="link_tombol_2"
                                    value="{{ old('link_tombol_2', $item->link_tombol_2 ?? '#prodi') }}"
                                    placeholder="Link (contoh: #prodi)" required style="margin-top: 6px;">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- KOLOM KANAN: Media & Status --}}
                <div class="g">
                    <div class="card">
                        <h3>Media Background</h3>

                        <div class="f">
                            <label>Tipe Media</label>
                            <div style="display: flex; flex-direction: column; gap: 8px; margin-top: 6px;">
                                <label
                                    style="display: flex; align-items: center; gap: 8px; cursor: pointer; font-weight: normal;">
                                    <input type="radio" name="tipe_media" value="none" class="tipe-media"
                                        {{ old('tipe_media', $item->tipe_media ?? 'none') === 'none' ? 'checked' : '' }}>
                                    Tanpa Media (Warna Dasar)
                                </label>
                                <label
                                    style="display: flex; align-items: center; gap: 8px; cursor: pointer; font-weight: normal;">
                                    <input type="radio" name="tipe_media" value="gambar" class="tipe-media"
                                        {{ old('tipe_media', $item->tipe_media ?? '') === 'gambar' ? 'checked' : '' }}>
                                    Gambar
                                </label>
                                <label
                                    style="display: flex; align-items: center; gap: 8px; cursor: pointer; font-weight: normal;">
                                    <input type="radio" name="tipe_media" value="video" class="tipe-media"
                                        {{ old('tipe_media', $item->tipe_media ?? '') === 'video' ? 'checked' : '' }}>
                                    Video (MP4/WebM)
                                </label>
                            </div>
                        </div>

                        <div class="f" style="margin-top: 16px;">
                            <label for="input_media" id="label_media">Upload Media</label>
                            <input type="file" id="input_media" name="path_media">
                            <small id="hint_media">Pilih tipe media terlebih dahulu.</small>

                            @if ($item->path_media ?? false)
                                <div
                                    style="margin-top: 10px; padding: 10px; border: 1px dashed var(--line); border-radius: 8px;">
                                    <small style="color: var(--mut); display: block; margin-bottom: 6px;">File saat
                                        ini:</small>
                                    @if (($item->tipe_media ?? '') === 'gambar')
                                        <img src="{{ asset('storage/' . $item->path_media) }}" alt="Media"
                                            style="max-height: 100px; border-radius: 4px;">
                                    @elseif(($item->tipe_media ?? '') === 'video')
                                        <video src="{{ asset('storage/' . $item->path_media) }}"
                                            style="max-height: 100px; border-radius: 4px;" muted></video>
                                    @endif
                                </div>
                            @endif
                        </div>

                        <div class="f" id="poster_wrap" style="display: none;">
                            <label for="input_poster">Poster Video (Opsional)</label>
                            <input type="file" id="input_poster" name="path_poster" accept="image/*">
                            <small>Thumbnail sebelum video diputar (maks 2MB).</small>

                            @if ($item->path_poster ?? false)
                                <div style="margin-top: 10px;">
                                    <small style="color: var(--mut); display: block; margin-bottom: 6px;">Poster saat
                                        ini:</small>
                                    <img src="{{ asset('storage/' . $item->path_poster) }}" alt="Poster"
                                        style="max-height: 100px; border-radius: 4px;">
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="card">
                        <h3>Status</h3>
                        <div class="f">
                            <label
                                style="display: flex; align-items: center; justify-content: space-between; cursor: pointer;">
                                <span>Tampilkan di Landing</span>
                                <input type="checkbox" name="urip" value="1"
                                    style="width: auto; accent-color: var(--pri);"
                                    {{ old('urip', $item->urip ?? true) ? 'checked' : '' }}>
                            </label>
                        </div>

                        <div style="margin-top: 24px; text-align: right;">
                            <button type="submit" class="btn" style="width: 100%;">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                                    <polyline points="17 21 17 13 7 13 7 21"></polyline>
                                    <polyline points="7 3 7 8 15 8"></polyline>
                                </svg>
                                Simpan Pengaturan
                            </button>
                        </div>
                    </div>
                </div>

            </div>
        </form>
    </div>

    {{-- Script Dinamis untuk Form Upload --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const radios = document.querySelectorAll('.tipe-media');
            const posterWrap = document.getElementById('poster_wrap');
            const inputMedia = document.getElementById('input_media');
            const hintMedia = document.getElementById('hint_media');
            const labelMedia = document.getElementById('label_media');

            function updateUI() {
                const selected = document.querySelector('.tipe-media:checked').value;

                if (selected === 'video') {
                    posterWrap.style.display = 'block';
                    inputMedia.accept = 'video/mp4,video/webm';
                    hintMedia.innerText = 'Upload video MP4/WebM (Maks 15MB).';
                    labelMedia.innerText = 'Upload Video';
                } else if (selected === 'gambar') {
                    posterWrap.style.display = 'none';
                    inputMedia.accept = 'image/*';
                    hintMedia.innerText = 'Upload gambar (Maks 2MB).';
                    labelMedia.innerText = 'Upload Gambar';
                } else {
                    posterWrap.style.display = 'none';
                    inputMedia.accept = '';
                    hintMedia.innerText = 'Tidak perlu upload media.';
                    labelMedia.innerText = 'Upload Media (Nonaktif)';
                }
            }

            radios.forEach(r => r.addEventListener('change', updateUI));
            updateUI();
        });
    </script>
</x-dashboard.layout.app>
