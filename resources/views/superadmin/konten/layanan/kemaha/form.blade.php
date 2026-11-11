@php
    $isEdit = $item->exists;
    $pageTitle = $isEdit ? 'Edit Kemahasiswaan' : 'Tambah Kemahasiswaan';
    $meta = $item->data_tambahan ?? [];
@endphp

<x-dashboard.layout.app :title="$pageTitle" :page-title="$pageTitle">
    <div class="kmh-form-page">

        {{-- HERO --}}
        <div class="kmh-form-hero">
            <div>
                <div class="kmh-form-hero__crumb">
                    <a href="{{ route('superadmin.kemaha.index') }}">Kemahasiswaan</a>
                    <span>/</span>
                    <span class="is-current">{{ $isEdit ? 'Ubah' : 'Tambah' }}</span>
                </div>
                <h1 class="kmh-form-hero__title">{{ $pageTitle }}</h1>
                <p class="kmh-form-hero__desc">
                    Lengkapi informasi yang akan tampil di landing page. Kontak, lokasi, dan tautan
                    membantu mahasiswa menemukan layanan ini.
                </p>
            </div>
            <a href="{{ route('superadmin.kemaha.index') }}" class="btn gh kmh-form-hero__cta">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="19" y1="12" x2="5" y2="12"></line>
                    <polyline points="12 19 5 12 12 5"></polyline>
                </svg>
                Kembali
            </a>
        </div>

        {{-- ERROR --}}
        @if ($errors->any())
            <div class="al w" role="alert" style="margin-bottom:20px;">
                <ul style="margin:0;padding-left:18px;">
                    @foreach ($errors->all() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- LAYOUT: form kiri + sidebar kanan --}}
        <div class="kmh-form-layout">

            {{-- KOLOM KIRI --}}
            <div class="kmh-form-main">
                <div class="card" style="padding:24px;">
                    <h2 style="margin:0 0 6px;font-size:1.1rem;">Detail Konten</h2>
                    <p style="margin:0 0 24px;font-size:0.85rem;color:var(--mut);">
                        Tulis informasi yang relevan bagi mahasiswa dan calon mahasiswa.
                    </p>

                    <form id="kmhForm" method="POST"
                        action="{{ $isEdit ? route('superadmin.kemaha.update', $item) : route('superadmin.kemaha.store') }}">
                        @csrf
                        @if ($isEdit)
                            @method('PUT')
                        @endif

                        <div class="f">
                            <label for="judhul">Judul <span style="color:var(--err)">*</span></label>
                            <input id="judhul" name="judhul" type="text" required maxlength="150"
                                value="{{ old('judhul', $item->judhul) }}"
                                placeholder="Contoh: Unit Kegiatan Mahasiswa">
                        </div>

                        <div class="f">
                            <label for="isi">Deskripsi</label>
                            <textarea id="isi" name="isi" rows="8" placeholder="Penjelasan layanan atau kegiatan…">{{ old('isi', $item->isi) }}</textarea>
                        </div>

                        <div class="g g2" style="gap:16px;">
                            <div class="f" style="margin-bottom:0;">
                                <label for="kontak">Kontak / Telepon</label>
                                <input id="kontak" name="kontak" type="text" maxlength="100"
                                    value="{{ old('kontak', data_get($meta, 'kontak')) }}" placeholder="(021) 000-0000">
                            </div>
                            <div class="f" style="margin-bottom:0;">
                                <label for="email">Email</label>
                                <input id="email" name="email" type="email" maxlength="150"
                                    value="{{ old('email', data_get($meta, 'email')) }}"
                                    placeholder="kemahasiswaan@kampus.ac.id">
                            </div>
                        </div>

                        <div class="f" style="margin-top:16px;">
                            <label for="lokasi">Lokasi / Ruang</label>
                            <input id="lokasi" name="lokasi" type="text" maxlength="150"
                                value="{{ old('lokasi', data_get($meta, 'lokasi')) }}" placeholder="Gedung A lantai 2">
                        </div>

                        <div class="f">
                            <label for="url_portal">Tautan Terkait (opsional)</label>
                            <input id="url_portal" name="url_portal" type="url" maxlength="255"
                                value="{{ old('url_portal', data_get($meta, 'url_portal')) }}" placeholder="https://…">
                            <small style="color:var(--mut);display:block;margin-top:4px;">
                                Form pendaftaran UKM, portal mahasiswa, dll.
                            </small>
                            @error('url_portal')
                                <small style="color:var(--err);">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="f" style="max-width:240px;">
                            <label for="urutan">Urutan Tampil</label>
                            <input id="urutan" type="number" name="urutan" min="0" max="9999"
                                value="{{ old('urutan', $item->urutan ?? 0) }}">
                        </div>

                        <div class="f" style="margin-top:16px;">
                            <span class="f-label" style="display:block;margin-bottom:8px;">Publikasi</span>
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

                        <div
                            style="display:flex;gap:10px;margin-top:32px;padding-top:20px;border-top:1px solid var(--line);">
                            <button type="submit"
                                class="btn">{{ $isEdit ? 'Simpan Perubahan' : 'Simpan Konten' }}</button>
                            <a href="{{ route('superadmin.kemaha.index') }}" class="btn gh">Batal</a>
                        </div>
                    </form>
                </div>
            </div>

            {{-- KOLOM KANAN: SIDEBAR --}}
            <aside class="kmh-form-aside">

                {{-- Ringkasan --}}
                <div class="card kmh-side-card">
                    <div class="kmh-side-head">
                        <span class="kmh-side-badge {{ $isEdit ? 'is-edit' : 'is-new' }}">
                            {{ $isEdit ? 'Mode Edit' : 'Data Baru' }}
                        </span>
                        <h3 class="kmh-side-title">Ringkasan Pengisian</h3>
                        <p class="kmh-side-desc">
                            Lengkapi informasi dasar agar konten tampil optimal.
                        </p>
                    </div>

                    <ul class="kmh-checklist" id="kmhChecklist">
                        <li data-check="judhul"><span class="dot"></span> Judul</li>
                        <li data-check="isi"><span class="dot"></span> Deskripsi</li>
                        <li data-check="kontak"><span class="dot"></span> Kontak</li>
                        <li data-check="email"><span class="dot"></span> Email</li>
                        <li data-check="lokasi"><span class="dot"></span> Lokasi / ruang</li>
                        <li data-check="url_portal"><span class="dot"></span> Tautan terkait (opsional)</li>
                    </ul>

                    <div class="kmh-progress">
                        <div class="kmh-progress__bar">
                            <span id="kmhProgressBar" style="width:0%"></span>
                        </div>
                        <small id="kmhProgressText">0%</small>
                    </div>

                    @if ($isEdit)
                        <div class="kmh-kv"
                            style="margin-top:16px;padding-top:14px;border-top:1px solid var(--line);">
                            <span>Terakhir update</span>
                            <strong>{{ $item->updated_at->format('d M Y') }}</strong>
                        </div>
                    @endif
                </div>

                {{-- Status publikasi --}}
                <div class="card kmh-side-card">
                    <h3 class="kmh-side-title">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                            <circle cx="12" cy="12" r="3"></circle>
                        </svg>
                        Status Publikasi
                    </h3>
                    <div class="kmh-pub-row {{ old('urip', $item->urip ?? true) ? '' : 'is-off' }}" id="kmhPubRow">
                        <span class="kmh-pub-dot"></span>
                        <strong id="kmhPubLabel">
                            {{ old('urip', $item->urip ?? true) ? 'Tampil di publik' : 'Disembunyikan' }}
                        </strong>
                    </div>
                    <p class="kmh-side-desc" style="margin-top:8px;">
                        <span id="kmhPubDesc">
                            {{ old('urip', $item->urip ?? true)
                                ? 'Konten akan muncul di landing page kemahasiswaan.'
                                : 'Konten tersimpan namun tidak tampil di landing page.' }}
                        </span>
                    </p>
                </div>

                {{-- Kontak & portal preview --}}
                <div class="card kmh-side-card">
                    <h3 class="kmh-side-title">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path
                                d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z" />
                        </svg>
                        Kontak & Portal
                    </h3>
                    <div class="kmh-contact-list">
                        <div class="kmh-contact-item">
                            <span class="kmh-contact-label">Telepon</span>
                            <code id="kmhPreviewKontak">—</code>
                        </div>
                        <div class="kmh-contact-item">
                            <span class="kmh-contact-label">Email</span>
                            <code id="kmhPreviewEmail">—</code>
                        </div>
                        <div class="kmh-contact-item">
                            <span class="kmh-contact-label">Lokasi</span>
                            <code id="kmhPreviewLokasi">—</code>
                        </div>
                        <div class="kmh-contact-item">
                            <span class="kmh-contact-label">Portal</span>
                            <code id="kmhPreviewUrl">—</code>
                        </div>
                    </div>
                </div>

                {{-- Tips --}}
                <div class="card kmh-side-card">
                    <h3 class="kmh-side-title">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M9 18h6"></path>
                            <path d="M10 22h4"></path>
                            <path
                                d="M15.09 14c.18-.98.65-1.74 1.41-2.5A4.65 4.65 0 0 0 18 8 6 6 0 0 0 6 8c0 1.45.55 2.67 1.5 3.5.76.76 1.23 1.52 1.41 2.5">
                            </path>
                        </svg>
                        Tips Pengisian
                    </h3>
                    <ul class="kmh-tips">
                        <li>Satu blok = satu topik (UKM, beasiswa, layanan).</li>
                        <li>Isi kontak & email agar mudah dihubungi.</li>
                        <li>URL wajib diawali <code>https://</code>.</li>
                        <li>Gunakan urutan kecil untuk tampil lebih awal.</li>
                    </ul>
                </div>

            </aside>
        </div>
    </div>

    @push('css')
        <link rel="stylesheet" href="{{ asset('admin/landing/layanan/kemaha/form.css') }}">
    @endpush

    @push('js')
        <script src="{{ asset('admin/landing/layanan/kemaha/form.js') }}" defer></script>
    @endpush
</x-dashboard.layout.app>
