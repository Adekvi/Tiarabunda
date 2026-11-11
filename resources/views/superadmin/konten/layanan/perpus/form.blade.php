@php
    $isEdit = $item->exists;
    $pageTitle = $isEdit ? 'Edit Perpustakaan' : 'Tambah Perpustakaan';
    $meta = $item->data_tambahan ?? [];
@endphp

<x-dashboard.layout.app :title="$pageTitle" :page-title="$pageTitle">
    <div class="prp-page">

        {{-- 1. HERO SECTION (Menggantikan Card Breadcrumb) --}}
        <div class="card prp-hero" style="margin-bottom: 20px;">
            <div>
                <div class="prp-hero__crumb">
                    <a href="{{ route('superadmin.perpus.index') }}"
                        style="color:var(--mut); text-decoration:none;">Perpustakaan</a>
                    <span>/</span>
                    <span class="is-current">{{ $isEdit ? 'Ubah' : 'Tambah' }}</span>
                </div>
                <h1 class="prp-hero__title">{{ $pageTitle }}</h1>
                <p class="prp-hero__desc">
                    Isi informasi yang akan ditampilkan di landing page. Tautkan portal perpustakaan jika sudah
                    tersedia.
                </p>
            </div>
            <a href="{{ route('superadmin.perpus.index') }}" class="btn gh prp-hero__cta">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="19" y1="12" x2="5" y2="12"></line>
                    <polyline points="12 19 5 12 12 5"></polyline>
                </svg>
                Kembali
            </a>
        </div>

        @if ($errors->any())
            <div class="al w" role="alert" style="margin-bottom: 20px;">
                <ul style="margin:0;padding-left:18px;">
                    @foreach ($errors->all() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- 2. LAYOUT GRID (Form + Sidebar) --}}
        <div class="prp-layout">

            {{-- KOLOM KIRI: FORM UTAMA --}}
            <div class="prp-main">
                <div class="card" style="padding: 24px;">
                    <h2 style="margin:0 0 6px; font-size:1.1rem;">Detail Konten</h2>
                    <p style="margin:0 0 24px;font-size:0.85rem;color:var(--mut);">
                        Lengkapi data di bawah ini dengan benar.
                    </p>

                    <form method="POST"
                        action="{{ $isEdit ? route('superadmin.perpus.update', $item) : route('superadmin.perpus.store') }}">
                        @csrf
                        @if ($isEdit)
                            @method('PUT')
                        @endif

                        <div class="f">
                            <label for="judhul">Judul <span style="color:var(--err)">*</span></label>
                            <input id="judhul" name="judhul" type="text" required maxlength="150"
                                value="{{ old('judhul', $item->judhul) }}"
                                placeholder="Contoh: Perpustakaan Universitas Tiara Bunda">
                        </div>

                        <div class="f">
                            <label for="isi">Deskripsi</label>
                            <textarea id="isi" name="isi" rows="6" placeholder="Profil singkat, koleksi, fasilitas…">{{ old('isi', $item->isi) }}</textarea>
                        </div>

                        <div class="f">
                            <label for="url_portal">URL Portal Perpustakaan</label>
                            <input id="url_portal" name="url_portal" type="url" maxlength="255"
                                value="{{ old('url_portal', data_get($meta, 'url_portal')) }}"
                                placeholder="https://perpus.contoh.ac.id">
                            <small style="color:var(--mut); display:block; margin-top:4px;">
                                Domain/sistem perpus sendiri (OPAC, e-library). Opsional.
                            </small>
                            @error('url_portal')
                                <small style="color:var(--err);">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="g g2" style="gap:16px;">
                            <div class="f" style="margin-bottom:0;">
                                <label for="jam_layanan">Jam Layanan</label>
                                <input id="jam_layanan" name="jam_layanan" type="text" maxlength="150"
                                    value="{{ old('jam_layanan', data_get($meta, 'jam_layanan')) }}"
                                    placeholder="Senin–Jumat 08.00–16.00">
                            </div>
                            <div class="f" style="margin-bottom:0;">
                                <label for="kontak">Kontak / Telepon</label>
                                <input id="kontak" name="kontak" type="text" maxlength="100"
                                    value="{{ old('kontak', data_get($meta, 'kontak')) }}"
                                    placeholder="(021) 000-0000">
                            </div>
                        </div>

                        <div class="f">
                            <label for="email">Email</label>
                            <input id="email" name="email" type="email" maxlength="150"
                                value="{{ old('email', data_get($meta, 'email')) }}" placeholder="perpus@kampus.ac.id">
                        </div>

                        <div class="g g2" style="gap:16px;">
                            <div class="f" style="margin-bottom:0;">
                                <label for="urutan">Urutan Tampil</label>
                                <input id="urutan" type="number" name="urutan" min="0" max="9999"
                                    value="{{ old('urutan', $item->urutan ?? 0) }}">
                            </div>
                        </div>

                        <div class="f" style="margin-top: 16px;">
                            <span class="f-label" style="display:block; margin-bottom:8px;">Publikasi</span>
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
                            <a href="{{ route('superadmin.perpus.index') }}" class="btn gh">Batal</a>
                        </div>
                    </form>
                </div>
            </div>

            {{-- KOLOM KANAN: SIDEBAR --}}
            {{-- KOLOM KANAN: SIDEBAR --}}
            <div class="prp-aside">

                {{-- Card: Status & Ringkasan --}}
                <div class="card prp-side-card">
                    <div class="prp-side-head">
                        <span class="prp-side-badge {{ $isEdit ? 'is-edit' : 'is-new' }}">
                            {{ $isEdit ? 'Mode Edit' : 'Data Baru' }}
                        </span>
                        <h3 class="prp-side-title">
                            {{ $isEdit ? 'Ringkasan Data' : 'Ringkasan Pengisian' }}
                        </h3>
                        <p class="prp-side-desc">
                            {{ $isEdit
                                ? 'Periksa kembali data sebelum menyimpan perubahan.'
                                : 'Lengkapi informasi dasar agar konten tampil optimal di landing page.' }}
                        </p>
                    </div>

                    <ul class="prp-checklist" id="prpChecklist">
                        <li data-check="judhul">
                            <span class="dot"></span> Judul perpustakaan
                        </li>
                        <li data-check="isi">
                            <span class="dot"></span> Deskripsi singkat
                        </li>
                        <li data-check="url_portal">
                            <span class="dot"></span> URL portal (opsional)
                        </li>
                        <li data-check="kontak">
                            <span class="dot"></span> Kontak / jam layanan
                        </li>
                    </ul>

                    <div class="prp-progress">
                        <div class="prp-progress__bar">
                            <span id="prpProgressBar" style="width:0%"></span>
                        </div>
                        <small id="prpProgressText">0%</small>
                    </div>

                    @if ($isEdit)
                        <div class="prp-kv"
                            style="margin-top:16px;padding-top:14px;border-top:1px solid var(--line);">
                            <span>Terakhir update</span>
                            <strong>{{ $item->updated_at->format('d M Y') }}</strong>
                        </div>
                    @endif
                </div>

                {{-- Card: Status Publikasi --}}
                <div class="card prp-side-card">
                    <h3 class="prp-side-title">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                            <circle cx="12" cy="12" r="3"></circle>
                        </svg>
                        Status Publikasi
                    </h3>
                    <div class="prp-pub-row {{ old('urip', $item->urip ?? true) ? '' : 'is-off' }}" id="prpPubRow">
                        <span class="prp-pub-dot"></span>
                        <strong
                            id="prpPubLabel">{{ old('urip', $item->urip ?? true) ? 'Tampil di publik' : 'Disembunyikan' }}</strong>
                    </div>
                    <p class="prp-side-desc" style="margin-top:8px;">
                        <span id="prpPubDesc">
                            {{ old('urip', $item->urip ?? true)
                                ? 'Konten akan muncul di landing page perpustakaan.'
                                : 'Konten tersimpan namun tidak tampil di landing page.' }}
                        </span>
                    </p>
                </div>

                {{-- Card: Portal Perpustakaan --}}
                <div class="card prp-side-card">
                    <h3 class="prp-side-title">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"></path>
                            <path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"></path>
                        </svg>
                        Portal Perpustakaan
                    </h3>
                    <p class="prp-side-desc">
                        Jika perpustakaan memiliki sistem mandiri (OPAC/e-library), masukkan URL lengkapnya.
                        Tombol <em>"Kunjungi Portal"</em> di halaman publik akan mengarah ke tautan tersebut.
                    </p>
                    <div class="prp-portal-preview" id="prpPortalPreview">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"></circle>
                            <line x1="2" y1="12" x2="22" y2="12"></line>
                            <path
                                d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z">
                            </path>
                        </svg>
                        <code id="prpPortalUrl">Belum diisi</code>
                    </div>
                </div>

                {{-- Card: Tips --}}
                <div class="card prp-side-card">
                    <h3 class="prp-side-title">
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
                    <ul class="prp-tips">
                        <li>Gunakan judul yang jelas dan singkat.</li>
                        <li>Deskripsi bisa berisi profil, koleksi unggulan, atau fasilitas.</li>
                        <li>Pastikan format URL diawali dengan <code>https://</code>.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    @push('css')
        <link rel="stylesheet" href="{{ asset('admin/landing/layanan/perpus/form.css') }}">
    @endpush

    @push('js')
        <script src="{{ asset('admin/landing/layanan/perpus/form.js') }}" defer></script>
    @endpush

</x-dashboard.layout.app>
