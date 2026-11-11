@php
    $isEdit = $item && $item->exists;
    $pageTitle = 'Pengaturan Hero';
@endphp

<x-dashboard.layout.app :title="$pageTitle" :page-title="$pageTitle">
    <div class="g">

        {{-- Breadcrumb aksi atas --}}
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

        {{-- Konten: form + sidebar --}}
        <div class="g g2" style="align-items: start;">

            {{-- Kolom form --}}
            <div class="card" style="min-width: 0; padding: 0; overflow: hidden;">

                {{-- Header form --}}
                <div
                    style="padding: 22px 26px 18px; border-bottom: 1px solid var(--line); background: linear-gradient(135deg, var(--bg) 0%, transparent 100%);">
                    <div style="display: flex; align-items: center; gap: 12px;">
                        <div
                            style="width: 40px; height: 40px; border-radius: 10px; background: var(--pri); opacity: 0.1; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--pri)"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                                <circle cx="8.5" cy="8.5" r="1.5"></circle>
                                <polyline points="21 15 16 10 5 21"></polyline>
                            </svg>
                        </div>
                        <div>
                            <h2 style="margin: 0; font-size: 1.05rem;">Detail Hero</h2>
                            <p style="margin: 2px 0 0; font-size: 0.82rem; color: var(--mut);">
                                Atur teks, tombol, dan media yang tampil di bagian paling atas halaman beranda.
                            </p>
                        </div>
                    </div>
                </div>

                <form method="POST" action="{{ route('superadmin.hero.update') }}" enctype="multipart/form-data"
                    style="padding: 24px 26px 28px;">
                    @csrf
                    @method('PUT')

                    {{-- Section 01: Teks utama --}}
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

                    {{-- Section 02: Tombol aksi --}}
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

                    {{-- Section 03: Media --}}
                    <div style="margin-bottom: 28px;">
                        <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 16px;">
                            <span
                                style="font-size: 0.7rem; font-weight: 700; color: var(--pri); text-transform: uppercase; letter-spacing: 0.08em;">03</span>
                            <h3 style="margin: 0; font-size: 0.95rem;">Media background</h3>
                            <div style="flex: 1; height: 1px; background: var(--line);"></div>
                        </div>

                        {{-- Tipe media --}}
                        <div class="f" style="margin-bottom: 18px;">
                            <label style="font-weight: 600; font-size: 0.85rem;">Tipe media <span
                                    style="color: var(--err);">*</span></label>
                            <div
                                style="display: grid; grid-template-columns: repeat(auto-fit, minmax(140px, 1fr)); gap: 10px; margin-top: 8px;">
                                @php
                                    $currentTipe = old('tipe_media', $item->tipe_media ?? 'none');
                                @endphp
                                @foreach ([
        'none' => ['label' => 'Tanpa media', 'desc' => 'Warna dasar', 'icon' => '<path d="M18.36 6.64a9 9 0 1 1-12.73 0"></path><line x1="12" y1="2" x2="12" y2="12"></line>'],
        'gambar' => ['label' => 'Gambar', 'desc' => 'Rasio 16:9', 'icon' => '<rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline>'],
        'video' => ['label' => 'Video', 'desc' => 'MP4 / WebM', 'icon' => '<polygon points="23 7 16 12 23 17 23 7"></polygon><rect x="1" y="5" width="15" height="14" rx="2" ry="2"></rect>'],
    ] as $val => $opt)
                                    <label style="position: relative; display: block; cursor: pointer; margin: 0;">
                                        <input type="radio" name="tipe_media" value="{{ $val }}"
                                            class="tipe-media peer" {{ $currentTipe === $val ? 'checked' : '' }}
                                            style="position: absolute; opacity: 0; width: 0; height: 0;">
                                        <div class="media-option" data-value="{{ $val }}"
                                            style="padding: 14px; border: 1.5px solid var(--line); border-radius: 10px; background: var(--bg); transition: all 0.18s ease; text-align: center;">
                                            <div class="media-icon"
                                                style="width: 34px; height: 34px; margin: 0 auto 8px; border-radius: 8px; display: flex; align-items: center; justify-content: center; background: rgba(99,102,241,0.08); transition: all 0.18s ease;">
                                                <svg width="16" height="16" viewBox="0 0 24 24"
                                                    fill="none" stroke="var(--mut)" stroke-width="2"
                                                    stroke-linecap="round" stroke-linejoin="round">
                                                    {!! $opt['icon'] !!}
                                                </svg>
                                            </div>
                                            <div style="font-weight: 600; font-size: 0.85rem;">{{ $opt['label'] }}
                                            </div>
                                            <div style="font-size: 0.72rem; color: var(--mut); margin-top: 2px;">
                                                {{ $opt['desc'] }}</div>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                            @error('tipe_media')
                                <small style="color: var(--err);">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- Upload area --}}
                        <div id="upload_section" style="display: none; margin-bottom: 18px;">
                            <label style="font-weight: 600; font-size: 0.85rem; display: block; margin-bottom: 8px;">
                                Upload media <span style="color: var(--err);">*</span>
                            </label>

                            <input type="file" id="input_media" name="path_media" style="display: none;">

                            <div id="dropzone"
                                style="position: relative; border: 2px dashed var(--line); border-radius: 12px; background: var(--bg); padding: 28px 20px; text-align: center; cursor: pointer; transition: all 0.18s ease;">
                                <div id="dropzone_empty">
                                    <div
                                        style="width: 48px; height: 48px; margin: 0 auto 12px; border-radius: 12px; background: rgba(99,102,241,0.08); display: flex; align-items: center; justify-content: center;">
                                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none"
                                            stroke="var(--pri)" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                            <polyline points="17 8 12 3 7 8"></polyline>
                                            <line x1="12" y1="3" x2="12" y2="15">
                                            </line>
                                        </svg>
                                    </div>
                                    <div style="font-weight: 600; font-size: 0.88rem; margin-bottom: 4px;">Klik atau
                                        seret file ke sini</div>
                                    <div id="dropzone_hint" style="font-size: 0.78rem; color: var(--mut);">Format yang
                                        didukung: JPG, PNG, WEBP</div>
                                </div>

                                {{-- Preview container --}}
                                <div id="dropzone_preview" class="hidden-preview">
                                    <div id="preview_wrap"
                                        style="position: relative; display: inline-block; margin-bottom: 12px; width: 100%;">
                                        <img id="preview_img" alt="Preview"
                                            style="max-height: 180px; max-width: 100%; border-radius: 10px; box-shadow: 0 4px 12px rgba(0,0,0,0.08); display: none;">
                                        <video id="preview_video"
                                            style="max-height: 180px; max-width: 100%; border-radius: 10px; box-shadow: 0 4px 12px rgba(0,0,0,0.08); display: none;"
                                            muted playsinline preload="metadata" controls></video>
                                    </div>
                                    <div
                                        style="display: flex; align-items: center; justify-content: center; gap: 10px; flex-wrap: wrap;">
                                        <div style="text-align: left; min-width: 0;">
                                            <div id="preview_name"
                                                style="font-weight: 600; font-size: 0.85rem; max-width: 280px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                            </div>
                                            <div id="preview_size" style="font-size: 0.75rem; color: var(--mut);">
                                            </div>
                                        </div>
                                        <button type="button" id="btn_remove_media"
                                            style="padding: 6px 12px; border-radius: 8px; border: 1px solid var(--err); background: transparent; color: var(--err); font-size: 0.78rem; font-weight: 600; cursor: pointer; transition: all 0.15s;">Hapus</button>
                                    </div>
                                </div>

                                <div id="dropzone_loading" style="display: none;">
                                    <div
                                        style="width: 32px; height: 32px; margin: 0 auto 10px; border: 3px solid rgba(99,102,241,0.15); border-top-color: var(--pri); border-radius: 50%; animation: heroSpin 0.8s linear infinite;">
                                    </div>
                                    <div style="font-size: 0.82rem; color: var(--mut);">Memuat preview...</div>
                                </div>
                            </div>

                            @if ($item->path_media ?? false)
                                <div id="current_media"
                                    style="margin-top: 12px; padding: 12px; border: 1px solid var(--line); border-radius: 10px; background: var(--bg); display: flex; align-items: center; gap: 12px;">
                                    @if (($item->tipe_media ?? '') === 'gambar')
                                        <img src="{{ asset('storage/' . $item->path_media) }}" alt="Media"
                                            style="width: 60px; height: 45px; object-fit: cover; border-radius: 6px; flex-shrink: 0;">
                                    @elseif (($item->tipe_media ?? '') === 'video')
                                        <video src="{{ asset('storage/' . $item->path_media) }}"
                                            style="width: 60px; height: 45px; object-fit: cover; border-radius: 6px; flex-shrink: 0;"
                                            muted></video>
                                    @endif
                                    <div style="min-width: 0; flex: 1;">
                                        <div
                                            style="font-size: 0.72rem; font-weight: 700; color: var(--pri); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 2px;">
                                            File saat ini</div>
                                        <div
                                            style="font-size: 0.8rem; color: var(--mut); overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                            {{ basename($item->path_media) }}</div>
                                    </div>
                                </div>
                            @endif

                            @error('path_media')
                                <small
                                    style="color: var(--err); display: block; margin-top: 8px;">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- Poster video --}}
                        <div id="poster_section" style="display: none;">
                            <label style="font-weight: 600; font-size: 0.85rem; display: block; margin-bottom: 8px;">
                                Poster video <span style="color: var(--mut); font-weight: 500;">(opsional)</span>
                            </label>

                            <input type="file" id="input_poster" name="path_poster" accept="image/*"
                                style="display: none;">

                            <div id="poster_dropzone"
                                style="position: relative; border: 2px dashed var(--line); border-radius: 12px; background: var(--bg); padding: 20px; text-align: center; cursor: pointer; transition: all 0.18s ease;">
                                <div id="poster_empty"
                                    style="display: flex; align-items: center; justify-content: center; gap: 12px;">
                                    <div
                                        style="width: 40px; height: 40px; border-radius: 10px; background: rgba(245,158,11,0.1); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none"
                                            stroke="rgb(245,158,11)" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <rect x="3" y="3" width="18" height="18" rx="2"
                                                ry="2"></rect>
                                            <circle cx="8.5" cy="8.5" r="1.5"></circle>
                                            <polyline points="21 15 16 10 5 21"></polyline>
                                        </svg>
                                    </div>
                                    <div style="text-align: left;">
                                        <div style="font-weight: 600; font-size: 0.85rem;">Upload poster (thumbnail
                                            video)</div>
                                        <div style="font-size: 0.75rem; color: var(--mut);">Gambar tampil sebelum video
                                            diputar. Maks 2MB.</div>
                                    </div>
                                </div>

                                <div id="poster_preview" class="hidden-preview">
                                    <img id="poster_img" alt="Poster"
                                        style="max-height: 120px; max-width: 100%; border-radius: 10px; box-shadow: 0 4px 12px rgba(0,0,0,0.08); margin-bottom: 10px; display: none;">
                                    <div
                                        style="display: flex; align-items: center; justify-content: center; gap: 10px; flex-wrap: wrap;">
                                        <div id="poster_name"
                                            style="font-weight: 600; font-size: 0.82rem; max-width: 280px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                        </div>
                                        <button type="button" id="btn_remove_poster"
                                            style="padding: 6px 12px; border-radius: 8px; border: 1px solid var(--err); background: transparent; color: var(--err); font-size: 0.78rem; font-weight: 600; cursor: pointer; transition: all 0.15s;">Hapus</button>
                                    </div>
                                </div>
                            </div>

                            @if ($item->path_poster ?? false)
                                <div id="current_poster"
                                    style="margin-top: 12px; padding: 12px; border: 1px solid var(--line); border-radius: 10px; background: var(--bg); display: flex; align-items: center; gap: 12px;">
                                    <img src="{{ asset('storage/' . $item->path_poster) }}" alt="Poster"
                                        style="width: 60px; height: 45px; object-fit: cover; border-radius: 6px; flex-shrink: 0;">
                                    <div style="min-width: 0; flex: 1;">
                                        <div
                                            style="font-size: 0.72rem; font-weight: 700; color: rgb(245,158,11); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 2px;">
                                            Poster saat ini</div>
                                        <div
                                            style="font-size: 0.8rem; color: var(--mut); overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                            {{ basename($item->path_poster) }}</div>
                                    </div>
                                </div>
                            @endif

                            @error('path_poster')
                                <small
                                    style="color: var(--err); display: block; margin-top: 8px;">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    {{-- Section 04: Status publikasi --}}
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
                                        style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: rgb(203,213,225); border-radius: 26px; transition: all 0.25s ease;"></span>
                                </label>
                            </div>
                        </div>
                    </div>

                    {{-- Footer aksi --}}
                    <div
                        style="display: flex; gap: 10px; margin-top: 24px; padding-top: 20px; border-top: 1px solid var(--line); flex-wrap: wrap; justify-content: flex-end;">
                        <a href="{{ route('superadmin.hero.index') }}" class="btn gh">Batal</a>
                        <button type="submit" class="btn" style="min-width: 160px;">
                            Simpan pengaturan
                        </button>
                    </div>
                </form>
            </div>

            {{-- SIDEBAR SCROLL --}}
            {{-- Sidebar kanan: sticky + scroll internal --}}
            <div class="hero-sidebar"
                style="display: flex; flex-direction: column; gap: 16px; min-width: 0; max-height: calc(100vh - 140px); overflow-y: auto; overscroll-behavior: contain; padding-right: 4px; scrollbar-width: thin; scrollbar-color: rgba(148,163,184,0.4) transparent;">
                <div class="card" style="padding: 18px 20px; flex-shrink: 0;">
                    <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 14px;">
                        <div
                            style="width: 32px; height: 32px; border-radius: 8px; background: rgba(99, 102, 241, 0.1); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                stroke="var(--pri)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"></circle>
                                <line x1="12" y1="16" x2="12" y2="12"></line>
                                <line x1="12" y1="8" x2="12.01" y2="8"></line>
                            </svg>
                        </div>
                        <h3 style="margin: 0; font-size: 0.9rem;">Panduan singkat</h3>
                    </div>
                    <ul
                        style="margin: 0; padding-left: 0; list-style: none; font-size: 0.82rem; color: var(--mut); line-height: 1.7;">
                        <li style="padding-left: 18px; position: relative; margin-bottom: 6px;">
                            <span style="position: absolute; left: 0; color: var(--pri);">•</span>
                            Judul maksimal 1–2 baris agar terbaca cepat.
                        </li>
                        <li style="padding-left: 18px; position: relative; margin-bottom: 6px;">
                            <span style="position: absolute; left: 0; color: var(--pri);">•</span>
                            Sub judul menjelaskan keunggulan kampus.
                        </li>
                        <li style="padding-left: 18px; position: relative; margin-bottom: 6px;">
                            <span style="position: absolute; left: 0; color: var(--pri);">•</span>
                            Tombol 1 untuk aksi utama (mis. Daftar).
                        </li>
                        <li style="padding-left: 18px; position: relative;">
                            <span style="position: absolute; left: 0; color: var(--pri);">•</span>
                            Media ringan = halaman beranda lebih cepat.
                        </li>
                    </ul>
                </div>

                <div class="card" style="padding: 18px 20px; flex-shrink: 0;">
                    <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 14px;">
                        <div
                            style="width: 32px; height: 32px; border-radius: 8px; background: rgba(34, 197, 94, 0.1); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                stroke="rgb(34, 197, 94)" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                                <polyline points="22 4 12 14.01 9 11.01"></polyline>
                            </svg>
                        </div>
                        <h3 style="margin: 0; font-size: 0.9rem;">Status form</h3>
                    </div>
                    <div style="display: grid; gap: 10px; font-size: 0.84rem;">
                        <div style="display: flex; justify-content: space-between; gap: 8px; align-items: center;">
                            <span style="color: var(--mut);">Mode</span>
                            <strong>{{ $isEdit ? 'Edit' : 'Buat baru' }}</strong>
                        </div>
                        @if ($isEdit)
                            <div style="display: flex; justify-content: space-between; gap: 8px; align-items: center;">
                                <span style="color: var(--mut);">ID</span>
                                <strong>#{{ $item->id }}</strong>
                            </div>
                            <div style="display: flex; justify-content: space-between; gap: 8px; align-items: center;">
                                <span style="color: var(--mut);">Diperbarui</span>
                                <strong style="font-size: 0.8rem;">
                                    {{ optional($item->updated_at)->timezone(config('app.timezone'))->format('d M Y H:i') ?? '—' }}
                                </strong>
                            </div>
                            <div
                                style="display: flex; justify-content: space-between; gap: 8px; align-items: center; padding-top: 8px; border-top: 1px solid var(--line);">
                                <span style="color: var(--mut);">Saat ini</span>
                                <span class="badge {{ $item->urip ? 'ok' : 'err' }}">
                                    {{ $item->urip ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </div>
                        @else
                            <p style="margin: 0; color: var(--mut); font-size: 0.82rem; line-height: 1.55;">
                                Setelah disimpan, Hero langsung bisa dipratinjau di halaman index.
                            </p>
                        @endif
                    </div>
                </div>

                <div class="card" style="padding: 18px 20px; flex-shrink: 0;">
                    <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 14px;">
                        <div
                            style="width: 32px; height: 32px; border-radius: 8px; background: rgba(245, 158, 11, 0.1); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                stroke="rgb(245, 158, 11)" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <rect x="3" y="3" width="18" height="18" rx="2" ry="2">
                                </rect>
                                <circle cx="8.5" cy="8.5" r="1.5"></circle>
                                <polyline points="21 15 16 10 5 21"></polyline>
                            </svg>
                        </div>
                        <h3 style="margin: 0; font-size: 0.9rem;">Saran media</h3>
                    </div>
                    <ul
                        style="margin: 0; padding-left: 0; list-style: none; font-size: 0.82rem; color: var(--mut); line-height: 1.7;">
                        <li style="padding-left: 18px; position: relative; margin-bottom: 6px;">
                            <span style="position: absolute; left: 0; color: rgb(245, 158, 11);">•</span>
                            Video: MP4/WebM, durasi pendek.
                        </li>
                        <li style="padding-left: 18px; position: relative; margin-bottom: 6px;">
                            <span style="position: absolute; left: 0; color: rgb(245, 158, 11);">•</span>
                            Gambar: rasio 16:9, maks 2MB.
                        </li>
                        <li style="padding-left: 18px; position: relative;">
                            <span style="position: absolute; left: 0; color: rgb(245, 158, 11);">•</span>
                            Poster video membantu tampilan awal.
                        </li>
                    </ul>
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
