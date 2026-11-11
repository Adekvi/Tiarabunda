@php
    $isEdit = $item && $item->exists;
    $pageTitle = $isEdit ? 'Edit Info' : 'Tambah Info';
    $hasImg = $isEdit && $item->img;
    $isActive = old('urip', $item->urip ?? true);
@endphp

<x-dashboard.layout.app :title="$pageTitle" :page-title="$pageTitle">

    @push('css')
        <link rel="stylesheet" href="{{ asset('admin/landing/info/form.css') }}">
    @endpush

    <div class="g">

        {{-- HEADER --}}
        <div class="page-header">
            <div>
                <p class="breadcrumb">
                    <a href="{{ route('superadmin.info.index') }}">Info Terkini</a>
                    <span style="opacity: .5;">/</span>
                    {{ $isEdit ? 'Ubah' : 'Tambah' }}
                </p>
                <h2>{{ $isEdit ? 'Ubah Data Info' : 'Tambah Info Baru' }}</h2>
                <p class="subtitle">
                    {{ $isEdit ? 'Perbarui informasi yang tampil di slider beranda.' : 'Lengkapi data untuk menampilkan info baru di slider beranda.' }}
                </p>
            </div>
            <a href="{{ route('superadmin.info.index') }}" class="btn gh" style="white-space: nowrap;">← Kembali</a>
        </div>

        {{-- ERROR ALERT --}}
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

            {{-- FORM UTAMA --}}
            <div class="card form-card" style="min-width: 0;">
                <h2>Detail Info</h2>
                <p class="hint">Isi data yang akan tampil di slider <strong>Info Terkini</strong> pada halaman
                    beranda.</p>

                <form method="POST"
                    action="{{ $isEdit ? route('superadmin.info.update', $item) : route('superadmin.info.store') }}"
                    enctype="multipart/form-data">
                    @csrf
                    @if ($isEdit)
                        @method('PUT')
                    @endif

                    {{-- SECTION: Informasi Dasar --}}
                    <div class="form-section">
                        <h4 class="form-section-title">Informasi Dasar</h4>

                        <div class="form-group">
                            <label for="k" class="lbl">Kategori / Tag <span class="req">*</span></label>
                            <input id="k" type="text" name="k" value="{{ old('k', $item->k ?? '') }}"
                                required maxlength="50" placeholder="Contoh: Pengumuman, Beasiswa">
                            <small class="help">Gunakan 1–2 kata agar rapi di kartu info.</small>
                        </div>

                        <div class="form-group">
                            <label for="t" class="lbl">Judul Info <span class="req">*</span></label>
                            <input id="t" type="text" name="t" value="{{ old('t', $item->t ?? '') }}"
                                required maxlength="255" placeholder="Judul utama yang singkat & jelas...">
                        </div>

                        <div class="form-group">
                            <label for="d" class="lbl">Deskripsi Singkat
                                <span style="color: var(--mut); font-weight: 400;">(opsional)</span>
                            </label>
                            <textarea id="d" name="d" rows="3" placeholder="Penjelasan singkat mengenai info ini...">{{ old('d', $item->d ?? '') }}</textarea>
                            <small class="help">Tampil sebagai ringkasan di bawah judul.</small>
                        </div>
                    </div>

                    {{-- SECTION: Tanggal --}}
                    <div class="form-section">
                        <h4 class="form-section-title">Tanggal / Waktu</h4>

                        <div class="form-group" style="margin-bottom: 0;">
                            <label class="lbl">Tanggal <span class="req">*</span></label>

                            <div class="date-mode-switch" id="date-mode-switch">
                                <button type="button" data-mode="picker" class="active">📅 Pilih Tanggal</button>
                                <button type="button" data-mode="text">✏️ Teks Bebas</button>
                            </div>

                            @php
                                $savedTgl = old('tgl', $item->tgl ?? '');
                                $isDateValue = $savedTgl && preg_match('/^\d{4}-\d{2}-\d{2}$/', $savedTgl);
                            @endphp

                            <div class="date-inputs">
                                <div class="mode-panel {{ $isDateValue || !$savedTgl ? 'active' : '' }}"
                                    data-panel="picker">
                                    <input type="date" id="tgl-picker" name="tgl_picker"
                                        value="{{ $isDateValue ? $savedTgl : '' }}">
                                    <small class="help">Format standar: <em>YYYY-MM-DD</em>.</small>
                                </div>

                                <div class="mode-panel {{ $savedTgl && !$isDateValue ? 'active' : '' }}"
                                    data-panel="text">
                                    <input type="text" id="tgl-text" name="tgl_text"
                                        value="{{ $savedTgl && !$isDateValue ? $savedTgl : '' }}" maxlength="50"
                                        placeholder="Contoh: Setiap Senin, 12–15 Nov 2026">
                                    <small class="help">Bebas format teks untuk keterangan waktu yang tidak
                                        spesifik.</small>
                                </div>
                            </div>

                            <input type="hidden" name="tgl" id="tgl-final" value="{{ $savedTgl }}">
                        </div>
                    </div>

                    {{-- SECTION: Poster --}}
                    <div class="form-section">
                        <h4 class="form-section-title">Poster</h4>

                        <div class="form-group" style="margin-bottom: 0;">
                            <label for="img" class="lbl">
                                Upload Poster
                                @unless ($isEdit)
                                    <span class="req">*</span>
                                @endunless
                            </label>

                            <div class="file-drop {{ $hasImg ? 'has-file' : '' }}" id="file-drop">
                                <input id="img" type="file" name="img" accept="image/*"
                                    {{ $isEdit ? '' : 'required' }} onchange="previewInfoImage(event)">
                                <p class="fd-title">{{ $hasImg ? 'Ganti poster' : 'Pilih atau seret gambar ke sini' }}
                                </p>
                                <p class="fd-desc">Rasio portrait (±5:7) · JPG/PNG · Maks 2MB</p>
                                <div class="fd-filename" id="fd-filename">
                                    {{ $hasImg ? basename($item->img) : '' }}
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- SECTION: Status Publikasi --}}
                    <div class="form-section">
                        <h4 class="form-section-title">Status Publikasi</h4>

                        <div class="publish-block">
                            <label class="publish-toggle {{ $isActive ? 'on' : '' }}" id="publish-toggle">
                                <div class="publish-info">
                                    <p class="publish-title">
                                        Tampilkan di Landing
                                        <span class="pub-badge {{ $isActive ? 'is-on' : '' }}" id="publish-badge">
                                            {{ $isActive ? 'Aktif' : 'Draf' }}
                                        </span>
                                    </p>
                                    <p class="publish-desc" id="publish-desc">
                                        {{ $isActive ? 'Info akan langsung tampil di slider publik beranda.' : 'Info disimpan sebagai draf — tidak terlihat oleh pengunjung.' }}
                                    </p>
                                </div>
                                <span class="switch">
                                    <input type="hidden" name="urip" value="0">
                                    <input type="checkbox" name="urip" value="1" id="urip-checkbox"
                                        @checked($isActive)>
                                    <span class="slider"></span>
                                </span>
                            </label>

                            <div class="publish-note {{ $isActive ? 'on' : '' }}" id="publish-note">
                                <span class="note-icon">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <circle cx="12" cy="12" r="10"></circle>
                                        <line x1="12" y1="16" x2="12" y2="12"></line>
                                        <line x1="12" y1="8" x2="12.01" y2="8"></line>
                                    </svg>
                                </span>
                                <span id="publish-note-text">
                                    @if ($isActive)
                                        Setelah disimpan, info ini <strong>langsung tayang</strong> di slider beranda.
                                        Pastikan poster & judul sudah final sebelum menyimpan.
                                    @else
                                        Info akan disimpan sebagai <strong>draf</strong>. Anda dapat mengaktifkannya
                                        kapan saja dari halaman daftar Info Terkini.
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>

                    {{-- ACTIONS --}}
                    <div class="form-actions">
                        <button type="submit" class="btn">Simpan Data</button>
                        <a href="{{ route('superadmin.info.index') }}" class="btn gh">Batal</a>
                    </div>
                </form>
            </div>

            {{-- SIDEBAR --}}
            <aside class="sidebar-scroll">
                <div class="g" style="gap: 16px;">

                    {{-- Preview Poster --}}
                    <div class="card sidebar-card">
                        <h3>Preview Poster</h3>
                        <div class="preview-box">
                            <img id="info-img-preview" src="{{ $hasImg ? asset('storage/' . $item->img) : '' }}"
                                alt="Preview"
                                style="width: 100%; height: 100%; object-fit: cover; {{ $hasImg ? '' : 'display: none;' }}">
                            <div id="info-img-placeholder" class="ph"
                                style="{{ $hasImg ? 'display: none;' : '' }}">
                                Belum ada poster<br>
                                <span style="font-size: 0.72rem; opacity: 0.7;">Upload untuk melihat preview</span>
                            </div>
                        </div>
                    </div>

                    {{-- Ringkasan Form --}}
                    <div class="card sidebar-card">
                        <h3>Ringkasan Form</h3>
                        <div class="meta-row">
                            <span class="k">Mode</span>
                            <span class="v">{{ $isEdit ? 'Edit Data' : 'Buat Baru' }}</span>
                        </div>
                        @if ($isEdit)
                            <div class="meta-row">
                                <span class="k">ID</span>
                                <span class="v">#{{ $item->id }}</span>
                            </div>
                            <div class="meta-row">
                                <span class="k">Status Saat Ini</span>
                                <span class="pub-status-badge {{ $item->urip ? 'is-on' : '' }}">
                                    {{ $item->urip ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </div>
                        @endif
                        <div class="meta-row"
                            style="border-top: 1px dashed var(--line); margin-top: 8px; padding-top: 10px;">
                            <span class="k">Status Form</span>
                            <span class="v" id="summary-status">
                                {{ $isActive ? 'Akan ditampilkan' : 'Disimpan sebagai draf' }}
                            </span>
                        </div>
                    </div>

                    {{-- Panduan --}}
                    <div class="card sidebar-card">
                        <h3>Panduan Pengisian</h3>
                        <ul class="guide-list">
                            <li><strong>Kategori</strong> singkat & jelas.</li>
                            <li><strong>Judul</strong> maksimal 1–2 baris.</li>
                            <li><strong>Poster</strong> wajib, portrait berkualitas.</li>
                            <li><strong>Status</strong> nonaktif jika belum siap tayang.</li>
                        </ul>
                    </div>
                </div>
            </aside>
        </div>
    </div>

    @push('js')
        <script src="{{ asset('admin/landing/info/form.js') }}" defer></script>
    @endpush
</x-dashboard.layout.app>
