<div>
    <!-- Be present above all else. - Naval Ravikant -->
</div>
@php
    $isEdit = $testimoni !== null;
    $title = $isEdit ? 'Edit Testimoni — ' . ($testimoni->nama ?: '#' . $testimoni->id) : 'Tambah Testimoni Baru';
    $existingFoto = $testimoni?->foto_url ?? '';
@endphp

<x-dashboard.layout.app :title="$title" :page-title="$title">

    <div id="testimoniFormApp" data-existing-foto="{{ $existingFoto }}">
        <div class="lok-head">
            <div>
                <nav class="lok-crumb" aria-label="Breadcrumb">
                    <a href="{{ route('superadmin.dashboard') }}">Dashboard</a>
                    <span>/</span>
                    <a href="{{ route('superadmin.testimoni.index') }}">Testimoni</a>
                    <span>/</span>
                    <span>{{ $isEdit ? 'Edit' : 'Tambah' }}</span>
                </nav>
                <h1 class="lok-title">
                    {{ $isEdit ? 'Edit ' . ($testimoni->nama ?: 'Testimoni #' . $testimoni->id) : 'Tambah Testimoni Baru' }}
                </h1>
                <p class="lok-sub">Lengkapi identitas mahasiswa atau alumni, kategori cerita, sorotan utama, dan
                    kutipan.</p>
            </div>

            <div class="lok-head-actions">
                <a href="{{ route('superadmin.testimoni.index') }}" class="lok-btn lok-btn--ghost">
                    &larr; Kembali ke Daftar
                </a>
            </div>
        </div>

        @include('superadmin.konten.landing.testi.partials.flash')

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

        <form id="testimoniMainForm"
            action="{{ $isEdit ? route('superadmin.testimoni.update', $testimoni) : route('superadmin.testimoni.store') }}"
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
                                <h2 class="lok-fcard-title">Identitas Mahasiswa / Alumni</h2>
                                <p class="lok-fcard-sub">Nama lengkap, program studi atau peran, angkatan, dan instansi
                                    saat ini.</p>
                            </div>
                        </div>

                        <div class="lok-frow lok-frow--2">
                            <div class="lok-fgroup">
                                <label class="lok-flabel" for="nama">Nama Lengkap &amp; Gelar</label>
                                <input type="text" id="nama" name="nama" maxlength="150" class="lok-finput"
                                    placeholder="Ns. Nabila Putri Ramadhani, S.Kep."
                                    value="{{ old('nama', $testimoni->nama ?? '') }}">
                            </div>

                            <div class="lok-fgroup">
                                <label class="lok-flabel" for="peran">Peran / Program Studi</label>
                                <input type="text" id="peran" name="peran" maxlength="180" class="lok-finput"
                                    placeholder="Alumni S1 Keperawatan & Profesi Ners"
                                    value="{{ old('peran', $testimoni->peran ?? '') }}">
                            </div>
                        </div>

                        <div class="lok-frow lok-frow--3-tst">
                            <div class="lok-fgroup">
                                <label class="lok-flabel" for="kategori">Kategori Filter</label>
                                <select id="kategori" name="kategori" class="lok-finput">
                                    @foreach (\App\Models\Landing\Testimoni::KATEGORI as $key => $label)
                                        <option value="{{ $key }}" @selected(old('kategori', $testimoni->kategori ?? 'alumni') === $key)>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="lok-fgroup">
                                <label class="lok-flabel" for="angkatan">Badge Angkatan / Lulusan</label>
                                <input type="text" id="angkatan" name="angkatan" maxlength="80" class="lok-finput"
                                    placeholder="Lulusan 2024"
                                    value="{{ old('angkatan', $testimoni->angkatan ?? '') }}">
                            </div>

                            <div class="lok-fgroup">
                                <label class="lok-flabel" for="urutan">Urutan Tampil</label>
                                <input type="number" id="urutan" name="urutan" min="0" class="lok-finput"
                                    value="{{ old('urutan', $testimoni->urutan ?? $nextUrutan) }}">
                            </div>
                        </div>

                        <div class="lok-fgroup" style="margin-bottom: 0;">
                            <label class="lok-flabel" for="instansi">Instansi / Tempat Bekerja / Pencapaian</label>
                            <input type="text" id="instansi" name="instansi" maxlength="180" class="lok-finput"
                                placeholder="Perawat Klinik — RS Mitra Keluarga"
                                value="{{ old('instansi', $testimoni->instansi ?? '') }}">
                        </div>
                    </div>

                    <div class="card lok-fcard" style="margin-bottom: 0;">
                        <div class="lok-fcard-head">
                            <span class="lok-step-badge">2</span>
                            <div>
                                <h2 class="lok-fcard-title">Isi Cerita &amp; Penilaian</h2>
                                <p class="lok-fcard-sub">Judul sorotan singkat, kutipan testimoni, dan skor bintang.</p>
                            </div>
                        </div>

                        <div class="lok-frow lok-frow--2">
                            <div class="lok-fgroup">
                                <label class="lok-flabel" for="sorotan">Judul Sorotan (Highlight)</label>
                                <input type="text" id="sorotan" name="sorotan" maxlength="200" class="lok-finput"
                                    placeholder="Siap praktik sejak hari pertama di rumah sakit"
                                    value="{{ old('sorotan', $testimoni->sorotan ?? '') }}">
                            </div>

                            <div class="lok-fgroup">
                                <label class="lok-flabel" for="rating">Penilaian Bintang</label>
                                <select id="rating" name="rating" class="lok-finput">
                                    @for ($r = 5; $r >= 1; $r--)
                                        <option value="{{ $r }}" @selected((int) old('rating', $testimoni->rating ?? 5) === $r)>
                                            {{ $r }} Bintang ({{ str_repeat('★', $r) }})
                                        </option>
                                    @endfor
                                </select>
                            </div>
                        </div>

                        <div class="lok-fgroup" style="margin-bottom: 0;">
                            <div class="lok-label-row">
                                <label class="lok-flabel" for="kutipan" style="margin-bottom: 0;">
                                    Kutipan Testimoni <span class="lok-req">*</span>
                                </label>
                                <span class="lok-char-counter" id="tstKutipanCounter">0 / 2000 karakter</span>
                            </div>
                            <textarea id="kutipan" name="kutipan" rows="5" maxlength="2000" class="lok-ftextarea"
                                placeholder="Tuliskan pengalaman belajar, fasilitas laboratorium, atau dukungan karier selama kuliah..." required>{{ old('kutipan', $testimoni->kutipan ?? '') }}</textarea>
                            <small class="lok-fhelp">Gunakan kalimat yang ringkas, jujur, dan mudah dibaca
                                pengunjung.</small>
                        </div>
                    </div>
                </div>

                {{-- KOLOM KANAN: PRATINJAU, FOTO & PUBLIKASI --}}
                <aside class="lok-form-side">
                    <div class="card lok-fcard">
                        <div class="lok-fcard-head">
                            <span class="lok-step-badge">3</span>
                            <div>
                                <h2 class="lok-fcard-title">Pratinjau &amp; Foto Profil</h2>
                                <p class="lok-fcard-sub">Otomatis dikompresi ke format WebP (rasio 1:1).</p>
                            </div>
                        </div>

                        <div class="tst-preview-stage">
                            <div class="tst-preview-top">
                                <span class="tst-preview-badge" id="prevAngkatan">
                                    {{ old('angkatan', $testimoni->angkatan ?? '') ?: 'Lulusan 2024' }}
                                </span>
                                <span class="tst-preview-stars" id="prevStars">★★★★★</span>
                            </div>

                            <div class="tst-preview-highlight" id="prevSorotan">
                                {{ old('sorotan', $testimoni->sorotan ?? '') ?: 'Pengalaman Akademik & Praktik' }}
                            </div>

                            <p class="tst-preview-quote" id="prevKutipan">
                                “{{ old('kutipan', $testimoni->kutipan ?? '') ?: 'Kutipan testimoni akan tampil di sini...' }}”
                            </p>

                            <div class="tst-preview-author">
                                <div class="tst-preview-avatar">
                                    <img id="previewFotoTestimoni" src="{{ $existingFoto }}" alt="Preview Profil"
                                        {{ $existingFoto ? '' : 'hidden' }}>
                                    <span id="placeholderFotoTestimoni" {{ $existingFoto ? 'hidden' : '' }}>
                                        {{ $testimoni?->inisial ?? 'TB' }}
                                    </span>
                                </div>
                                <div class="tst-preview-person">
                                    <strong
                                        id="prevNama">{{ old('nama', $testimoni->nama ?? '') ?: 'Nama Mahasiswa / Alumni' }}</strong>
                                    <span
                                        id="prevPeran">{{ old('peran', $testimoni->peran ?? '') ?: 'Program Studi' }}</span>
                                    <small id="prevInstansi">{{ old('instansi', $testimoni->instansi ?? '') }}</small>
                                </div>
                            </div>
                        </div>

                        <div class="lok-media-tabs" role="tablist">
                            <button type="button" class="lok-media-tab is-active" data-tst-tab="upload">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2.2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                                    <polyline points="17 8 12 3 7 8" />
                                    <line x1="12" y1="3" x2="12" y2="15" />
                                </svg>
                                <span>Unggah Foto</span>
                            </button>
                            <button type="button" class="lok-media-tab" data-tst-tab="studio">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2.2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <polygon
                                        points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2" />
                                </svg>
                                <span>Avatar Ilustrasi</span>
                            </button>
                        </div>

                        <div id="tstPaneUpload">
                            <div class="lok-dropzone" id="tstDropzone" tabindex="0" role="button"
                                aria-label="Klik atau seret foto profil">
                                <input type="file" name="foto" id="inputFotoTestimoni"
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
                                <div class="lok-dropzone__title">Klik atau seret foto profil</div>
                                <div class="lok-dropzone__sub">JPG, PNG, WEBP &bull; Auto-konversi <strong>WebP
                                        (400×400px)</strong></div>
                            </div>
                        </div>

                        <div id="tstPaneStudio" hidden>
                            <div class="lok-palette-bar">
                                <span>Tema Latar:</span>
                                <div class="lok-swatches" id="tstPaletteSwatches">
                                    <button type="button" class="lok-swatch is-active" data-palette="navy"
                                        style="--sw:#1f5fbf;" title="Biru Akademik"></button>
                                    <button type="button" class="lok-swatch" data-palette="amber"
                                        style="--sw:#d97706;" title="Emas Hangat"></button>
                                    <button type="button" class="lok-swatch" data-palette="emerald"
                                        style="--sw:#059669;" title="Hijau Toska"></button>
                                    <button type="button" class="lok-swatch" data-palette="indigo"
                                        style="--sw:#4f46e5;" title="Indigo Modern"></button>
                                </div>
                            </div>

                            <div class="lok-studio-grid" id="tstStudioGrid"></div>
                        </div>

                        <div class="lok-file-meta" id="tstFileMetaBox" hidden>
                            <div class="lok-file-meta__info">
                                <span class="lok-file-meta__badge" id="tstFileSourceBadge">Auto-WebP</span>
                                <div class="lok-file-meta__name" id="tstFileNameText">avatar.webp</div>
                                <div class="lok-file-meta__size" id="tstFileSizeText">400×400px</div>
                            </div>
                            <button type="button" class="lok-file-meta__clear" id="tstClearFotoBtn"
                                title="Batalkan pilihan baru">
                                &times;
                            </button>
                        </div>

                        @if ($existingFoto)
                            <label class="lok-check-danger" style="margin-top: 12px;">
                                <input type="checkbox" id="hapusFotoCheck" name="hapus_foto" value="1">
                                <span>Hapus foto saat ini (gunakan inisial nama)</span>
                            </label>
                        @endif
                    </div>

                    <div class="card lok-fcard" style="margin-bottom: 0;">
                        <h2 class="lok-fcard-title" style="margin-bottom: 12px;">Status Publikasi</h2>

                        <label class="lok-switch-row">
                            <input type="checkbox" name="urip" value="1"
                                {{ old('urip', $testimoni->urip ?? true) ? 'checked' : '' }}>
                            <span>
                                <strong>Tampilkan di Halaman Beranda</strong>
                                <small>Nonaktifkan jika testimoni belum siap dipublikasikan.</small>
                            </span>
                        </label>

                        <div class="lok-fsubmit">
                            <button type="submit" class="lok-btn lok-btn--primary" style="width: 100%;">
                                {{ $isEdit ? 'Simpan Perubahan' : 'Simpan Testimoni' }}
                            </button>
                            <a href="{{ route('superadmin.testimoni.index') }}" class="lok-btn lok-btn--ghost"
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
        <script src="{{ asset('admin/landing/testi/form.js') }}" defer></script>
    @endpush

</x-dashboard.layout.app>
