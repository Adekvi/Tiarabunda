@php
    $isEdit = $item->exists;
    $pageTitle = $isEdit ? 'Edit Visi & Misi' : 'Tambah Visi & Misi';
    $dhaptarText = old('dhaptar_text', implode("\n", $item->dhaptar ?? []));
@endphp

<x-dashboard.layout.app :title="$pageTitle" :page-title="$pageTitle">
    <div class="g">
        <div class="card" style="padding:14px 22px;">
            <div class="ch2" style="margin-bottom:0;">
                <div>
                    <p style="margin:0 0 2px;font-size:0.75rem;color:var(--mut);">
                        <a href="{{ route('superadmin.visimisi.index') }}" style="color:var(--pri);font-weight:600;">Visi
                            &amp; Misi</a>
                        <span style="opacity:.5;"> / </span>{{ $isEdit ? 'Ubah' : 'Tambah' }}
                    </p>
                    <h2 style="margin:0;font-size:1.15rem;">{{ $pageTitle }}</h2>
                </div>
                <a href="{{ route('superadmin.visimisi.index') }}" class="btn gh">← Kembali</a>
            </div>
        </div>

        @if ($errors->any())
            <div class="al w" role="alert">
                <ul style="margin:0;padding-left:18px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="g g2" style="align-items:start;">
            <div class="card" style="min-width:0;">
                <h2 style="margin-bottom:6px;">Detail konten</h2>
                <p style="margin:0 0 20px;font-size:0.85rem;color:var(--mut);">
                    Pilih bagian Visi atau Misi, lalu isi sesuai jenisnya.
                </p>

                <form method="POST"
                    action="{{ $isEdit ? route('superadmin.visimisi.update', $item) : route('superadmin.visimisi.store') }}">
                    @csrf
                    @if ($isEdit)
                        @method('PUT')
                    @endif

                    <div class="f">
                        <label for="bagean">Bagian <span style="color:var(--err);">*</span></label>
                        <select id="bagean" name="bagean" required>
                            <option value="visi" @selected(old('bagean', $item->bagean) === 'visi')>Visi</option>
                            <option value="misi" @selected(old('bagean', $item->bagean) === 'misi')>Misi</option>
                        </select>
                    </div>

                    <div class="f">
                        <label for="judhul">Judul <span style="color:var(--err);">*</span></label>
                        <input id="judhul" type="text" name="judhul" maxlength="255" required
                            value="{{ old('judhul', $item->judhul) }}"
                            placeholder="Contoh: Visi 2032 atau Misi Institusi">
                    </div>

                    <div class="f">
                        <label for="isi">Narasi (disarankan untuk Visi)</label>
                        <textarea id="isi" name="isi" rows="8" placeholder="Tuliskan pernyataan visi…">{{ old('isi', $item->isi) }}</textarea>
                    </div>

                    <div class="f">
                        <label for="dhaptar_text">Daftar poin (disarankan untuk Misi)</label>
                        <textarea id="dhaptar_text" name="dhaptar_text" rows="8" placeholder="Satu baris satu poin misi">{{ $dhaptarText }}</textarea>
                        <small style="color:var(--mut);">Setiap baris menjadi satu poin di halaman publik.</small>
                    </div>

                    <div class="f" style="max-width:240px;">
                        <label for="urutan">Urutan tampil</label>
                        <input id="urutan" type="number" name="urutan" min="0" max="9999"
                            value="{{ old('urutan', $item->urutan ?? 0) }}">
                        <small style="color:var(--mut);">Angka lebih kecil tampil lebih dulu.</small>
                        @error('urutan')
                            <small style="color:var(--err);">{{ $message }}</small>
                        @enderror
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
                        <a href="{{ route('superadmin.visimisi.index') }}" class="btn gh">Batal</a>
                    </div>
                </form>
            </div>

            <div class="g" style="gap:16px;">
                <div class="card">
                    <h3 style="margin-bottom:10px;">Cara mengisi</h3>
                    <ul style="margin:0;padding-left:18px;color:var(--mut);font-size:0.88rem;line-height:1.7;">
                        <li><strong>Visi</strong> — isi Judul + Narasi; daftar poin boleh kosong.</li>
                        <li><strong>Misi</strong> — isi Judul + daftar poin (satu baris satu misi).</li>
                    </ul>
                </div>
                <div class="card">
                    <h3 style="margin-bottom:10px;">Status form</h3>
                    <div style="display:grid;gap:10px;font-size:0.88rem;">
                        <div style="display:flex;justify-content:space-between;">
                            <span style="color:var(--mut);">Mode</span>
                            <strong>{{ $isEdit ? 'Edit' : 'Buat baru' }}</strong>
                        </div>
                        @if ($isEdit)
                            <div style="display:flex;justify-content:space-between;align-items:center;">
                                <span style="color:var(--mut);">Publikasi</span>
                                <span
                                    class="badge {{ $item->urip ? 'ok' : 'err' }}">{{ $item->urip ? 'Aktif' : 'Nonaktif' }}</span>
                            </div>
                        @endif
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
