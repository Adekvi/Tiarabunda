@php
    $isEdit = $item->exists;
    $pageTitle = $isEdit ? 'Edit Beasiswa' : 'Tambah Beasiswa';
    $syaratText = old('syarat', $item->syarat);
@endphp

<x-dashboard.layout.app :title="$pageTitle" :page-title="$pageTitle">
    <div class="beasiswa-form-page">
        {{-- ===== Header ===== --}}
        <div class="card beasiswa-form-hero">
            <div class="beasiswa-form-hero__main">
                <p class="beasiswa-form-hero__crumb">
                    <a href="{{ route('superadmin.beasiswa.index') }}">Beasiswa</a>
                    <span aria-hidden="true">/</span>
                    <span class="is-current">{{ $isEdit ? 'Ubah' : 'Tambah' }}</span>
                </p>
                <h2 class="beasiswa-form-hero__title">{{ $pageTitle }}</h2>
                <p class="beasiswa-form-hero__desc">
                    Lengkapi detail beasiswa agar calon penerima memahami cakupan dan persyaratannya.
                </p>
            </div>
            <a href="{{ route('superadmin.beasiswa.index') }}" class="btn gh">← Kembali</a>
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

        <div class="beasiswa-form-layout">
            {{-- ===== Kolom Form ===== --}}
            <div class="card beasiswa-form-card" style="min-width:0;">
                <h2 style="margin-bottom:6px;">Detail beasiswa</h2>
                <p style="margin:0 0 22px;font-size:0.85rem;color:var(--mut);">
                    Kolom bertanda <span style="color:var(--err)">*</span> wajib diisi.
                </p>

                <form method="POST" id="beasiswaForm"
                    action="{{ $isEdit ? route('superadmin.beasiswa.update', $item) : route('superadmin.beasiswa.store') }}">
                    @csrf
                    @if ($isEdit)
                        @method('PUT')
                    @endif

                    {{-- Nama --}}
                    <div class="f">
                        <label for="jeneng">Nama beasiswa <span style="color:var(--err)">*</span></label>
                        <input id="jeneng" name="jeneng" type="text" required maxlength="150"
                            value="{{ old('jeneng', $item->jeneng) }}"
                            placeholder="Contoh: KIP Kuliah / Beasiswa Prestasi" autocomplete="off">
                        @error('jeneng')
                            <small style="color:var(--err);">{{ $message }}</small>
                        @enderror
                    </div>

                    {{-- Slug --}}
                    <div class="f">
                        <label for="tandha">
                            Slug URL
                            @if ($isEdit)
                                <span style="font-weight:400;color:var(--mut);font-size:.78rem;">
                                    &middot;
                                    <button type="button" id="tandhaLock" class="beasiswa-link-btn">ubah
                                        manual</button>
                                </span>
                            @endif
                        </label>
                        <input id="tandha" name="tandha" type="text" maxlength="150"
                            value="{{ old('tandha', $item->tandha) }}" placeholder="Otomatis dari nama beasiswa"
                            readonly data-edit="{{ $isEdit ? '1' : '0' }}">
                        <small style="color:var(--mut);font-size:.78rem;display:block;margin-top:6px;">
                            Dibuat otomatis dari nama. Hanya huruf, angka, dan tanda hubung.
                        </small>
                        @error('tandha')
                            <small style="color:var(--err);">{{ $message }}</small>
                        @enderror
                    </div>

                    {{-- Cakupan --}}
                    <div class="f">
                        <label for="jangkepan">Cakupan bantuan</label>
                        <input id="jangkepan" name="jangkepan" type="text" maxlength="255"
                            value="{{ old('jangkepan', $item->jangkepan) }}"
                            placeholder="Contoh: SPP penuh + uang saku">
                        <small style="color:var(--mut);">Ringkas: apa saja yang ditanggung beasiswa ini.</small>
                    </div>

                    {{-- Deskripsi --}}
                    @include('superadmin.konten.pendidikan.beasiswa.partials.rte', [
                        'name' => 'katrangan',
                        'label' => 'Deskripsi',
                        'value' => old('katrangan', $item->katrangan),
                        'rows' => 5,
                        'placeholder' => 'Penjelasan umum beasiswa…',
                    ])

                    {{-- Persyaratan --}}
                    @include('superadmin.konten.pendidikan.beasiswa.partials.rte', [
                        'name' => 'syarat',
                        'label' => 'Persyaratan',
                        'value' => old('syarat', $item->syarat),
                        'rows' => 8,
                        'placeholder' => 'Tulis satu syarat per baris (bullet list)...',
                        'hint' => 'Gunakan daftar poin untuk setiap persyaratan.',
                    ])

                    {{-- Urutan --}}
                    <div class="f" style="max-width:240px;">
                        <label for="urutan">Urutan tampil</label>
                        <input id="urutan" type="number" name="urutan" min="0" max="9999"
                            value="{{ old('urutan', $item->urutan ?? 0) }}">
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
                    <div class="beasiswa-form-actions">
                        <button type="submit" class="btn">
                            {{ $isEdit ? 'Simpan perubahan' : 'Simpan beasiswa' }}
                        </button>
                        <a href="{{ route('superadmin.beasiswa.index') }}" class="btn gh">Batal</a>
                    </div>
                </form>
            </div>

            {{-- ===== Sidebar Kanan ===== --}}
            <aside class="beasiswa-form-aside">
                {{-- Ringkasan mode + progress --}}
                <div class="card beasiswa-side-summary">
                    <div class="beasiswa-side-summary__head">
                        <span class="beasiswa-side-badge {{ $isEdit ? 'is-edit' : 'is-new' }}">
                            {{ $isEdit ? 'Mode Edit' : 'Beasiswa Baru' }}
                        </span>
                        <h3>{{ $isEdit ? 'Perbarui data' : 'Buat beasiswa' }}</h3>
                        <p>Lengkapi data inti. Checklist di bawah menunjukkan kelengkapan isian.</p>
                    </div>

                    <ul class="beasiswa-checklist" id="beasiswaChecklist">
                        <li data-check="jeneng"><span class="dot"></span> Nama beasiswa</li>
                        <li data-check="jangkepan"><span class="dot"></span> Cakupan bantuan</li>
                        <li data-check="katrangan"><span class="dot"></span> Deskripsi umum</li>
                        <li data-check="syarat"><span class="dot"></span> Persyaratan</li>
                    </ul>

                    <div class="beasiswa-progress">
                        <div class="beasiswa-progress__bar"><span id="beasiswaProgressBar" style="width:0%"></span>
                        </div>
                        <small><span id="beasiswaProgressText">0</span>% lengkap</small>
                    </div>
                </div>

                {{-- Status publikasi --}}
                <div class="card beasiswa-side-pub">
                    <h3 style="margin-bottom:10px;">Status publikasi</h3>
                    <div class="beasiswa-pub-row">
                        <span class="beasiswa-pub-dot" id="pubDot"></span>
                        <strong id="pubLabel">Aktif</strong>
                    </div>
                    <p class="beasiswa-pub-desc" id="pubDesc">
                        Beasiswa akan tampil di halaman publik.
                    </p>
                </div>

                {{-- Panduan --}}
                <div class="card">
                    <h3 style="margin-bottom:10px;">Panduan</h3>
                    <ul class="beasiswa-tips">
                        <li>Nama harus dikenali calon penerima.</li>
                        <li>Cakupan diisi singkat (SPP, UKT, asrama, dll.).</li>
                        <li>Persyaratan boleh panjang; pisahkan per baris.</li>
                        <li>Slug dibuat otomatis dan bersifat <em>readonly</em>.</li>
                    </ul>
                </div>

                {{-- Preview slug --}}
                <div class="card beasiswa-side-slug">
                    <h3 style="margin-bottom:10px;">Pratinjau URL</h3>
                    <code id="slugPreview">/beasiswa/…</code>
                </div>
            </aside>
        </div>
    </div>

    @push('css')
        <link rel="stylesheet" href="{{ asset('admin/landing/pendidikan/beasiswa/form.css') }}">
    @endpush

    @push('js')
        <script src="{{ asset('admin/landing/pendidikan/beasiswa/form.js') }}" defer></script>
    @endpush
</x-dashboard.layout.app>
