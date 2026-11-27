<x-dashboard.layout.app :title="$isEdit ? 'Edit Slide Pop-up' : 'Tambah Slide Pop-up'" :pageTitle="$isEdit ? 'Edit Slide Pop-up' : 'Tambah Slide Pop-up'">
    @php
        $existingImg = '';
        if ($isEdit && !empty($popup->gambar)) {
            $val = trim($popup->gambar);
            if (str_starts_with($val, 'http://') || str_starts_with($val, 'https://') || str_starts_with($val, '/')) {
                $existingImg = $val;
            } elseif (
                str_starts_with($val, 'storage/') ||
                str_starts_with($val, 'landing/') ||
                str_starts_with($val, 'assets/')
            ) {
                $existingImg = asset($val);
            } else {
                $existingImg = asset('storage/' . ltrim($val, '/'));
            }
        }
    @endphp

    <div class="pop-page" id="popupFormApp" data-existing-img="{{ $existingImg }}">
        @if ($errors->any())
            <div class="al w pop-al" role="alert">
                <div>
                    <strong>Periksa kembali isian formulir berikut:</strong>
                    <ul class="pop-err-list">
                        @foreach ($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <div class="pop-layout">
            {{-- KOLOM KIRI: FORM SLIDE POP-UP --}}
            <div class="pop-main">
                <div class="card pop-card">
                    <div class="pop-card__head">
                        <div>
                            <h3>{{ $isEdit ? 'Edit Slide Pop-up' : 'Tambah Slide Pop-up' }}</h3>
                            <p class="pop-card__sub">Atur konten informasi, format deskripsi, serta banner ilustrasi
                                teroptimasi untuk halaman utama.</p>
                        </div>
                        <a href="{{ route('superadmin.popup.index') }}" class="btn pop-btn-ghost">
                            &larr; Kembali ke Daftar
                        </a>
                    </div>

                    <form id="popupMainForm"
                        action="{{ $isEdit ? route('superadmin.popup.update', $popup->id) : route('superadmin.popup.store') }}"
                        method="POST" enctype="multipart/form-data" class="pop-form">
                        @csrf
                        @if ($isEdit)
                            @method('PUT')
                        @endif

                        {{-- STEP 1: IDENTITAS & DESKRIPSI RICH TEXT --}}
                        <div class="pop-section">
                            <div class="pop-section__head">
                                <span class="pop-step">1</span>
                                <div>
                                    <h4>Informasi &amp; Konten Utama</h4>
                                    <span>Tentukan kategori tema, judul utama, dan deskripsi terformat.</span>
                                </div>
                            </div>

                            <div class="pop-form-grid">
                                <div class="f pop-col-4">
                                    <label for="kategori">Kategori <span class="req">*</span></label>
                                    <select id="kategori" name="kategori" class="pop-input" required>
                                        @foreach ($kategoris as $key => $label)
                                            <option value="{{ $key }}" @selected(old('kategori', $popup->kategori) === $key)>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="f pop-col-5">
                                    <label for="badge">Badge / Label Atas</label>
                                    <input id="badge" type="text" name="badge" class="pop-input" maxlength="80"
                                        value="{{ old('badge', $popup->badge) }}"
                                        placeholder="Contoh: Penerimaan Mahasiswa Baru">
                                </div>

                                <div class="f pop-col-3">
                                    <label for="urutan">Urutan Slide</label>
                                    <input id="urutan" type="number" name="urutan" class="pop-input" min="0"
                                        max="9999" value="{{ old('urutan', $popup->urutan) }}">
                                </div>

                                <div class="f pop-col-12">
                                    <label for="judul">Judul Slide <span class="req">*</span></label>
                                    <input id="judul" type="text" name="judul" class="pop-input"
                                        maxlength="180" required value="{{ old('judul', $popup->judul) }}"
                                        placeholder="Contoh: Pendaftaran Mahasiswa Baru Gelombang I Telah Dibuka">
                                </div>

                                {{-- RICH TEXT EDITOR --}}
                                <div class="f pop-col-12">
                                    <div class="pop-label-bar">
                                        <label for="deskripsiEditor">Deskripsi</label>
                                        <span class="pop-counter" id="deskripsiCounter">0 / 2000 karakter</span>
                                    </div>

                                    <div class="pop-rte" id="deskripsiRte">
                                        <div class="pop-rte__bar" role="toolbar" aria-label="Toolbar pemformatan teks">
                                            <button type="button" class="pop-rte__tool" data-cmd="bold"
                                                title="Tebal (Ctrl+B)">
                                                <strong>B</strong>
                                            </button>
                                            <button type="button" class="pop-rte__tool" data-cmd="italic"
                                                title="Miring (Ctrl+I)">
                                                <em>I</em>
                                            </button>
                                            <button type="button" class="pop-rte__tool" data-cmd="underline"
                                                title="Garis Bawah (Ctrl+U)">
                                                <u>U</u>
                                            </button>

                                            <span class="pop-rte__div" aria-hidden="true"></span>

                                            <button type="button" class="pop-rte__tool" data-cmd="insertUnorderedList"
                                                title="Daftar Poin">
                                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none"
                                                    stroke="currentColor" stroke-width="2.2" stroke-linecap="round"
                                                    stroke-linejoin="round">
                                                    <line x1="8" y1="6" x2="21" y2="6" />
                                                    <line x1="8" y1="12" x2="21"
                                                        y2="12" />
                                                    <line x1="8" y1="18" x2="21"
                                                        y2="18" />
                                                    <line x1="3" y1="6" x2="3.01"
                                                        y2="6" />
                                                    <line x1="3" y1="12" x2="3.01"
                                                        y2="12" />
                                                    <line x1="3" y1="18" x2="3.01"
                                                        y2="18" />
                                                </svg>
                                            </button>
                                            <button type="button" class="pop-rte__tool" data-cmd="insertOrderedList"
                                                title="Daftar Bernomor">
                                                <svg width="14" height="14" viewBox="0 0 24 24"
                                                    fill="none" stroke="currentColor" stroke-width="2.2"
                                                    stroke-linecap="round" stroke-linejoin="round">
                                                    <line x1="10" y1="6" x2="21"
                                                        y2="6" />
                                                    <line x1="10" y1="12" x2="21"
                                                        y2="12" />
                                                    <line x1="10" y1="18" x2="21"
                                                        y2="18" />
                                                    <path d="M4 6h1v4" />
                                                    <path d="M4 10h2" />
                                                    <path d="M6 18H4c0-1 2-2 2-3s-1-1.5-2-1" />
                                                </svg>
                                            </button>

                                            <span class="pop-rte__div" aria-hidden="true"></span>

                                            <button type="button" class="pop-rte__tool" data-cmd="createLink"
                                                title="Sisipkan Tautan">
                                                <svg width="14" height="14" viewBox="0 0 24 24"
                                                    fill="none" stroke="currentColor" stroke-width="2.2"
                                                    stroke-linecap="round" stroke-linejoin="round">
                                                    <path
                                                        d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71" />
                                                    <path
                                                        d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71" />
                                                </svg>
                                            </button>
                                            <button type="button" class="pop-rte__tool" data-cmd="removeFormat"
                                                title="Hapus Format">
                                                <svg width="14" height="14" viewBox="0 0 24 24"
                                                    fill="none" stroke="currentColor" stroke-width="2.2"
                                                    stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M4 7V4h16v3" />
                                                    <path d="M9 20h6" />
                                                    <path d="M12 4v16" />
                                                    <line x1="3" y1="3" x2="21"
                                                        y2="21" />
                                                </svg>
                                            </button>
                                        </div>

                                        <div id="deskripsiEditor" class="pop-rte__body" contenteditable="true"
                                            role="textbox" aria-multiline="true"
                                            data-placeholder="Tulis informasi penting, jadwal pendaftaran, syarat beasiswa, atau poin keunggulan...">
                                            {!! old('deskripsi', $popup->deskripsi) !!}</div>
                                    </div>

                                    <textarea id="deskripsi" name="deskripsi" maxlength="5000" hidden>{{ old('deskripsi', $popup->deskripsi) }}</textarea>
                                </div>
                            </div>
                        </div>

                        {{-- STEP 2: VISUAL BANNER & STUDIO ILUSTRASI CUSTOM --}}
                        <div class="pop-section">
                            <div class="pop-section__head">
                                <span class="pop-step">2</span>
                                <div>
                                    <h4>Gambar Ilustrasi / Banner (Auto-WebP)</h4>
                                    <span>Unggah banner sendiri atau buat ilustrasi editorial kustom dalam 1
                                        klik.</span>
                                </div>
                            </div>

                            <div class="pop-media-switch" role="tablist">
                                <button type="button" class="pop-media-tab is-active" data-media-tab="upload">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="2.1" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                                        <polyline points="17 8 12 3 7 8" />
                                        <line x1="12" y1="3" x2="12" y2="15" />
                                    </svg>
                                    <span>Unggah Gambar</span>
                                </button>
                                <button type="button" class="pop-media-tab" data-media-tab="studio">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="2.1" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <path
                                            d="M12 2l2.4 7.4H22l-6.2 4.5 2.4 7.4L12 16.8l-6.2 4.5 2.4-7.4L2 9.4h7.6z" />
                                    </svg>
                                    <span>Studio Ilustrasi Kustom</span>
                                </button>
                            </div>

                            {{-- Tab 1: Upload File --}}
                            <div id="paneUpload">
                                <div class="pop-dropzone" id="popDropzone" tabindex="0" role="button"
                                    aria-label="Pilih atau seret file gambar">
                                    <input id="gambar" type="file" name="gambar"
                                        accept=".jpg,.jpeg,.png,.webp" hidden>
                                    <div class="pop-dropzone__ico">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <rect x="3" y="3" width="18" height="18" rx="3" />
                                            <circle cx="8.5" cy="8.5" r="1.5" />
                                            <polyline points="21 15 16 10 5 21" />
                                        </svg>
                                    </div>
                                    <div class="pop-dropzone__title">Klik untuk memilih gambar atau seret file ke sini
                                    </div>
                                    <div class="pop-dropzone__sub">JPG, PNG, atau WEBP &bull; Otomatis dikompresi ke
                                        <strong>WebP (maks. 1200px)</strong> sebelum diunggah
                                    </div>
                                </div>
                            </div>

                            {{-- Tab 2: Generator Ilustrasi Kustom --}}
                            <div id="paneStudio" hidden>
                                <div class="pop-studio-head">
                                    <span>Pilih desain ilustrasi di bawah. Warna otomatis menyesuaikan
                                        <strong>Kategori</strong> yang dipilih dan langsung dirender ke format
                                        <strong>.webp</strong>.</span>
                                </div>
                                <div class="pop-studio-grid" id="popStudioGrid"></div>
                            </div>

                            {{-- Kartu Status Gambar Aktif --}}
                            <div class="pop-asset-card" id="popAssetCard"
                                @if (!$existingImg) hidden @endif>
                                <div class="pop-asset-card__thumb">
                                    <img id="popAssetThumb" src="{{ $existingImg }}" alt="Preview Banner">
                                </div>
                                <div class="pop-asset-card__body">
                                    <span class="pop-asset-card__tag" id="popAssetTag">
                                        {{ $existingImg ? 'Gambar Tersimpan' : 'WebP Teroptimasi' }}
                                    </span>
                                    <div class="pop-asset-card__name" id="popAssetName">
                                        {{ $existingImg ? basename($popup->gambar) : '' }}
                                    </div>
                                    <div class="pop-asset-card__meta" id="popAssetMeta">
                                        {{ $existingImg ? 'Aktif digunakan pada slide ini' : '' }}
                                    </div>
                                </div>
                                <button type="button" class="pop-asset-card__reset" id="popAssetReset"
                                    title="Batalkan pilihan gambar baru">
                                    &times;
                                </button>
                            </div>

                            @if ($isEdit && !empty($popup->gambar))
                                <div style="margin-top: 10px;">
                                    <label class="pop-check">
                                        <input type="checkbox" id="hapusGambarCheck" name="hapus_gambar"
                                            value="1">
                                        <span class="text-err">Hapus gambar saat disimpan</span>
                                    </label>
                                </div>
                            @endif
                        </div>

                        {{-- STEP 3: TOMBOL CTA & PUBLIKASI --}}
                        <div class="pop-section" style="margin-bottom: 0; padding-bottom: 0; border-bottom: 0;">
                            <div class="pop-section__head">
                                <span class="pop-step">3</span>
                                <div>
                                    <h4>Tombol Aksi (CTA) &amp; Publikasi</h4>
                                    <span>Atur tombol navigasi tujuan dan status visibilitas slide.</span>
                                </div>
                            </div>

                            <div class="pop-form-grid">
                                <div class="f pop-col-6">
                                    <label for="teks_tombol">Teks Tombol Utama</label>
                                    <input id="teks_tombol" type="text" name="teks_tombol" class="pop-input"
                                        maxlength="80" value="{{ old('teks_tombol', $popup->teks_tombol) }}"
                                        placeholder="Contoh: Daftar PMB Sekarang">
                                </div>

                                <div class="f pop-col-6">
                                    <label for="url_tombol">URL Tombol Utama</label>
                                    <input id="url_tombol" type="text" name="url_tombol" class="pop-input"
                                        maxlength="255" value="{{ old('url_tombol', $popup->url_tombol) }}"
                                        placeholder="https://... atau /pendidikan/program-pendidikan">
                                </div>

                                <div class="f pop-col-6">
                                    <label for="teks_sekunder">Teks Tombol Sekunder (Opsional)</label>
                                    <input id="teks_sekunder" type="text" name="teks_sekunder" class="pop-input"
                                        maxlength="80" value="{{ old('teks_sekunder', $popup->teks_sekunder) }}"
                                        placeholder="Contoh: Lihat Brosur">
                                </div>

                                <div class="f pop-col-6">
                                    <label for="url_sekunder">URL Tombol Sekunder (Opsional)</label>
                                    <input id="url_sekunder" type="text" name="url_sekunder" class="pop-input"
                                        maxlength="255" value="{{ old('url_sekunder', $popup->url_sekunder) }}"
                                        placeholder="/layanan/akademik">
                                </div>

                                <div class="f pop-col-6">
                                    <label class="pop-switch-box">
                                        <input type="checkbox" name="buka_tab_baru" value="1"
                                            @checked(old('buka_tab_baru', $popup->buka_tab_baru))>
                                        <span>
                                            <strong>Buka URL utama di tab baru</strong>
                                            <small>Ideal untuk tautan eksternal seperti portal pendaftaran PMB.</small>
                                        </span>
                                    </label>
                                </div>

                                <div class="f pop-col-6">
                                    <label class="pop-switch-box">
                                        <input type="checkbox" name="is_active" value="1"
                                            @checked(old('is_active', $popup->is_active))>
                                        <span>
                                            <strong>Tampilkan slide di landing utama</strong>
                                            <small>Slide aktif akan masuk dalam antrean Welcome Pop-up beranda.</small>
                                        </span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="pop-form__foot">
                            <a href="{{ route('superadmin.popup.index') }}" class="btn pop-btn-ghost">Batal</a>
                            <button type="submit" class="btn pop-btn-primary">
                                {{ $isEdit ? 'Simpan Perubahan' : 'Simpan Slide' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- KOLOM KANAN: LIVE INTERACTIVE PREVIEW & INFORMASI --}}
            <aside class="pop-aside">
                <div class="card pop-preview-card">
                    <div class="pop-preview-card__top">
                        <span class="pop-live-badge">
                            <span class="pop-live-dot"></span>
                            Pratinjau Pop-up
                        </span>
                        <span class="pop-kat-pill" id="prevKatPill">PMB &amp; Brosur</span>
                    </div>

                    <div class="pop-stage" id="popStage" data-theme="pmb">
                        <div class="pop-stage__accent"></div>

                        <div class="pop-stage__media" id="prevMediaBox"
                            @if (!$existingImg) hidden @endif>
                            <img id="prevStageImg" src="{{ $existingImg }}" alt="Pratinjau Banner">
                        </div>

                        <div class="pop-stage__body">
                            <span class="pop-stage__badge" id="prevBadge">
                                {{ old('badge', $popup->badge) ?: 'Informasi Kampus' }}
                            </span>

                            <h4 class="pop-stage__title" id="prevTitle">
                                {{ old('judul', $popup->judul) ?: 'Judul Slide Pop-up Akan Tampil di Sini' }}
                            </h4>

                            <div class="pop-stage__desc" id="prevDesc">
                                {!! old('deskripsi', $popup->deskripsi) ?:
                                    '<p>Deskripsi singkat mengenai pendaftaran mahasiswa baru, program studi, atau pengumuman kampus akan tampil rapi di sini.</p>' !!}
                            </div>

                            <div class="pop-stage__btns">
                                <span class="pop-stage__btn1" id="prevBtn1">
                                    {{ old('teks_tombol', $popup->teks_tombol) ?: 'Selengkapnya' }}
                                </span>
                                <span class="pop-stage__btn2" id="prevBtn2"
                                    @if (!old('teks_sekunder', $popup->teks_sekunder)) hidden @endif>
                                    {{ old('teks_sekunder', $popup->teks_sekunder) }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card pop-info-box">
                    <h3>Informasi &amp; Optimasi</h3>
                    <ul class="pop-tips">
                        <li><strong>Kategori:</strong> Menentukan palet warna aksen pada slide pop-up dan ilustrasi
                            kustom.</li>
                        <li><strong>Rich Text:</strong> Gunakan huruf <strong>tebal</strong> atau daftar poin agar info
                            penting mudah dibaca.</li>
                        <li><strong>Auto-WebP:</strong> Gambar unggahan maupun ilustrasi kustom otomatis dikonversi ke
                            <code>.webp</code> agar loading pop-up cepat.
                        </li>
                    </ul>
                </div>
            </aside>
        </div>
    </div>

    @push('js')
        <script src="{{ asset('admin/landing/popup/form.js') }}}" defer></script>
    @endpush
</x-dashboard.layout.app>
