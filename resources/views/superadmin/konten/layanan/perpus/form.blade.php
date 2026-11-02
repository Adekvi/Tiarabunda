@php
    $isEdit = $item->exists;
    $pageTitle = $isEdit ? 'Edit Perpustakaan' : 'Tambah Perpustakaan';
    $meta = $item->data_tambahan ?? [];
@endphp

<x-dashboard.layout.app :title="$pageTitle" :page-title="$pageTitle">
    <div class="g">
        <div class="card" style="padding:14px 22px;">
            <div class="ch2" style="margin-bottom:0;">
                <div>
                    <p style="margin:0 0 2px;font-size:0.75rem;color:var(--mut);">
                        <a href="{{ route('superadmin.perpus.index') }}"
                            style="color:var(--pri);font-weight:600;">Perpustakaan</a>
                        <span style="opacity:.5;"> / </span>{{ $isEdit ? 'Ubah' : 'Tambah' }}
                    </p>
                    <h2 style="margin:0;font-size:1.15rem;">{{ $pageTitle }}</h2>
                </div>
                <a href="{{ route('superadmin.perpus.index') }}" class="btn gh">← Kembali</a>
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

        <div class="g g2" style="align-items:start;">
            <div class="card" style="min-width:0;">
                <h2 style="margin-bottom:6px;">Detail konten</h2>
                <p style="margin:0 0 20px;font-size:0.85rem;color:var(--mut);">
                    Isi informasi yang ditampilkan di landing. Tautkan portal perpustakaan jika sudah tersedia.
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
                        <textarea id="isi" name="isi" rows="8" placeholder="Profil singkat, koleksi, fasilitas…">{{ old('isi', $item->isi) }}</textarea>
                    </div>

                    <div class="f">
                        <label for="url_portal">URL portal perpustakaan</label>
                        <input id="url_portal" name="url_portal" type="url" maxlength="255"
                            value="{{ old('url_portal', data_get($meta, 'url_portal')) }}"
                            placeholder="https://perpus.contoh.ac.id">
                        <small style="color:var(--mut);">Domain/sistem perpus sendiri (OPAC, e-library).
                            Opsional.</small>
                        @error('url_portal')
                            <small style="color:var(--err);">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="g g2" style="gap:16px;">
                        <div class="f" style="margin-bottom:0;">
                            <label for="jam_layanan">Jam layanan</label>
                            <input id="jam_layanan" name="jam_layanan" type="text" maxlength="150"
                                value="{{ old('jam_layanan', data_get($meta, 'jam_layanan')) }}"
                                placeholder="Senin–Jumat 08.00–16.00">
                        </div>
                        <div class="f" style="margin-bottom:0;">
                            <label for="kontak">Kontak / telepon</label>
                            <input id="kontak" name="kontak" type="text" maxlength="100"
                                value="{{ old('kontak', data_get($meta, 'kontak')) }}" placeholder="(021) 000-0000">
                        </div>
                    </div>

                    <div class="f">
                        <label for="email">Email</label>
                        <input id="email" name="email" type="email" maxlength="150"
                            value="{{ old('email', data_get($meta, 'email')) }}" placeholder="perpus@kampus.ac.id">
                    </div>

                    <div class="f" style="max-width:240px;">
                        <label for="urutan">Urutan tampil</label>
                        <input id="urutan" type="number" name="urutan" min="0" max="9999"
                            value="{{ old('urutan', $item->urutan ?? 0) }}">
                    </div>

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

                    <div
                        style="display:flex;gap:10px;margin-top:24px;padding-top:20px;border-top:1px solid var(--line);">
                        <button type="submit" class="btn">{{ $isEdit ? 'Simpan perubahan' : 'Simpan' }}</button>
                        <a href="{{ route('superadmin.perpus.index') }}" class="btn gh">Batal</a>
                    </div>
                </form>
            </div>

            <div class="g" style="gap:16px;">
                <div class="card">
                    <h3 style="margin-bottom:10px;">Portal perpustakaan</h3>
                    <p style="margin:0;font-size:0.88rem;color:var(--mut);line-height:1.65;">
                        Jika nanti ada domain khusus (mis. <code>https://perpus.tiarabunda.ac.id</code>),
                        isi di kolom URL portal. Tombol “Kunjungi portal” di landing mengarah ke sana.
                    </p>
                </div>
                <div class="card">
                    <h3 style="margin-bottom:10px;">Status form</h3>
                    <div style="display:flex;justify-content:space-between;font-size:0.88rem;">
                        <span style="color:var(--mut);">Mode</span>
                        <strong>{{ $isEdit ? 'Edit' : 'Buat baru' }}</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('css')
        <style>
            /* ---------- Switch publikasi ---------- */
            .f .f-label {
                font-weight: 600;
                font-size: .85rem;
            }

            .f label.sw {
                position: relative;
                display: flex;
                align-items: center;
                gap: 14px;
                padding: 14px 16px;
                border: 1px solid var(--line);
                border-radius: 12px;
                background: var(--bg);
                font-weight: 400;
                font-size: .9rem;
                cursor: pointer;
                transition: border-color .2s, background .2s;
            }

            .f label.sw:hover {
                border-color: var(--pri);
            }

            /* sembunyikan checkbox asli (lawan aturan .f input) */
            .f .sw .sw-in {
                position: absolute;
                width: 1px;
                height: 1px;
                padding: 0;
                border: 0;
                opacity: 0;
                pointer-events: none;
            }

            .sw-track {
                position: relative;
                flex: none;
                width: 44px;
                height: 24px;
                border-radius: 99px;
                background: var(--line);
                transition: background .25s;
            }

            .sw-track::after {
                content: '';
                position: absolute;
                top: 3px;
                left: 3px;
                width: 18px;
                height: 18px;
                border-radius: 50%;
                background: #fff;
                box-shadow: 0 1px 3px rgba(16, 24, 40, .3);
                transition: transform .25s var(--ease);
            }

            .sw-in:checked~.sw-track {
                background: var(--ok);
            }

            .sw-in:checked~.sw-track::after {
                transform: translateX(20px);
            }

            .sw-in:focus-visible~.sw-track {
                outline: 2px solid var(--pri);
                outline-offset: 2px;
            }

            .sw-text {
                flex: 1;
                min-width: 0;
                line-height: 1.4;
            }

            .sw-text strong {
                display: block;
                color: var(--ink);
            }

            .sw-text small {
                color: var(--mut);
                font-size: .8rem;
            }

            .sw-state {
                flex: none;
                padding: 2px 10px;
                border-radius: 99px;
                font-size: .75rem;
                font-weight: 700;
            }

            .sw-state-on {
                background: var(--ok-t);
                color: var(--ok);
                display: none;
            }

            .sw-state-off {
                background: var(--err-t);
                color: var(--err);
            }

            .sw-in:checked~.sw-state-on {
                display: inline-block;
            }

            .sw-in:checked~.sw-state-off {
                display: none;
            }
        </style>
    @endpush

</x-dashboard.layout.app>
