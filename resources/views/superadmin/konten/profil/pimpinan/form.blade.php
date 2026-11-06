@php
    $isEdit = $item->exists;
    $pageTitle = $isEdit ? 'Edit Sambutan' : 'Tambah Sambutan';
@endphp

<x-dashboard.layout.app :title="$pageTitle" :page-title="$pageTitle">
    <div class="pim-form-page">
        {{-- ===== Header ===== --}}
        <div class="card pim-form-hero">
            <div class="pim-form-hero__main">
                <p class="pim-form-hero__crumb">
                    <a href="{{ route('superadmin.pimpinan.index') }}">Sambutan</a>
                    <span aria-hidden="true">/</span>
                    <span class="is-current">{{ $isEdit ? 'Ubah' : 'Tambah' }}</span>
                </p>
                <h2 class="pim-form-hero__title">{{ $pageTitle }}</h2>
                <p class="pim-form-hero__desc">
                    Lengkapi profil, unggah foto formal, dan atur urutan hierarki tampilannya.
                </p>
            </div>
            <a href="{{ route('superadmin.pimpinan.index') }}" class="btn gh">← Kembali</a>
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

        <div class="pim-form-layout">
            {{-- ===== Kolom Form ===== --}}
            <div class="card pim-form-card" style="min-width:0;">
                <h2 style="margin-bottom:6px;">Detail Data</h2>
                <p style="margin:0 0 22px;font-size:0.85rem;color:var(--mut);">
                    Kolom bertanda <span style="color:var(--err)">*</span> wajib diisi.
                </p>

                <form method="POST" id="pimForm" enctype="multipart/form-data"
                    action="{{ $isEdit ? route('superadmin.pimpinan.update', $item) : route('superadmin.pimpinan.store') }}">
                    @csrf
                    @if ($isEdit)
                        @method('PUT')
                    @endif

                    {{-- Nama --}}
                    <div class="f">
                        <label for="jeneng">Nama Lengkap & Gelar <span style="color:var(--err)">*</span></label>
                        <input id="jeneng" name="jeneng" type="text" required maxlength="150"
                            value="{{ old('jeneng', $item->jeneng) }}"
                            placeholder="Contoh: Dr. Bdn. Lusy Pratiwi, S.Tr.Keb., M.K.M." autocomplete="off">
                        @error('jeneng')
                            <small style="color:var(--err);">{{ $message }}</small>
                        @enderror
                    </div>

                    {{-- Jabatan --}}
                    <div class="f">
                        <label for="kalungguhan">Jabatan <span style="color:var(--err)">*</span></label>
                        <input id="kalungguhan" name="kalungguhan" type="text" required maxlength="150"
                            value="{{ old('kalungguhan', $item->kalungguhan) }}"
                            placeholder="Contoh: Direktur / Wakil Rektor I / Dekan">
                        @error('kalungguhan')
                            <small style="color:var(--err);">{{ $message }}</small>
                        @enderror
                    </div>

                    {{-- FOTO (Drag & Drop Preview) --}}
                    <div class="f">
                        <label for="foto">Foto Profil</label>
                        <div class="pim-upload" id="uploadArea">
                            <div class="pim-upload__preview">
                                {{-- Gunakan data attribute untuk menyimpan path foto lama --}}
                                <img id="fotoPreview"
                                    src="{{ $isEdit && $item->path_foto ? asset('storage/' . $item->path_foto) : '' }}"
                                    alt="Preview"
                                    style="{{ $isEdit && $item->path_foto ? 'display:block;' : 'display:none;' }}">

                                <div id="fotoPlaceholder" class="pim-upload__placeholder"
                                    style="{{ $isEdit && $item->path_foto ? 'display:none;' : 'display:grid;' }}">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                                        <polyline points="17 8 12 3 7 8" />
                                        <line x1="12" y1="3" x2="12" y2="15" />
                                    </svg>
                                    <span style="font-size:0.75rem; font-weight:600; margin-top:8px;">Pilih Foto</span>
                                </div>
                            </div>

                            <div class="pim-upload__info">
                                <strong style="display:block; font-size:0.88rem; margin-bottom:4px;">Unggah Foto
                                    Formal</strong>
                                <span
                                    style="color:var(--mut); font-size:0.78rem; line-height:1.4; display:block; margin-bottom:8px;">
                                    Klik atau seret gambar ke area ini. Format JPG/PNG maks 2MB. Rasio 1:1 disarankan.
                                </span>
                                <button type="button" class="btn gh" style="font-size:0.78rem; padding:6px 12px;"
                                    onclick="document.getElementById('foto').click()">Pilih dari Perangkat</button>

                                {{-- Input File disembunyikan --}}
                                <input id="foto" name="foto" type="file" accept="image/*"
                                    style="display:none;" data-has-old="{{ $isEdit && $item->path_foto ? '1' : '0' }}">
                            </div>
                        </div>
                        @error('foto')
                            <small style="color:var(--err);">{{ $message }}</small>
                        @enderror
                    </div>

                    {{-- Unen (RTE) --}}
                    @include('superadmin.konten.profil.pimpinan.partials.rte', [
                        'name' => 'unen',
                        'label' => 'Unen-unen / Sambutan',
                        'value' => old('unen', $item->unen),
                        'rows' => 5,
                        'placeholder' => 'Tulis sambutan singkat pimpinan…',
                        'hint' => 'Maksimal 2000 karakter.',
                    ])

                    {{-- Riwayat --}}
                    <div class="f">
                        <label for="riwayat">Riwayat Pendidikan / Karier</label>
                        <textarea id="riwayat" name="riwayat" rows="8" placeholder="Tuliskan riwayat secara kronologis…">{{ old('riwayat', $item->riwayat) }}</textarea>
                    </div>

                    {{-- Urutan --}}
                    <div class="f" style="max-width:240px;">
                        <label for="urutan">Urutan hierarki</label>
                        <input id="urutan" type="number" name="urutan" min="0" max="9999"
                            value="{{ old('urutan', $item->urutan ?? 0) }}">
                        <small style="color:var(--mut);">Semakin kecil angka, semakin atas posisinya.</small>
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
                    <div class="pim-form-actions">
                        <button type="submit" class="btn">
                            {{ $isEdit ? 'Simpan perubahan' : 'Simpan pimpinan' }}
                        </button>
                        <a href="{{ route('superadmin.pimpinan.index') }}" class="btn gh">Batal</a>
                    </div>
                </form>
            </div>

            {{-- ===== Sidebar Kanan ===== --}}
            <aside class="pim-form-aside">
                {{-- Ringkasan mode + progress --}}
                <div class="card pim-side-summary">
                    <div class="pim-side-summary__head">
                        <span class="pim-side-badge {{ $isEdit ? 'is-edit' : 'is-new' }}">
                            {{ $isEdit ? 'Mode Edit' : 'Pimpinan Baru' }}
                        </span>
                        <h3>{{ $isEdit ? 'Perbarui profil' : 'Buat profil' }}</h3>
                        <p>Lengkapi data inti. Checklist di bawah menunjukkan kelengkapan isian.</p>
                    </div>

                    <ul class="pim-checklist" id="pimChecklist">
                        <li data-check="jeneng"><span class="dot"></span> Nama & gelar</li>
                        <li data-check="kalungguhan"><span class="dot"></span> Jabatan</li>
                        <li data-check="foto"><span class="dot"></span> Foto profil</li>
                        <li data-check="unen"><span class="dot"></span> Sambutan / unen</li>
                        <li data-check="riwayat"><span class="dot"></span> Riwayat</li>
                    </ul>

                    <div class="pim-progress">
                        <div class="pim-progress__bar"><span id="pimProgressBar" style="width:0%"></span></div>
                        <small><span id="pimProgressText">0</span>% lengkap</small>
                    </div>
                </div>

                {{-- Status publikasi --}}
                <div class="card pim-side-pub">
                    <h3 style="margin-bottom:10px;">Status publikasi</h3>
                    <div class="pim-pub-row">
                        <span class="pim-pub-dot" id="pubDot"></span>
                        <strong id="pubLabel">Aktif</strong>
                    </div>
                    <p class="pim-pub-desc" id="pubDesc">
                        Profil akan tampil di halaman publik.
                    </p>
                </div>

                {{-- Panduan --}}
                <div class="card">
                    <h3 style="margin-bottom:10px;">Panduan</h3>
                    <ul class="pim-tips">
                        <li>Gunakan nama lengkap beserta gelar.</li>
                        <li>Foto formal dengan rasio 1:1 lebih rapi.</li>
                        <li>Isi sambutan maksimal 2-3 paragraf.</li>
                        <li>Urutan kecil (misal 1) = tampil paling atas.</li>
                    </ul>
                </div>
            </aside>
        </div>
    </div>

    @push('css')
        <link rel="stylesheet" href="{{ asset('admin/landing/profil/pimpinan/form.css') }}">
    @endpush

    @push('js')
        <script src="{{ asset('admin/landing/profil/pimpinan/form.js') }}" defer></script>
    @endpush
</x-dashboard.layout.app>
