@php
    $isEdit = $item->exists;
    $pageTitle = $isEdit ? 'Edit Akreditasi' : 'Tambah Akreditasi';
@endphp

<x-dashboard.layout.app :title="$pageTitle" :page-title="$pageTitle">
    <div class="akr-form-page">
        {{-- ===== Header ===== --}}
        <div class="card akr-form-hero">
            <div class="akr-form-hero__main">
                <p class="akr-form-hero__crumb">
                    <a href="{{ route('superadmin.akreditasi.index') }}">Akreditasi</a>
                    <span aria-hidden="true">/</span>
                    <span class="is-current">{{ $isEdit ? 'Ubah' : 'Tambah' }}</span>
                </p>
                <h2 class="akr-form-hero__title">{{ $pageTitle }}</h2>
                <p class="akr-form-hero__desc">
                    Unggah sertifikat dan tentukan status akreditasi untuk institusi atau program studi.
                </p>
            </div>
            <a href="{{ route('superadmin.akreditasi.index') }}" class="btn gh">← Kembali</a>
        </div>

        {{-- ===== Error ===== --}}
        @if ($errors->any())
            <div class="al w" role="alert">
                <strong style="display:block;margin-bottom:6px;">Periksa kembali isian:</strong>
                <ul style="margin:0;padding-left:18px;">
                    @foreach ($errors->all() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="akr-form-layout">
            {{-- ===== Kolom Form ===== --}}
            <div class="card akr-form-card" style="min-width:0;">
                <h2 style="margin-bottom:6px;">Detail akreditasi</h2>
                <p style="margin:0 0 22px;font-size:0.85rem;color:var(--mut);">
                    Kolom bertanda <span style="color:var(--err)">*</span> wajib diisi.
                </p>

                <form method="POST" id="akrForm" enctype="multipart/form-data"
                    action="{{ $isEdit ? route('superadmin.akreditasi.update', $item) : route('superadmin.akreditasi.store') }}">
                    @csrf
                    @if ($isEdit)
                        @method('PUT')
                    @endif

                    {{-- Judul --}}
                    <div class="f">
                        <label for="jeneng">Judul Akreditasi <span style="color:var(--err)">*</span></label>
                        <input id="jeneng" name="jeneng" type="text" required maxlength="150"
                            value="{{ old('jeneng', $item->jeneng) }}" placeholder="Contoh: Akreditasi Institusi 2024"
                            autocomplete="off">
                        @error('jeneng')
                            <small style="color:var(--err);">{{ $message }}</small>
                        @enderror
                    </div>

                    {{-- Jenis & Status --}}
                    <div class="row-2">
                        <div class="f">
                            <label for="bagean">Jenis Akreditasi <span style="color:var(--err)">*</span></label>
                            <select id="bagean" name="bagean" required>
                                <option value="institusi" @selected(old('bagean', $item->bagean) === 'institusi')>Institusi</option>
                                <option value="prodi" @selected(old('bagean', $item->bagean) === 'prodi')>Program Studi</option>
                            </select>
                        </div>
                        <div class="f">
                            <label for="status_akred">Peringkat / Status</label>
                            <input id="status_akred" name="status_akred" type="text" maxlength="50"
                                value="{{ old('status_akred', $item->status_akred) }}"
                                placeholder="Unggul / Baik Sekali / A">
                        </div>
                    </div>

                    <div class="f" id="wrap-prodi" style="display: none;">
                        <label>Program Studi yang Dicakup <span style="color:var(--err)">*</span></label>

                        <div class="prodi-checklist-box">
                            @foreach ($prodiList as $p)
                                @php
                                    $isChecked = in_array($p->id, old('prodi_ids', $selectedProdis ?? []));
                                @endphp
                                <label class="prodi-check-item">
                                    <input type="checkbox" name="prodi_ids[]" value="{{ $p->id }}"
                                        @checked($isChecked)>
                                    <div class="prodi-check-content">
                                        <span class="prodi-check-name">{{ $p->jeneng }}</span>
                                        <span class="prodi-check-badge">{{ $p->undhak }}</span>
                                    </div>
                                </label>
                            @endforeach
                        </div>

                        <small style="color:var(--mut);font-size:.78rem;display:block;margin-top:8px;">
                            Centang program studi yang menggunakan sertifikat akreditasi ini.
                            <br>*Jika prodi tidak ada di daftar, tambahkan terlebih dahulu di menu Program Studi.
                        </small>
                        @error('prodi_ids')
                            <small style="color:var(--err);">{{ $message }}</small>
                        @enderror
                    </div>

                    {{-- Keterangan --}}
                    <div class="f">
                        <label for="katrangan">Keterangan / Catatan</label>
                        <textarea id="katrangan" name="katrangan" rows="4" placeholder="Catatan singkat (opsional)…">{{ old('katrangan', $item->katrangan) }}</textarea>
                    </div>

                    {{-- Gambar Sertifikat --}}
                    <div class="f">
                        <label for="path_gambar">Gambar Sertifikat</label>
                        <div class="dz" data-dz data-initial-src="{{ $item->gambar_url ?? '' }}">
                            <input id="path_gambar" type="file" name="path_gambar"
                                accept="image/png,image/jpeg,image/webp">

                            <div class="dz-thumb" data-dz-thumb>
                                <svg class="dz-ico" width="26" height="26" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" aria-hidden="true">
                                    <path d="M3 5h18v14H3zM3 16l5-5 4 4 3-3 6 6M9 9h.01" />
                                </svg>
                                <img alt="Pratinjau sertifikat" hidden>
                            </div>

                            <div class="dz-text">
                                <strong data-dz-title>Klik atau seret gambar ke sini</strong>
                                <small data-dz-meta>PNG, JPG, atau WEBP · maks. 2 MB</small>
                            </div>

                            <span class="dz-btn" data-dz-btn>Pilih gambar</span>
                            <button type="button" class="dz-clear" data-dz-clear aria-label="Batalkan pilihan gambar"
                                hidden>×</button>
                        </div>
                        <small class="dz-error" data-dz-error role="alert" hidden></small>
                        @error('path_gambar')
                            <small style="color:var(--err);">{{ $message }}</small>
                        @enderror
                    </div>

                    {{-- Urutan --}}
                    <div class="f" style="max-width:240px;">
                        <label for="urutan">Urutan tampil</label>
                        <input id="urutan" type="number" name="urutan" min="0" max="9999"
                            value="{{ old('urutan', $item->urutan ?? 0) }}">
                        <small style="color:var(--mut);">Angka kecil = tampil lebih atas.</small>
                    </div>

                    {{-- Publikasi --}}
                    <div class="f">
                        <span class="f-label">Publikasi</span>
                        <label class="sw" for="urip">
                            <input type="hidden" name="urip" value="0">
                            <input id="urip" class="sw-in" type="checkbox" name="urip" value="1"
                                @checked(old('urip', $item->urip ?? true))>
                            <span class="sw-track" aria-hidden="true"></span>
                            <span class="sw-text">
                                <strong>Tampilkan di situs publik</strong>
                                <small>Nonaktif = tersimpan, tidak tampil di landing.</small>
                            </span>
                            <span class="sw-state sw-state-on">Tampil</span>
                            <span class="sw-state sw-state-off">Disembunyikan</span>
                        </label>
                    </div>

                    {{-- Aksi --}}
                    <div class="akr-form-actions">
                        <button type="submit"
                            class="btn">{{ $isEdit ? 'Simpan perubahan' : 'Simpan akreditasi' }}</button>
                        <a href="{{ route('superadmin.akreditasi.index') }}" class="btn gh">Batal</a>
                    </div>
                </form>
            </div>

            {{-- ===== Sidebar Kanan ===== --}}
            <aside class="akr-form-aside">
                {{-- Ringkasan & Progress --}}
                <div class="card akr-side-summary">
                    <div class="akr-side-summary__head">
                        <span class="akr-side-badge {{ $isEdit ? 'is-edit' : 'is-new' }}">
                            {{ $isEdit ? 'Mode Edit' : 'Data Baru' }}
                        </span>
                        <h3>{{ $isEdit ? 'Perbarui data' : 'Buat data' }}</h3>
                        <p>Lengkapi data inti. Checklist di bawah menunjukkan kelengkapan isian.</p>
                    </div>

                    <ul class="akr-checklist" id="akrChecklist">
                        <li data-check="jeneng"><span class="dot"></span> Judul akreditasi</li>
                        <li data-check="status_akred"><span class="dot"></span> Status / Peringkat</li>
                        <li data-check="prodi_id"><span class="dot"></span> Program Studi</li>
                        <li data-check="path_gambar"><span class="dot"></span> Sertifikat gambar</li>
                    </ul>

                    <div class="akr-progress">
                        <div class="akr-progress__bar"><span id="akrProgressBar" style="width:0%"></span></div>
                        <small><span id="akrProgressText">0</span>% lengkap</small>
                    </div>
                </div>

                {{-- Status publikasi --}}
                <div class="card akr-side-pub">
                    <h3 style="margin-bottom:10px;">Status publikasi</h3>
                    <div class="akr-pub-row">
                        <span class="akr-pub-dot" id="pubDot"></span>
                        <strong id="pubLabel">Aktif</strong>
                    </div>
                    <p class="akr-pub-desc" id="pubDesc">
                        Sertifikat akan tampil di halaman publik.
                    </p>
                </div>

                {{-- Panduan --}}
                <div class="card">
                    <h3 style="margin-bottom:10px;">Panduan</h3>
                    <ul class="akr-tips">
                        <li><strong>Institusi</strong> — untuk sertifikat kampus secara keseluruhan.</li>
                        <li><strong>Prodi</strong> — pilih program studi terkait.</li>
                        <li>Gunakan gambar hasil scan yang tajam, tanpa watermark berlebih.</li>
                    </ul>
                </div>
            </aside>
        </div>
    </div>

    @push('css')
        <link rel="stylesheet" href="{{ asset('admin/landing/pendidikan/akreditasi/form.css') }}">
    @endpush

    @push('js')
        <script src="{{ asset('admin/landing/pendidikan/akreditasi/form.js') }}" defer></script>
    @endpush
</x-dashboard.layout.app>
