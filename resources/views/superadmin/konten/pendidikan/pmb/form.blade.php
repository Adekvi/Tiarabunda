@php
    $isEdit = $item->exists;
    $pageTitle = $isEdit ? 'Edit Jalur PMB' : 'Tambah Jalur PMB';
    $syaratText = old('syarat_text', implode("\n", $item->syarat ?? []));
    $langkahText = old('langkah_text', implode("\n", $item->langkah ?? []));
    $bukak = old('wektu_bukak', optional($item->wektu_bukak)->format('Y-m-d'));
    $tutup = old('wektu_tutup', optional($item->wektu_tutup)->format('Y-m-d'));
@endphp

<x-dashboard.layout.app :title="$pageTitle" :page-title="$pageTitle">
    <div class="pmb-form-page">
        {{-- ===== Header ===== --}}
        <div class="card pmb-form-hero">
            <div class="pmb-form-hero__main">
                <p class="pmb-form-hero__crumb">
                    <a href="{{ route('superadmin.pmb.index') }}">PMB</a>
                    <span aria-hidden="true">/</span>
                    <span class="is-current">{{ $isEdit ? 'Ubah' : 'Tambah' }}</span>
                </p>
                <h2 class="pmb-form-hero__title">{{ $pageTitle }}</h2>
                <p class="pmb-form-hero__desc">
                    Lengkapi informasi jalur agar calon mahasiswa memahami cara dan jadwal mendaftar.
                </p>
            </div>
            <a href="{{ route('superadmin.pmb.index') }}" class="btn gh">← Kembali</a>
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

        <div class="pmb-form-layout">
            {{-- ===== Kolom Form ===== --}}
            <div class="card pmb-form-card" style="min-width:0;">
                <h2 style="margin-bottom:6px;">Detail jalur</h2>
                <p style="margin:0 0 22px;font-size:0.85rem;color:var(--mut);">
                    Kolom bertanda <span style="color:var(--err)">*</span> wajib diisi.
                </p>

                <form method="POST" id="pmbForm"
                    action="{{ $isEdit ? route('superadmin.pmb.update', $item) : route('superadmin.pmb.store') }}">
                    @csrf
                    @if ($isEdit)
                        @method('PUT')
                    @endif

                    {{-- Nama --}}
                    <div class="f">
                        <label for="jeneng">Nama jalur <span style="color:var(--err)">*</span></label>
                        <input id="jeneng" name="jeneng" type="text" required maxlength="150"
                            value="{{ old('jeneng', $item->jeneng) }}"
                            placeholder="Contoh: Jalur Reguler / Prestasi / KIP" autocomplete="off">
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
                                    <button type="button" id="tandhaLock" class="pmb-link-btn">ubah manual</button>
                                </span>
                            @endif
                        </label>
                        <input id="tandha" name="tandha" type="text" maxlength="150"
                            value="{{ old('tandha', $item->tandha) }}" placeholder="Otomatis dari nama jalur" readonly
                            data-edit="{{ $isEdit ? '1' : '0' }}">
                        <small style="color:var(--mut);font-size:.78rem;display:block;margin-top:6px;">
                            Dibuat otomatis dari nama jalur. Hanya huruf, angka, dan tanda hubung.
                        </small>
                        @error('tandha')
                            <small style="color:var(--err);">{{ $message }}</small>
                        @enderror
                    </div>

                    {{-- Deskripsi --}}
                    @include('superadmin.konten.pendidikan.pmb.partials.form.rte', [
                        'name' => 'katrangan',
                        'label' => 'Deskripsi singkat',
                        'value' => old('katrangan', $item->katrangan),
                        'rows' => 6,
                        'placeholder' => 'Penjelasan jalur untuk calon mahasiswa…',
                    ])

                    {{-- Tanggal --}}
                    <div class="row-2">
                        <div class="f" style="margin-bottom:0;">
                            <label for="wektu_bukak">Tanggal dibuka</label>
                            <input id="wektu_bukak" type="date" name="wektu_bukak" value="{{ $bukak }}">
                        </div>
                        <div class="f" style="margin-bottom:0;">
                            <label for="wektu_tutup">Tanggal ditutup</label>
                            <input id="wektu_tutup" type="date" name="wektu_tutup" value="{{ $tutup }}">
                            @error('wektu_tutup')
                                <small style="color:var(--err);">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    {{-- Persyaratan --}}
                    @include('superadmin.konten.pendidikan.pmb.partials.form.rte', [
                        'name' => 'syarat_text',
                        'label' => 'Persyaratan',
                        'value' => $syaratText,
                        'rows' => 6,
                        'placeholder' => 'Tulis satu syarat per baris (bullet list)...',
                        'hint' => 'Gunakan daftar poin untuk setiap syarat.',
                    ])

                    {{-- Langkah --}}
                    @include('superadmin.konten.pendidikan.pmb.partials.form.rte', [
                        'name' => 'langkah_text',
                        'label' => 'Alur / langkah pendaftaran',
                        'value' => $langkahText,
                        'rows' => 6,
                        'placeholder' => 'Tulis satu langkah per baris (numbered list)...',
                        'hint' => 'Gunakan daftar bernomor untuk urutan langkah.',
                    ])

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
                    <div class="pmb-form-actions">
                        <button type="submit" class="btn">
                            {{ $isEdit ? 'Simpan perubahan' : 'Simpan jalur' }}
                        </button>
                        <a href="{{ route('superadmin.pmb.index') }}" class="btn gh">Batal</a>
                    </div>
                </form>
            </div>

            {{-- ===== Sidebar Kanan ===== --}}
            <aside class="pmb-form-aside">
                {{-- Ringkasan mode + progress --}}
                <div class="card pmb-side-summary">
                    <div class="pmb-side-summary__head">
                        <span class="pmb-side-badge {{ $isEdit ? 'is-edit' : 'is-new' }}">
                            {{ $isEdit ? 'Mode Edit' : 'Jalur Baru' }}
                        </span>
                        <h3>{{ $isEdit ? 'Perbarui jalur' : 'Buat jalur' }}</h3>
                        <p>Lengkapi data inti. Checklist di bawah menunjukkan kelengkapan isian.</p>
                    </div>

                    <ul class="pmb-checklist" id="pmbChecklist">
                        <li data-check="jeneng"><span class="dot"></span> Nama jalur</li>
                        <li data-check="katrangan"><span class="dot"></span> Deskripsi singkat</li>
                        <li data-check="wektu_bukak"><span class="dot"></span> Tanggal dibuka</li>
                        <li data-check="wektu_tutup"><span class="dot"></span> Tanggal ditutup</li>
                        <li data-check="syarat_text"><span class="dot"></span> Persyaratan</li>
                        <li data-check="langkah_text"><span class="dot"></span> Alur pendaftaran</li>
                    </ul>

                    <div class="pmb-progress">
                        <div class="pmb-progress__bar"><span id="pmbProgressBar" style="width:0%"></span></div>
                        <small><span id="pmbProgressText">0</span>% lengkap</small>
                    </div>
                </div>

                {{-- Jadwal & status --}}
                <div class="card pmb-side-jadwal" id="jadwalCard" hidden>
                    <h3 style="margin-bottom:12px;">Ringkasan jadwal</h3>
                    <div class="pmb-kv">
                        <span>Dibuka</span>
                        <strong id="jadwalBukak">—</strong>
                    </div>
                    <div class="pmb-kv">
                        <span>Ditutup</span>
                        <strong id="jadwalTutup">—</strong>
                    </div>
                    <div class="pmb-kv">
                        <span>Durasi</span>
                        <strong id="jadwalDurasi">—</strong>
                    </div>
                    <div class="pmb-jadwal-status" id="jadwalStatus">
                        <i class="dot"></i><span>Belum dijadwalkan</span>
                    </div>
                </div>

                {{-- Publikasi --}}
                <div class="card pmb-side-pub">
                    <h3 style="margin-bottom:10px;">Status publikasi</h3>
                    <div class="pmb-pub-row">
                        <span class="pmb-pub-dot" id="pubDot"></span>
                        <strong id="pubLabel">Aktif</strong>
                    </div>
                    <p class="pmb-pub-desc" id="pubDesc">
                        Jalur akan tampil di halaman PMB situs publik.
                    </p>
                </div>

                {{-- Panduan --}}
                <div class="card">
                    <h3 style="margin-bottom:10px;">Panduan</h3>
                    <ul class="pmb-tips">
                        <li>Nama jalur harus jelas bagi calon mahasiswa.</li>
                        <li>Tanggal buka–tutup dipakai untuk status “Sedang dibuka”.</li>
                        <li>Syarat &amp; langkah: satu poin per baris.</li>
                        <li>Slug dibuat otomatis dan bersifat <em>readonly</em>.</li>
                    </ul>
                </div>

                {{-- Preview slug --}}
                <div class="card pmb-side-slug">
                    <h3 style="margin-bottom:10px;">Pratinjau URL</h3>
                    <code id="slugPreview">/pmb/…</code>
                </div>
            </aside>
        </div>
    </div>

    @push('css')
        <link rel="stylesheet" href="{{ asset('admin/landing/pendidikan/pmb/form.css') }}">
    @endpush

    @push('js')
        <script src="{{ asset('admin/landing/pendidikan/pmb/form.js') }}" defer></script>
    @endpush
</x-dashboard.layout.app>
