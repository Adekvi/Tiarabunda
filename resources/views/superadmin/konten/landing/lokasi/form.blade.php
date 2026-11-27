@php
    $isEdit = $lokasi !== null;
    $title = $isEdit ? 'Edit ' . $lokasi->nama : 'Tambah Lokasi Kampus';
    $existingFoto = !empty($lokasi?->foto) ? asset($lokasi->foto) : '';
@endphp

<x-dashboard.layout.app :title="$title" :page-title="$title">
    @push('css')
        <link rel="stylesheet" href="{{ asset('admin/landing/lokasi/lok.css') }}">
        <link rel="stylesheet" href="{{ asset('admin/landing/lokasi/form.css') }}">
    @endpush

    <div id="lokasiFormApp" data-existing-foto="{{ $existingFoto }}">
        <div class="lok-head">
            <div>
                <nav class="lok-crumb" aria-label="Breadcrumb">
                    <a href="{{ route('superadmin.dashboard') }}">Dashboard</a>
                    <span>/</span>
                    <a href="{{ route('superadmin.lokasi.index') }}">Lokasi Kampus</a>
                    <span>/</span>
                    <span>{{ $isEdit ? 'Edit' : 'Tambah' }}</span>
                </nav>
                <h1 class="lok-title">{{ $isEdit ? 'Edit ' . $lokasi->nama : 'Tambah Lokasi Kampus Baru' }}</h1>
                <p class="lok-sub">Lengkapi identitas gedung, fasilitas unggulan, alamat, dan tautan Google Maps.</p>
            </div>

            <div class="lok-head-actions">
                <a href="{{ route('superadmin.lokasi.index') }}" class="lok-btn lok-btn--ghost">
                    &larr; Kembali ke Daftar
                </a>
            </div>
        </div>

        @include('superadmin.konten.landing.lokasi.partials.flash')

        @if ($errors->any())
            <div class="lok-alert lok-alert--err" role="alert">
                <div>
                    <strong>Periksa kembali isian formulir:</strong>
                    <ul style="margin: 6px 0 0; padding-left: 18px;">
                        @foreach ($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <form id="lokasiMainForm"
            action="{{ $isEdit ? route('superadmin.lokasi.update', $lokasi) : route('superadmin.lokasi.store') }}"
            method="POST" enctype="multipart/form-data">
            @csrf
            @if ($isEdit)
                @method('PUT')
            @endif

            <div class="lok-form-grid">
                {{-- KOLOM KIRI --}}
                <div class="lok-form-main">
                    <div class="card lok-fcard">
                        <div class="lok-fcard-head">
                            <span class="lok-step-badge">1</span>
                            <div>
                                <h2 class="lok-fcard-title">Informasi Utama Kampus</h2>
                                <p class="lok-fcard-sub">Identitas gedung, deskripsi profil kampus, dan daftar fasilitas
                                    unggulan.</p>
                            </div>
                        </div>

                        <div class="lok-frow lok-frow--3">
                            <div class="lok-fgroup">
                                <label class="lok-flabel" for="kode">Kode Kampus <span
                                        class="lok-req">*</span></label>
                                <input type="text" id="kode" name="kode" class="lok-finput" placeholder="A"
                                    maxlength="10" value="{{ old('kode', $lokasi->kode ?? '') }}" required>
                            </div>

                            <div class="lok-fgroup">
                                <label class="lok-flabel" for="badge">Badge Wilayah <span
                                        class="lok-req">*</span></label>
                                <input type="text" id="badge" name="badge" class="lok-finput"
                                    placeholder="Kampus A • Krukut, Limo"
                                    value="{{ old('badge', $lokasi->badge ?? '') }}" required>
                            </div>

                            <div class="lok-fgroup">
                                <label class="lok-flabel" for="urutan">Urutan Tampil</label>
                                <input type="number" id="urutan" name="urutan" min="0" class="lok-finput"
                                    value="{{ old('urutan', $lokasi->urutan ?? $nextUrutan) }}">
                            </div>
                        </div>

                        <div class="lok-fgroup">
                            <label class="lok-flabel" for="nama">Nama Lokasi Kampus <span
                                    class="lok-req">*</span></label>
                            <input type="text" id="nama" name="nama" class="lok-finput"
                                placeholder="Kampus A — Krukut Depok" value="{{ old('nama', $lokasi->nama ?? '') }}"
                                required>
                        </div>

                        {{-- RICH TEXT EDITOR DESKRIPSI --}}
                        <div class="lok-fgroup">
                            <div class="lok-label-row">
                                <label class="lok-flabel" for="deskripsiEditor" style="margin-bottom: 0;">
                                    Deskripsi Singkat <span class="lok-req">*</span>
                                </label>
                                <span class="lok-char-counter" id="lokDeskripsiCounter">0 / 2000 karakter</span>
                            </div>

                            <div class="lok-rte" id="lokRteWrap">
                                <div class="lok-rte__toolbar" role="toolbar" aria-label="Pemformatan teks deskripsi">
                                    <button type="button" class="lok-rte__btn" data-cmd="bold" title="Tebal (Ctrl+B)">
                                        <strong>B</strong>
                                    </button>
                                    <button type="button" class="lok-rte__btn" data-cmd="italic"
                                        title="Miring (Ctrl+I)">
                                        <em>I</em>
                                    </button>
                                    <button type="button" class="lok-rte__btn" data-cmd="underline"
                                        title="Garis Bawah (Ctrl+U)">
                                        <u>U</u>
                                    </button>

                                    <span class="lok-rte__sep" aria-hidden="true"></span>

                                    <button type="button" class="lok-rte__btn" data-cmd="insertUnorderedList"
                                        title="Daftar Poin">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2.2" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <line x1="8" y1="6" x2="21" y2="6" />
                                            <line x1="8" y1="12" x2="21" y2="12" />
                                            <line x1="8" y1="18" x2="21" y2="18" />
                                            <line x1="3" y1="6" x2="3.01" y2="6" />
                                            <line x1="3" y1="12" x2="3.01" y2="12" />
                                            <line x1="3" y1="18" x2="3.01" y2="18" />
                                        </svg>
                                    </button>
                                    <button type="button" class="lok-rte__btn" data-cmd="insertOrderedList"
                                        title="Daftar Bernomor">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2.2" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <line x1="10" y1="6" x2="21" y2="6" />
                                            <line x1="10" y1="12" x2="21" y2="12" />
                                            <line x1="10" y1="18" x2="21" y2="18" />
                                            <path d="M4 6h1v4" />
                                            <path d="M4 10h2" />
                                            <path d="M6 18H4c0-1 2-2 2-3s-1-1.5-2-1" />
                                        </svg>
                                    </button>

                                    <span class="lok-rte__sep" aria-hidden="true"></span>

                                    <button type="button" class="lok-rte__btn" data-cmd="createLink"
                                        title="Sisipkan Tautan">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2.2" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71" />
                                            <path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71" />
                                        </svg>
                                    </button>
                                    <button type="button" class="lok-rte__btn" data-cmd="removeFormat"
                                        title="Bersihkan Format">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2.2" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <path d="M4 7V4h16v3" />
                                            <path d="M9 20h6" />
                                            <path d="M12 4v16" />
                                            <line x1="3" y1="3" x2="21" y2="21" />
                                        </svg>
                                    </button>
                                </div>

                                <div id="deskripsiEditor" class="lok-rte__area" contenteditable="true"
                                    role="textbox" aria-multiline="true"
                                    data-placeholder="Tuliskan gambaran singkat gedung perkuliahan, suasana belajar, atau layanan utama kampus...">
                                    {!! trim(old('deskripsi', $lokasi->deskripsi ?? '')) !!}</div>
                            </div>

                            <textarea id="deskripsi" name="deskripsi" style="display: none;">{{ old('deskripsi', $lokasi->deskripsi ?? '') }}</textarea>
                            <small class="lok-fhelp lok-rte-err" id="lokDeskripsiError" hidden>Deskripsi singkat wajib
                                diisi.</small>
                        </div>

                        <div class="lok-fgroup" style="margin-bottom: 0;">
                            <label class="lok-flabel" for="unggulan">Fasilitas Unggulan (Chips)</label>
                            <input type="text" id="unggulan" name="unggulan" class="lok-finput"
                                placeholder="Gedung Kampus A, Layanan Akademik, Ruang Perkuliahan, Area Parkir"
                                value="{{ old('unggulan', isset($lokasi) && is_array($lokasi->unggulan) ? implode(', ', $lokasi->unggulan) : '') }}">
                            <small class="lok-fhelp">Pisahkan setiap item fasilitas menggunakan tanda koma (,).</small>
                            <div class="lok-chips-preview" id="unggulanPreviewChips"></div>
                        </div>
                    </div>

                    <div class="card lok-fcard">
                        <div class="lok-fcard-head">
                            <span class="lok-step-badge">2</span>
                            <div>
                                <h2 class="lok-fcard-title">Alamat, Operasional &amp; Google Maps</h2>
                                <p class="lok-fcard-sub">Detail lokasi fisik, jam layanan, kontak telepon, dan
                                    integrasi peta.</p>
                            </div>
                        </div>

                        <div class="lok-fgroup">
                            <label class="lok-flabel" for="alamat">Alamat Lengkap <span
                                    class="lok-req">*</span></label>
                            <textarea id="alamat" name="alamat" rows="2" class="lok-ftextarea" required>{{ old('alamat', $lokasi->alamat ?? '') }}</textarea>
                        </div>

                        <div class="lok-frow lok-frow--2">
                            <div class="lok-fgroup">
                                <label class="lok-flabel" for="jam">Jam Operasional <span
                                        class="lok-req">*</span></label>
                                <input type="text" id="jam" name="jam" class="lok-finput"
                                    placeholder="Senin - Jumat, 08.00 - 16.00 WIB (Sabtu - Minggu libur)"
                                    value="{{ old('jam', $lokasi->jam ?? '') }}" required>
                            </div>

                            <div class="lok-fgroup">
                                <label class="lok-flabel" for="telepon">Nomor Layanan Informasi <span
                                        class="lok-req">*</span></label>
                                <input type="text" id="telepon" name="telepon" class="lok-finput"
                                    placeholder="0811-1701-4000" value="{{ old('telepon', $lokasi->telepon ?? '') }}"
                                    required>
                            </div>
                        </div>

                        <div class="lok-frow lok-frow--2">
                            <div class="lok-fgroup">
                                <label class="lok-flabel" for="koordinat">Titik Koordinat</label>
                                <input type="text" id="koordinat" name="koordinat" class="lok-finput"
                                    placeholder='6°21&apos;07.4"S 106°47&apos;26.9"E'
                                    value="{{ old('koordinat', $lokasi->koordinat ?? '') }}">
                            </div>

                            <div class="lok-fgroup">
                                <label class="lok-flabel" for="akses">Caption Akses (Bawah Foto)</label>
                                <input type="text" id="akses" name="akses" class="lok-finput"
                                    placeholder="Jl. Raya Krukut, RW.2, Limo, Kota Depok"
                                    value="{{ old('akses', $lokasi->akses ?? '') }}">
                            </div>
                        </div>

                        <div class="lok-fgroup">
                            <label class="lok-flabel" for="map_url">URL Petunjuk Arah Google Maps</label>
                            <input type="url" id="map_url" name="map_url" class="lok-finput"
                                placeholder="https://maps.app.goo.gl/..."
                                value="{{ old('map_url', $lokasi->map_url ?? '') }}">
                        </div>

                        <div class="lok-fgroup" style="margin-bottom: 0;">
                            <label class="lok-flabel" for="map_embed">URL Embed Google Maps (atau kode
                                &lt;iframe&gt;)</label>
                            <textarea id="map_embed" name="map_embed" rows="3" class="lok-ftextarea"
                                placeholder="https://www.google.com/maps/embed?pb=...">{{ old('map_embed', $lokasi->map_embed ?? '') }}</textarea>
                            <small class="lok-fhelp">Anda dapat menempelkan URL
                                <code>https://www.google.com/maps/embed?...</code> atau langsung kode
                                <code>&lt;iframe&gt;</code> dari Google Maps.</small>
                        </div>
                    </div>
                </div>

                {{-- KOLOM KANAN: MEDIA GEDUNG & PUBLIKASI --}}
                <aside class="lok-form-side">
                    <div class="card lok-fcard">
                        <div class="lok-fcard-head">
                            <span class="lok-step-badge">3</span>
                            <div>
                                <h2 class="lok-fcard-title">Foto &amp; Ilustrasi Gedung</h2>
                                <p class="lok-fcard-sub">Otomatis dikompresi ke format WebP (rasio 3:2).</p>
                            </div>
                        </div>

                        {{-- Pratinjau Kartu Foto Gedung --}}
                        <div class="lok-preview-stage">
                            <span class="lok-preview-badge" id="prevBadgeGedung">
                                {{ old('badge', $lokasi->badge ?? '') ?: 'Kampus Utama' }}
                            </span>

                            @if ($existingFoto)
                                <img id="previewFotoLokasi" src="{{ $existingFoto }}" alt="Preview Gedung">
                                <div id="placeholderFotoLokasi" class="lok-preview-empty" hidden>
                                    <svg width="26" height="26" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="1.7" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <path d="M3 21h18" />
                                        <path d="M5 21V7l8-4v18" />
                                        <path d="M19 21V11l-6-4" />
                                        <path d="M9 9v.01" />
                                        <path d="M9 12v.01" />
                                        <path d="M9 15v.01" />
                                        <path d="M9 18v.01" />
                                    </svg>
                                    <span>Belum ada foto dipilih.<br>Unggah foto atau pilih ilustrasi di bawah.</span>
                                </div>
                            @else
                                <img id="previewFotoLokasi" src="" alt="Preview Gedung" hidden>
                                <div id="placeholderFotoLokasi" class="lok-preview-empty">
                                    <svg width="26" height="26" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="1.7" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <path d="M3 21h18" />
                                        <path d="M5 21V7l8-4v18" />
                                        <path d="M19 21V11l-6-4" />
                                        <path d="M9 9v.01" />
                                        <path d="M9 12v.01" />
                                        <path d="M9 15v.01" />
                                        <path d="M9 18v.01" />
                                    </svg>
                                    <span>Belum ada foto dipilih.<br>Unggah foto atau pilih ilustrasi di bawah.</span>
                                </div>
                            @endif

                            <div class="lok-preview-caption" id="prevAksesGedung">
                                {{ old('akses', $lokasi->akses ?? '') ?: 'Akses lokasi gedung kampus' }}
                            </div>
                        </div>

                        {{-- Tab Mode Media --}}
                        <div class="lok-media-tabs" role="tablist">
                            <button type="button" class="lok-media-tab is-active" data-lok-tab="upload">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2.2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                                    <polyline points="17 8 12 3 7 8" />
                                    <line x1="12" y1="3" x2="12" y2="15" />
                                </svg>
                                <span>Unggah Foto</span>
                            </button>
                            <button type="button" class="lok-media-tab" data-lok-tab="studio">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2.2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <polygon
                                        points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2" />
                                </svg>
                                <span>Ilustrasi Gedung</span>
                            </button>
                        </div>

                        {{-- Pane 1: Dropzone Upload --}}
                        <div id="lokPaneUpload">
                            <div class="lok-dropzone" id="lokDropzone" tabindex="0" role="button"
                                aria-label="Klik atau seret foto gedung">
                                <input type="file" name="foto" id="inputFotoLokasi"
                                    accept="image/jpeg,image/png,image/webp" hidden>

                                <div class="lok-dropzone__ico">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <rect x="3" y="3" width="18" height="18" rx="3" />
                                        <circle cx="8.5" cy="8.5" r="1.5" />
                                        <polyline points="21 15 16 10 5 21" />
                                    </svg>
                                </div>
                                <div class="lok-dropzone__title">Klik atau seret foto fasad gedung</div>
                                <div class="lok-dropzone__sub">JPG, PNG, WEBP &bull; Auto-konversi <strong>WebP
                                        (1200px)</strong></div>
                            </div>
                        </div>

                        {{-- Pane 2: Studio Ilustrasi Gedung Kustom --}}
                        <div id="lokPaneStudio" hidden>
                            <div class="lok-palette-bar">
                                <span>Tema Warna:</span>
                                <div class="lok-swatches" id="lokPaletteSwatches">
                                    <button type="button" class="lok-swatch is-active" data-palette="navy"
                                        style="--sw:#1f5fbf;" title="Biru Akademik"></button>
                                    <button type="button" class="lok-swatch" data-palette="emerald"
                                        style="--sw:#059669;" title="Hijau Kampus"></button>
                                    <button type="button" class="lok-swatch" data-palette="amber"
                                        style="--sw:#d97706;" title="Emas Klasik"></button>
                                    <button type="button" class="lok-swatch" data-palette="indigo"
                                        style="--sw:#4f46e5;" title="Indigo Modern"></button>
                                </div>
                            </div>

                            <div class="lok-studio-grid" id="lokStudioGrid"></div>
                        </div>

                        {{-- Status File Terpilih --}}
                        <div class="lok-file-meta" id="lokFileMetaBox" hidden>
                            <div class="lok-file-meta__info">
                                <span class="lok-file-meta__badge" id="lokFileSourceBadge">Auto-WebP</span>
                                <div class="lok-file-meta__name" id="lokFileNameText">gedung.webp</div>
                                <div class="lok-file-meta__size" id="lokFileSizeText">1200×800px</div>
                            </div>
                            <button type="button" class="lok-file-meta__clear" id="lokClearFotoBtn"
                                title="Batalkan pilihan baru">
                                &times;
                            </button>
                        </div>

                        @if ($existingFoto)
                            <label class="lok-check-danger" style="margin-top: 12px;">
                                <input type="checkbox" id="hapusFotoCheck" name="hapus_foto" value="1">
                                <span>Hapus foto saat ini</span>
                            </label>
                        @endif
                    </div>

                    <div class="card lok-fcard" style="margin-bottom: 0;">
                        <h2 class="lok-fcard-title" style="margin-bottom: 12px;">Status Publikasi</h2>

                        <label class="lok-switch-row">
                            <input type="checkbox" name="urip" value="1"
                                {{ old('urip', $lokasi->urip ?? true) ? 'checked' : '' }}>
                            <span>
                                <strong>Tampilkan di Halaman Beranda</strong>
                                <small>Nonaktifkan jika lokasi belum siap dipublikasikan.</small>
                            </span>
                        </label>

                        <div class="lok-fsubmit">
                            <button type="submit" class="lok-btn lok-btn--primary" style="width: 100%;">
                                {{ $isEdit ? 'Simpan Perubahan' : 'Simpan Lokasi Kampus' }}
                            </button>
                            <a href="{{ route('superadmin.lokasi.index') }}" class="lok-btn lok-btn--ghost"
                                style="width: 100%;">
                                Batal
                            </a>
                        </div>
                    </div>
                </aside>
            </div>
        </form>
    </div>

    @push('js')
        <script src="{{ asset('admin/landing/lokasi/form.js') }}" defer></script>
    @endpush
</x-dashboard.layout.app>
