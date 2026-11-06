@php
    $isEdit = $item->exists;
    $pageTitle = $isEdit ? 'Edit Program Studi' : 'Tambah Program Studi';
@endphp

<x-dashboard.layout.app :title="$pageTitle" :page-title="$pageTitle">
    <div class="g">
        {{-- ===== Header ===== --}}
        <div class="card" style="padding:14px 22px;">
            <div class="ch2" style="margin-bottom:0;">
                <div>
                    <p style="margin:0 0 2px;font-size:0.75rem;color:var(--mut);">
                        <a href="{{ route('superadmin.prodi.index') }}" style="color:var(--pri);font-weight:600;">Program
                            Studi</a>
                        <span style="opacity:.5;"> / </span>{{ $isEdit ? 'Ubah' : 'Tambah' }}
                    </p>
                    <h2 style="margin:0;font-size:1.15rem;">{{ $pageTitle }}</h2>
                </div>
                <a href="{{ route('superadmin.prodi.index') }}" class="btn gh">← Kembali</a>
            </div>
        </div>

        @if ($errors->any())
            <div class="al w" role="alert">
                <ul style="margin:0;padding-left:18px;">
                    @foreach ($errors->all() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="g g2" style="align-items:start;gap:20px;">
            {{-- ===== Kolom Form ===== --}}
            <div class="card" style="min-width:0;">
                <h2 style="margin-bottom:6px;">Detail program studi</h2>
                <p style="margin:0 0 22px;font-size:0.85rem;color:var(--mut);">
                    Hubungkan prodi ke fakultas dan lengkapi jenjang serta akreditasi.
                </p>

                <form method="POST" id="prodiForm"
                    action="{{ $isEdit ? route('superadmin.prodi.update', $item) : route('superadmin.prodi.store') }}">
                    @csrf
                    @if ($isEdit)
                        @method('PUT')
                    @endif

                    {{-- Fakultas --}}
                    <div class="f">
                        <label for="fakultas_id">Fakultas <span style="color:var(--err)">*</span></label>
                        <select id="fakultas_id" name="fakultas_id" required>
                            <option value="">— Pilih fakultas —</option>
                            @foreach ($fakultasList as $f)
                                <option value="{{ $f->id }}" @selected((string) old('fakultas_id', $item->fakultas_id) === (string) $f->id)>
                                    {{ $f->jeneng }}
                                </option>
                            @endforeach
                        </select>
                        @error('fakultas_id')
                            <small style="color:var(--err);">{{ $message }}</small>
                        @enderror
                    </div>

                    {{-- Nama Prodi --}}
                    <div class="f">
                        <label for="jeneng">Nama program studi <span style="color:var(--err)">*</span></label>
                        <input id="jeneng" name="jeneng" type="text" required maxlength="150"
                            value="{{ old('jeneng', $item->jeneng) }}" placeholder="Contoh: D3 Keperawatan"
                            autocomplete="off">
                        @error('jeneng')
                            <small style="color:var(--err);">{{ $message }}</small>
                        @enderror
                    </div>

                    {{-- Jenjang & Akreditasi (sejajar) --}}
                    <div class="row-2" style="align-items: start;">
                        <div class="f">
                            <label for="undhak">Jenjang <span style="color:var(--err)">*</span></label>
                            <select id="undhak" name="undhak" required>
                                <option value="">— Pilih Jenjang —</option>
                                @foreach (['D3', 'D4', 'S1'] as $j)
                                    <option value="{{ $j }}" @selected(old('undhak', $item->undhak) === $j)>
                                        {{ $j }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="f">
                            <label for="akreditasi_id">Sertifikat Akreditasi</label>
                            <select id="akreditasi_id" name="akreditasi_id">
                                <option value="">— Pilih Sertifikat —</option>
                                @foreach ($akreditasiList as $akr)
                                    <option value="{{ $akr->id }}" @selected((string) old('akreditasi_id', $item->akreditasi_id) === (string) $akr->id)>
                                        {{ $akr->status_akred }} - {{ $akr->jeneng }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="f-hint" style="margin-top:8px;">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <line x1="12" y1="16" x2="12" y2="12"></line>
                                    <line x1="12" y1="8" x2="12.01" y2="8"></line>
                                </svg>
                                Belum ada datanya?
                                <a href="{{ route('superadmin.akreditasi.create') }}" target="_blank">Tambah di menu
                                    Akreditasi</a>.
                            </div>
                            @error('akreditasi_id')
                                <small style="color:var(--err);">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    {{-- Slug (readonly otomatis) --}}
                    <div class="f" style="margin-top:20px;">
                        <label for="tandha">
                            Slug URL
                            @if ($isEdit)
                                <span style="font-weight:400;color:var(--mut);font-size:.78rem;">
                                    &middot;
                                    <button type="button" id="tandhaLock"
                                        style="background:none;border:0;padding:0;cursor:pointer;color:var(--pri);font-weight:600;font-size:.78rem;">
                                        ubah manual
                                    </button>
                                </span>
                            @endif
                        </label>
                        <input id="tandha" name="tandha" type="text" maxlength="150"
                            value="{{ old('tandha', $item->tandha) }}" placeholder="Otomatis dari nama prodi" readonly
                            data-auto="1">
                        <small style="color:var(--mut);font-size:.78rem;display:block;margin-top:6px;">
                            Dibuat otomatis dari nama program studi. Hanya huruf, angka, dan tanda hubung.
                        </small>
                        @error('tandha')
                            <small style="color:var(--err);">{{ $message }}</small>
                        @enderror
                    </div>

                    {{-- Deskripsi --}}
                    <div class="f">
                        <label for="katrangan">Deskripsi singkat</label>
                        <textarea id="katrangan" name="katrangan" rows="5" placeholder="Ringkasan prodi…">{{ old('katrangan', $item->katrangan) }}</textarea>
                    </div>

                    {{-- Urutan --}}
                    <div class="f" style="max-width:260px;">
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
                    <div
                        style="display:flex;gap:10px;margin-top:28px;padding-top:22px;border-top:1px solid var(--line);">
                        <button type="submit" class="btn">{{ $isEdit ? 'Simpan perubahan' : 'Simpan' }}</button>
                        <a href="{{ route('superadmin.prodi.index') }}" class="btn gh">Batal</a>
                    </div>
                </form>
            </div>

            {{-- ===== Sidebar Kanan ===== --}}
            <aside class="g" style="gap:16px;">
                {{-- Ringkasan Mode --}}
                <div class="card side-summary">
                    <div class="side-head">
                        <span class="side-badge {{ $isEdit ? 'is-edit' : 'is-new' }}">
                            {{ $isEdit ? 'Mode Edit' : 'Prodi Baru' }}
                        </span>
                        <h3 style="margin:0;">{{ $isEdit ? 'Perbarui data' : 'Buat prodi' }}</h3>
                        <p>Lengkapi data inti di bawah. Kolom bertanda <span style="color:var(--err)">*</span> wajib
                            diisi.</p>
                    </div>

                    <ul class="side-checklist" id="checklist">
                        <li data-check="fakultas_id">
                            <span class="dot"></span> Fakultas dipilih
                        </li>
                        <li data-check="jeneng">
                            <span class="dot"></span> Nama program studi
                        </li>
                        <li data-check="undhak">
                            <span class="dot"></span> Jenjang ditentukan
                        </li>
                        <li data-check="akred">
                            <span class="dot"></span> Akreditasi (opsional)
                        </li>
                        <li data-check="katrangan">
                            <span class="dot"></span> Deskripsi singkat
                        </li>
                    </ul>

                    <div class="side-progress">
                        <div class="side-progress-bar"><span id="progressBar" style="width:0%"></span></div>
                        <small><span id="progressText">0</span>% lengkap</small>
                    </div>
                </div>

                {{-- Info Fakultas terpilih --}}
                <div class="card side-fakultas" id="fakultasCard" hidden>
                    <h3 style="margin-bottom:10px;">Fakultas terpilih</h3>
                    <div class="side-kv">
                        <span>Nama</span>
                        <strong id="fakNama">—</strong>
                    </div>
                </div>

                {{-- Panduan --}}
                <div class="card">
                    <h3 style="margin-bottom:10px;">Panduan</h3>
                    <ul class="side-tips">
                        <li>Pilih fakultas yang sudah aktif.</li>
                        <li>Jenjang wajib: D3, D4, atau S1.</li>
                        <li>Slug dibuat otomatis dari nama prodi dan bersifat <em>readonly</em>.</li>
                        <li>Status akreditasi bisa dilengkapi nanti.</li>
                    </ul>
                </div>

                {{-- Live preview slug --}}
                <div class="card side-preview">
                    <h3 style="margin-bottom:10px;">Pratinjau URL</h3>
                    <code id="slugPreview">/prodi/…</code>
                </div>
            </aside>
        </div>
    </div>

    @push('css')
        <link rel="stylesheet" href="{{ asset('admin/landing/pendidikan/prodi/form.css') }}">
    @endpush

    @push('js')
        <script src="{{ asset('admin/landing/pendidikan/prodi/form.js') }}" defer></script>
    @endpush
</x-dashboard.layout.app>
