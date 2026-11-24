@php
    $isEdit = $item && $item->exists;
    $pageTitle = 'Pengaturan Hero';
    $videoMedia = $item?->videoMedia;
    $fotoMedias = $item?->fotoMedias ?? collect();
@endphp

<x-dashboard.layout.app :title="$pageTitle" :page-title="$pageTitle">
    <div class="g">

        <div class="card" style="padding: 16px 22px;">
            <div class="ch2" style="margin-bottom: 0;">
                <div>
                    <p style="margin: 0 0 4px; font-size: 0.75rem; color: var(--mut); letter-spacing: 0.02em;">
                        <a href="{{ route('superadmin.hero.index') }}"
                            style="color: var(--pri); font-weight: 600; text-decoration: none;">Hero Section</a>
                        <span style="opacity: 0.5;"> / </span>
                        {{ $isEdit ? 'Ubah' : 'Tambah' }}
                    </p>
                    <h2 style="margin: 0; font-size: 1.15rem; letter-spacing: -0.01em;">
                        {{ $isEdit ? 'Ubah pengaturan Hero' : 'Pengaturan Hero baru' }}
                    </h2>
                </div>
                <a href="{{ route('superadmin.hero.index') }}" class="btn gh">← Kembali</a>
            </div>
        </div>

        @if (session('success'))
            <div class="al al-success" role="status">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="al w" role="alert">
                <strong style="display: block; margin-bottom: 6px;">Periksa kembali isian:</strong>
                <ul style="margin: 0; padding-left: 18px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="g g2" style="align-items: start;">

            <div class="card" style="min-width: 0; padding: 0; overflow: hidden;">

                <div
                    style="padding: 22px 26px 18px; border-bottom: 1px solid var(--line); background: linear-gradient(135deg, rgba(255,255,255,0.02) 0%, transparent 100%);">
                    <div style="display: flex; align-items: center; gap: 12px;">
                        <div
                            style="width: 40px; height: 40px; border-radius: 10px; background: rgba(99,102,241,0.14); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--pri)"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                                <circle cx="8.5" cy="8.5" r="1.5"></circle>
                                <polyline points="21 15 16 10 5 21"></polyline>
                            </svg>
                        </div>
                        <div>
                            <h2 style="margin: 0; font-size: 1.05rem;">Detail Hero & Slider</h2>
                            <p style="margin: 2px 0 0; font-size: 0.82rem; color: var(--mut);">
                                Atur teks utama, tombol aksi, 1 video pembuka, dan galeri foto slider.
                            </p>
                        </div>
                    </div>
                </div>

                <form method="POST" action="{{ route('superadmin.hero.update') }}" enctype="multipart/form-data"
                    style="padding: 24px 26px 28px;">
                    @csrf
                    @method('PUT')

                    <div style="margin-bottom: 28px;">
                        <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 16px;">
                            <span
                                style="font-size: 0.7rem; font-weight: 700; color: var(--pri); text-transform: uppercase; letter-spacing: 0.08em;">01</span>
                            <h3 style="margin: 0; font-size: 0.95rem;">Teks utama</h3>
                            <div style="flex: 1; height: 1px; background: var(--line);"></div>
                        </div>

                        <div class="f" style="margin-bottom: 18px;">
                            <label for="judul" style="font-weight: 600; font-size: 0.85rem;">
                                Judul utama <span style="color: var(--err);">*</span>
                            </label>
                            <input id="judul" type="text" name="judul"
                                value="{{ old('judul', $item->judul ?? 'Kuliah yang langsung membawamu ke dunia kerja') }}"
                                required maxlength="255">
                            <small style="color: var(--mut); font-size: 0.78rem;">Gunakan kalimat singkat dan menarik,
                                maksimal 1–2 baris.</small>
                            @error('judul')
                                <small style="color: var(--err);">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="f" style="margin-bottom: 0;">
                            <label for="sub_judul" style="font-weight: 600; font-size: 0.85rem;">Sub judul
                                (deskripsi)</label>
                            <textarea id="sub_judul" name="sub_judul" rows="3" maxlength="500">{{ old('sub_judul', $item->sub_judul ?? '') }}</textarea>
                            <small style="color: var(--mut); font-size: 0.78rem;">Opsional. Jelaskan nilai utama kampus
                                secara ringkas.</small>
                            @error('sub_judul')
                                <small style="color: var(--err);">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    <div style="margin-bottom: 28px;">
                        <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 16px;">
                            <span
                                style="font-size: 0.7rem; font-weight: 700; color: var(--pri); text-transform: uppercase; letter-spacing: 0.08em;">02</span>
                            <h3 style="margin: 0; font-size: 0.95rem;">Tombol aksi</h3>
                            <div style="flex: 1; height: 1px; background: var(--line);"></div>
                        </div>

                        <div class="g g2" style="gap: 18px;">
                            <div
                                style="padding: 14px; border: 1px solid var(--line); border-radius: 10px; background: var(--bg);">
                                <label
                                    style="font-weight: 600; font-size: 0.82rem; display: block; margin-bottom: 10px;">
                                    Tombol 1 <span style="color: var(--mut); font-weight: 500;">(utama)</span> <span
                                        style="color: var(--err);">*</span>
                                </label>
                                <div class="f" style="margin-bottom: 10px;">
                                    <input type="text" name="teks_tombol_1"
                                        value="{{ old('teks_tombol_1', $item->teks_tombol_1 ?? 'Daftar sekarang') }}"
                                        placeholder="Teks tombol..." required maxlength="50">
                                </div>
                                <div class="f" style="margin-bottom: 0;">
                                    <input type="text" name="link_tombol_1"
                                        value="{{ old('link_tombol_1', $item->link_tombol_1 ?? '#pmb') }}"
                                        placeholder="Link (contoh: #pmb)" required maxlength="255">
                                </div>
                            </div>

                            <div
                                style="padding: 14px; border: 1px solid var(--line); border-radius: 10px; background: var(--bg);">
                                <label
                                    style="font-weight: 600; font-size: 0.82rem; display: block; margin-bottom: 10px;">
                                    Tombol 2 <span style="color: var(--mut); font-weight: 500;">(sekunder)</span> <span
                                        style="color: var(--err);">*</span>
                                </label>
                                <div class="f" style="margin-bottom: 10px;">
                                    <input type="text" name="teks_tombol_2"
                                        value="{{ old('teks_tombol_2', $item->teks_tombol_2 ?? 'Lihat program studi') }}"
                                        placeholder="Teks tombol..." required maxlength="50">
                                </div>
                                <div class="f" style="margin-bottom: 0;">
                                    <input type="text" name="link_tombol_2"
                                        value="{{ old('link_tombol_2', $item->link_tombol_2 ?? '#prodi') }}"
                                        placeholder="Link (contoh: #prodi)" required maxlength="255">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div style="margin-bottom: 28px;">
                        <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 16px;">
                            <span
                                style="font-size: 0.7rem; font-weight: 700; color: var(--pri); text-transform: uppercase; letter-spacing: 0.08em;">03</span>
                            <h3 style="margin: 0; font-size: 0.95rem;">Media Slider (Video & Galeri Foto)</h3>
                            <div style="flex: 1; height: 1px; background: var(--line);"></div>
                        </div>

                        <div
                            style="margin-bottom: 18px; padding: 12px 14px; border-radius: 10px; background: rgba(99,102,241,0.1); border: 1px solid rgba(99,102,241,0.28); font-size: 0.82rem; color: var(--fg); line-height: 1.55;">
                            <strong>Alur putar otomatis:</strong>
                            <span style="color: var(--pri); font-weight: 600;">1. Video Utama</span> →
                            <span style="color: var(--pri); font-weight: 600;">2. Seluruh Foto Aktif (berurutan)</span>
                            →
                            <span style="color: var(--pri); font-weight: 600;">3. Kembali ke Video (Loop)</span>.
                        </div>

                        <div class="f" style="margin-bottom: 20px;">
                            <label for="durasi_slider" style="font-weight: 600; font-size: 0.85rem;">
                                Rentang waktu perpindahan slide <span style="color: var(--err);">*</span>
                            </label>
                            @php
                                $durasiVal = (int) old('durasi_slider', $item->durasi_slider ?? 6);
                            @endphp
                            <select id="durasi_slider" name="durasi_slider" style="max-width: 250px;">
                                <option value="5" @selected($durasiVal === 5)>5 detik (Cepat)</option>
                                <option value="6" @selected($durasiVal === 6)>6 detik (Direkomendasikan)</option>
                                <option value="7" @selected($durasiVal === 7)>7 detik (Santai)</option>
                            </select>
                            @error('durasi_slider')
                                <small style="color: var(--err);">{{ $message }}</small>
                            @enderror
                        </div>

                        <div
                            style="padding: 16px; border: 1px solid var(--line); border-radius: 12px; background: var(--bg); margin-bottom: 20px;">
                            <div
                                style="display: flex; align-items: center; justify-content: space-between; gap: 8px; margin-bottom: 12px; flex-wrap: wrap;">
                                <label style="font-weight: 700; font-size: 0.88rem; margin: 0; color: var(--fg);">
                                    A. Video Utama <span
                                        style="font-size: 0.75rem; font-weight: 600; color: var(--pri);">(Slide #1 •
                                        Cukup 1 Video)</span>
                                </label>
                                <label
                                    style="display: inline-flex; align-items: center; gap: 6px; font-size: 0.78rem; font-weight: 600; color: var(--fg); cursor: pointer; margin: 0;">
                                    <input type="hidden" name="video_urip" value="0">
                                    <input type="checkbox" name="video_urip" value="1"
                                        @checked(old('video_urip', $videoMedia?->urip ?? true))>
                                    Tampilkan video di slider
                                </label>
                            </div>

                            <input type="file" id="input_video" name="path_video"
                                accept="video/mp4,video/webm,video/quicktime,.mp4,.webm,.mov" style="display: none;">

                            <div id="video_dropzone" class="hero-dz">
                                <div id="video_empty">
                                    <div
                                        style="width: 44px; height: 44px; margin: 0 auto 10px; border-radius: 10px; background: rgba(99,102,241,0.14); display: flex; align-items: center; justify-content: center;">
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                                            stroke="var(--pri)" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <polygon points="23 7 16 12 23 17 23 7"></polygon>
                                            <rect x="1" y="5" width="15" height="14" rx="2"
                                                ry="2"></rect>
                                        </svg>
                                    </div>
                                    <div
                                        style="font-weight: 600; font-size: 0.86rem; color: var(--fg); margin-bottom: 3px;">
                                        {{ $videoMedia ? 'Klik atau seret video baru untuk mengganti' : 'Klik atau seret 1 file video ke sini' }}
                                    </div>
                                    <div style="font-size: 0.76rem; color: var(--mut);">Format: MP4 / WebM. Maksimal
                                        15MB (opsional).</div>
                                </div>

                                <div id="video_preview_box" class="hidden-preview">
                                    <div style="margin-bottom: 10px;">
                                        <video id="preview_video" muted playsinline preload="metadata" controls
                                            style="max-height: 190px; max-width: 100%; border-radius: 10px; background: #0f172a; display: none;"></video>
                                    </div>
                                    <div
                                        style="display: flex; align-items: center; justify-content: center; gap: 10px; flex-wrap: wrap;">
                                        <div style="text-align: left;">
                                            <div id="video_preview_name"
                                                style="font-weight: 600; font-size: 0.84rem; color: var(--fg);"></div>
                                            <div id="video_preview_size"
                                                style="font-size: 0.74rem; color: var(--mut);"></div>
                                        </div>
                                        <button type="button" id="btn_remove_video"
                                            class="btn-mini-danger">Batalkan</button>
                                    </div>
                                </div>
                            </div>

                            @if ($videoMedia)
                                <div id="current_video_card" class="hero-current-media">
                                    <video src="{{ asset('storage/' . $videoMedia->path_media) }}"
                                        style="width: 72px; height: 50px; object-fit: cover; border-radius: 6px; background: #0f172a; flex-shrink: 0;"
                                        muted></video>
                                    <div style="min-width: 0; flex: 1;">
                                        <div
                                            style="font-size: 0.7rem; font-weight: 700; color: var(--pri); text-transform: uppercase; letter-spacing: 0.05em;">
                                            Video tersimpan saat ini
                                        </div>
                                        <div
                                            style="font-size: 0.8rem; color: var(--mut); overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                            {{ basename($videoMedia->path_media) }}
                                        </div>
                                    </div>
                                    <label
                                        style="display: inline-flex; align-items: center; gap: 6px; font-size: 0.78rem; font-weight: 600; color: var(--err); cursor: pointer; margin: 0;">
                                        <input type="checkbox" name="hapus_video" value="1"
                                            id="chk_hapus_video">
                                        Hapus video
                                    </label>
                                </div>
                            @endif

                            @error('path_video')
                                <small
                                    style="color: var(--err); display: block; margin-top: 6px;">{{ $message }}</small>
                            @enderror

                            <div style="margin-top: 14px; padding-top: 14px; border-top: 1px dashed var(--line);">
                                <label
                                    style="font-weight: 600; font-size: 0.82rem; display: block; margin-bottom: 8px; color: var(--fg);">
                                    Poster Video <span style="color: var(--mut); font-weight: 500;">(opsional, gambar
                                        pembuka sebelum video jalan)</span>
                                </label>
                                <input type="file" id="input_poster" name="path_poster" accept="image/*"
                                    style="display: none;">

                                <div id="poster_dropzone" class="hero-dz" style="padding: 14px;">
                                    <div id="poster_empty"
                                        style="display: flex; align-items: center; justify-content: center; gap: 10px;">
                                        <span style="font-size: 0.82rem; font-weight: 600; color: var(--pri);">+ Pilih
                                            gambar poster (Maks 2MB)</span>
                                    </div>
                                    <div id="poster_preview_box" class="hidden-preview">
                                        <img id="poster_img" alt="Poster"
                                            style="max-height: 110px; max-width: 100%; border-radius: 8px; margin-bottom: 8px; display: none;">
                                        <div
                                            style="display: flex; align-items: center; justify-content: center; gap: 8px;">
                                            <span id="poster_name"
                                                style="font-size: 0.8rem; font-weight: 600; color: var(--fg);"></span>
                                            <button type="button" id="btn_remove_poster"
                                                class="btn-mini-danger">Hapus</button>
                                        </div>
                                    </div>
                                </div>

                                @if (!empty($videoMedia?->path_poster))
                                    <div id="current_poster_card" class="hero-current-media"
                                        style="margin-top: 10px; padding: 10px 12px;">
                                        <img src="{{ asset('storage/' . $videoMedia->path_poster) }}" alt="Poster"
                                            style="width: 52px; height: 38px; object-fit: cover; border-radius: 6px; flex-shrink: 0;">
                                        <div
                                            style="min-width: 0; flex: 1; font-size: 0.78rem; color: var(--mut); overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                            {{ basename($videoMedia->path_poster) }}
                                        </div>
                                        <label
                                            style="display: inline-flex; align-items: center; gap: 6px; font-size: 0.76rem; font-weight: 600; color: var(--err); cursor: pointer; margin: 0;">
                                            <input type="checkbox" name="hapus_poster" value="1">
                                            Hapus
                                        </label>
                                    </div>
                                @endif

                                @error('path_poster')
                                    <small
                                        style="color: var(--err); display: block; margin-top: 6px;">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>

                        <div
                            style="padding: 16px; border: 1px solid var(--line); border-radius: 12px; background: var(--bg);">
                            <div
                                style="display: flex; align-items: center; justify-content: space-between; gap: 8px; margin-bottom: 12px;">
                                <label style="font-weight: 700; font-size: 0.88rem; margin: 0; color: var(--fg);">
                                    B. Galeri Foto Slider <span
                                        style="font-size: 0.75rem; font-weight: 600; color: var(--pri);">(Slide #2 dst.
                                        • Tanpa Batas Jumlah)</span>
                                </label>
                            </div>

                            <input type="file" id="input_fotos" name="fotos[]"
                                accept="image/jpeg,image/png,image/webp,.jpg,.jpeg,.png,.webp" multiple
                                style="display: none;">

                            <div id="fotos_dropzone" class="hero-dz">
                                <div
                                    style="width: 44px; height: 44px; margin: 0 auto 10px; border-radius: 10px; background: rgba(99,102,241,0.14); display: flex; align-items: center; justify-content: center;">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                                        stroke="var(--pri)" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <rect x="3" y="3" width="18" height="18" rx="2"
                                            ry="2"></rect>
                                        <circle cx="8.5" cy="8.5" r="1.5"></circle>
                                        <polyline points="21 15 16 10 5 21"></polyline>
                                    </svg>
                                </div>
                                <div
                                    style="font-weight: 600; font-size: 0.86rem; color: var(--fg); margin-bottom: 3px;">
                                    Klik atau seret
                                    banyak foto sekaligus ke sini</div>
                                <div style="font-size: 0.76rem; color: var(--mut);">Format: JPG, PNG, WEBP. Maks 2MB
                                    per foto. Otomatis dikompres ke WebP.</div>
                            </div>
                            <div id="new_fotos_wrap" style="display: none; margin-top: 14px;">
                                <div
                                    style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 8px;">
                                    <span
                                        style="font-size: 0.76rem; font-weight: 700; color: #4ade80; text-transform: uppercase; letter-spacing: 0.04em;">
                                        Foto baru siap diupload (<span id="new_fotos_count">0</span>)
                                    </span>
                                    <button type="button" id="btn_clear_new_fotos" class="btn-mini-danger">Hapus
                                        semua pilihan baru</button>
                                </div>
                                <div id="new_fotos_grid" class="hero-foto-grid"></div>
                            </div>

                            @if ($fotoMedias->isNotEmpty())
                                <div style="margin-top: 16px; padding-top: 14px; border-top: 1px dashed var(--line);">
                                    <div
                                        style="font-size: 0.76rem; font-weight: 700; color: var(--pri); text-transform: uppercase; letter-spacing: 0.04em; margin-bottom: 10px;">
                                        Foto tersimpan ({{ $fotoMedias->count() }}) — Atur urutan, status tampil, atau
                                        hapus
                                    </div>
                                    <div class="hero-foto-manage-grid">
                                        @foreach ($fotoMedias as $idx => $foto)
                                            <div class="hero-foto-card {{ !$foto->urip ? 'is-inactive' : '' }}"
                                                data-existing-foto>
                                                <div class="hero-foto-thumb">
                                                    <span class="hero-foto-badge">Foto #{{ $idx + 1 }}</span>
                                                    <img src="{{ asset('storage/' . $foto->path_media) }}"
                                                        alt="Foto {{ $idx + 1 }}">
                                                </div>

                                                <div class="hero-foto-controls">
                                                    <div
                                                        style="display: flex; align-items: center; justify-content: space-between; gap: 6px;">
                                                        <label
                                                            style="font-size: 0.72rem; color: var(--mut); font-weight: 600; margin: 0;">Urutan</label>
                                                        <input type="number"
                                                            name="existing_fotos[{{ $foto->id }}][urutan]"
                                                            value="{{ old("existing_fotos.{$foto->id}.urutan", $foto->urutan) }}"
                                                            min="0" class="hero-order-input">
                                                    </div>

                                                    <div
                                                        style="display: flex; align-items: center; justify-content: space-between; gap: 6px; margin-top: 6px; padding-top: 6px; border-top: 1px solid var(--line);">
                                                        <label
                                                            style="display: inline-flex; align-items: center; gap: 4px; font-size: 0.72rem; font-weight: 600; color: var(--fg); cursor: pointer; margin: 0;">
                                                            <input type="hidden"
                                                                name="existing_fotos[{{ $foto->id }}][urip]"
                                                                value="0">
                                                            <input type="checkbox"
                                                                name="existing_fotos[{{ $foto->id }}][urip]"
                                                                value="1" class="chk-urip-foto"
                                                                @checked(old("existing_fotos.{$foto->id}.urip", $foto->urip))>
                                                            <span>Tampil</span>
                                                        </label>

                                                        <label
                                                            style="display: inline-flex; align-items: center; gap: 4px; font-size: 0.72rem; font-weight: 700; color: var(--err); cursor: pointer; margin: 0;">
                                                            <input type="checkbox" name="hapus_fotos[]"
                                                                value="{{ $foto->id }}" class="chk-hapus-foto">
                                                            <span>Hapus</span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            @error('fotos')
                                <small
                                    style="color: var(--err); display: block; margin-top: 6px;">{{ $message }}</small>
                            @enderror
                            @error('fotos.*')
                                <small
                                    style="color: var(--err); display: block; margin-top: 6px;">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    <div style="margin-bottom: 8px;">
                        <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 16px;">
                            <span
                                style="font-size: 0.7rem; font-weight: 700; color: var(--pri); text-transform: uppercase; letter-spacing: 0.08em;">04</span>
                            <h3 style="margin: 0; font-size: 0.95rem;">Status publikasi</h3>
                            <div style="flex: 1; height: 1px; background: var(--line);"></div>
                        </div>

                        <div id="publish_card"
                            style="display: flex; align-items: center; justify-content: space-between; gap: 16px; padding: 16px 18px; border: 1.5px solid var(--line); border-radius: 12px; background: var(--bg); transition: all 0.2s ease;">
                            <div style="display: flex; align-items: center; gap: 14px; min-width: 0;">
                                <div id="publish_icon"
                                    style="width: 40px; height: 40px; border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; transition: all 0.2s ease; background: rgba(148,163,184,0.12);">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none"
                                        stroke="rgb(148,163,184)" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                        <circle cx="12" cy="12" r="3"></circle>
                                    </svg>
                                </div>
                                <div style="min-width: 0;">
                                    <strong id="publish_title"
                                        style="display: block; font-size: 0.9rem; transition: color 0.2s;">Tampilkan di
                                        landing page</strong>
                                    <small id="publish_desc"
                                        style="color: var(--mut); font-weight: 500; font-size: 0.78rem;">
                                        Nonaktif = tersimpan, tidak tampil di situs publik.
                                    </small>
                                </div>
                            </div>

                            <div style="display: flex; align-items: center; gap: 12px; flex-shrink: 0;">
                                <span id="publish_label"
                                    style="font-size: 0.78rem; font-weight: 700; color: var(--mut); text-transform: uppercase; letter-spacing: 0.05em; transition: color 0.2s;">Nonaktif</span>
                                <label class="toggle-switch"
                                    style="position: relative; display: inline-block; width: 48px; height: 26px; margin: 0; cursor: pointer;">
                                    <input type="hidden" name="urip" value="0">
                                    <input type="checkbox" id="urip_toggle" name="urip" value="1"
                                        @checked(old('urip', $item->urip ?? true))
                                        style="opacity: 0; width: 0; height: 0; position: absolute;">
                                    <span class="toggle-slider"
                                        style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: rgba(148,163,184,0.28); border-radius: 26px; transition: all 0.25s ease;"></span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div
                        style="display: flex; gap: 10px; margin-top: 24px; padding-top: 20px; border-top: 1px solid var(--line); flex-wrap: wrap; justify-content: flex-end;">
                        <a href="{{ route('superadmin.hero.index') }}" class="btn gh">Batal</a>
                        <button type="submit" class="btn" style="min-width: 160px;">
                            Simpan pengaturan
                        </button>
                    </div>
                </form>
            </div>

            <div class="hero-sidebar"
                style="display: flex; flex-direction: column; gap: 16px; min-width: 0; max-height: calc(100vh - 140px); overflow-y: auto; overscroll-behavior: contain; padding-right: 4px; scrollbar-width: thin; scrollbar-color: rgba(148,163,184,0.35) transparent;">
                <div class="card" style="padding: 18px 20px; flex-shrink: 0;">
                    <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 14px;">
                        <div
                            style="width: 32px; height: 32px; border-radius: 8px; background: rgba(99, 102, 241, 0.14); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                stroke="var(--pri)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"></circle>
                                <line x1="12" y1="16" x2="12" y2="12"></line>
                                <line x1="12" y1="8" x2="12.01" y2="8"></line>
                            </svg>
                        </div>
                        <h3 style="margin: 0; font-size: 0.9rem;">Aturan Slider Hero</h3>
                    </div>
                    <ul
                        style="margin: 0; padding-left: 0; list-style: none; font-size: 0.82rem; color: var(--mut); line-height: 1.7;">
                        <li style="padding-left: 18px; position: relative; margin-bottom: 6px;">
                            <span style="position: absolute; left: 0; color: var(--pri);">•</span>
                            <strong style="color: var(--fg);">Video cukup 1 saja</strong> dan selalu tampil pada urutan
                            pertama.
                        </li>
                        <li style="padding-left: 18px; position: relative; margin-bottom: 6px;">
                            <span style="position: absolute; left: 0; color: var(--pri);">•</span>
                            <strong style="color: var(--fg);">Foto tidak dibatasi</strong> dan tampil setelah video
                            sesuai nomor urutan.
                        </li>
                        <li style="padding-left: 18px; position: relative; margin-bottom: 6px;">
                            <span style="position: absolute; left: 0; color: var(--pri);">•</span>
                            Anda bisa menyembunyikan foto sementara tanpa menghapusnya (centang <em>Tampil</em>).
                        </li>
                        <li style="padding-left: 18px; position: relative;">
                            <span style="position: absolute; left: 0; color: var(--pri);">•</span>
                            Slider berputar otomatis (loop) dengan rentang 5–7 detik.
                        </li>
                    </ul>
                </div>

                <div class="card" style="padding: 18px 20px; flex-shrink: 0;">
                    <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 14px;">
                        <div
                            style="width: 32px; height: 32px; border-radius: 8px; background: rgba(34, 197, 94, 0.14); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                stroke="rgb(34, 197, 94)" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                                <polyline points="22 4 12 14.01 9 11.01"></polyline>
                            </svg>
                        </div>
                        <h3 style="margin: 0; font-size: 0.9rem;">Ringkasan Media</h3>
                    </div>
                    <div style="display: grid; gap: 10px; font-size: 0.84rem;">
                        <div style="display: flex; justify-content: space-between; gap: 8px; align-items: center;">
                            <span style="color: var(--mut);">Video Utama</span>
                            <strong>{{ $videoMedia ? ($videoMedia->urip ? '1 aktif' : '1 (nonaktif)') : 'Belum ada' }}</strong>
                        </div>
                        <div style="display: flex; justify-content: space-between; gap: 8px; align-items: center;">
                            <span style="color: var(--mut);">Total Foto</span>
                            <strong>{{ $fotoMedias->count() }} ({{ $fotoMedias->where('urip', true)->count() }}
                                aktif)</strong>
                        </div>
                        @if ($isEdit)
                            <div style="display: flex; justify-content: space-between; gap: 8px; align-items: center;">
                                <span style="color: var(--mut);">Diperbarui</span>
                                <strong style="font-size: 0.8rem;">
                                    {{ optional($item->updated_at)->timezone(config('app.timezone'))->format('d M Y H:i') ?? '—' }}
                                </strong>
                            </div>
                            <div
                                style="display: flex; justify-content: space-between; gap: 8px; align-items: center; padding-top: 8px; border-top: 1px solid var(--line);">
                                <span style="color: var(--mut);">Status Hero</span>
                                <span class="badge {{ $item->urip ? 'ok' : 'err' }}">
                                    {{ $item->urip ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('css')
        <link rel="stylesheet" href="{{ asset('admin/landing/hero/form.css') }}">
    @endpush

    @push('js')
        <script src="{{ asset('admin/landing/hero/form.js') }}" defer></script>
    @endpush
</x-dashboard.layout.app>
