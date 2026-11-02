@php
    $isEdit = $item->exists;
    $pageTitle = $isEdit ? 'Edit Akreditasi' : 'Tambah Akreditasi';
@endphp

<x-dashboard.layout.app :title="$pageTitle" :page-title="$pageTitle">
    <div class="g">
        <div class="card" style="padding:14px 22px;">
            <div class="ch2" style="margin-bottom:0;">
                <div>
                    <p style="margin:0 0 2px;font-size:0.75rem;color:var(--mut);">
                        <a href="{{ route('superadmin.akreditasi.index') }}"
                            style="color:var(--pri);font-weight:600;">Akreditasi</a>
                        <span style="opacity:.5;"> / </span>{{ $isEdit ? 'Ubah' : 'Tambah' }}
                    </p>
                    <h2 style="margin:0;font-size:1.15rem;">{{ $pageTitle }}</h2>
                </div>
                <a href="{{ route('superadmin.akreditasi.index') }}" class="btn gh">← Kembali</a>
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
                <h2 style="margin-bottom:6px;">Detail akreditasi</h2>
                <p style="margin:0 0 20px;font-size:0.85rem;color:var(--mut);">
                    Unggah gambar sertifikat dan tentukan apakah ini milik institusi atau program studi.
                </p>

                <form method="POST" enctype="multipart/form-data"
                    action="{{ $isEdit ? route('superadmin.akreditasi.update', $item) : route('superadmin.akreditasi.store') }}">
                    @csrf
                    @if ($isEdit)
                        @method('PUT')
                    @endif

                    <div class="f">
                        <label for="jeneng">Judul <span style="color:var(--err)">*</span></label>
                        <input id="jeneng" name="jeneng" type="text" required maxlength="150"
                            value="{{ old('jeneng', $item->jeneng) }}" placeholder="Contoh: Akreditasi Institusi 2024">
                    </div>

                    <div class="g g2" style="gap:16px;">
                        <div class="f" style="margin-bottom:0;">
                            <label for="bagean">Jenis <span style="color:var(--err)">*</span></label>
                            <select id="bagean" name="bagean" required>
                                <option value="institusi" @selected(old('bagean', $item->bagean) === 'institusi')>Institusi</option>
                                <option value="prodi" @selected(old('bagean', $item->bagean) === 'prodi')>Program studi</option>
                            </select>
                        </div>
                        <div class="f" style="margin-bottom:0;">
                            <label for="status_akred">Peringkat / status</label>
                            <input id="status_akred" name="status_akred" type="text" maxlength="50"
                                value="{{ old('status_akred', $item->status_akred) }}"
                                placeholder="Baik / Unggul / A / B">
                        </div>
                    </div>

                    <div class="f" id="wrap-prodi">
                        <label for="prodi_id">Program studi</label>
                        <select id="prodi_id" name="prodi_id">
                            <option value="">— Pilih jika jenis Prodi —</option>
                            @foreach ($prodiList as $p)
                                <option value="{{ $p->id }}" @selected((string) old('prodi_id', $item->prodi_id) === (string) $p->id)>
                                    {{ $p->jeneng }} ({{ $p->undhak }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="f">
                        <label for="katrangan">Keterangan</label>
                        <textarea id="katrangan" name="katrangan" rows="4" placeholder="Catatan singkat (opsional)">{{ old('katrangan', $item->katrangan) }}</textarea>
                    </div>

                    <div class="f">
                        <label for="path_gambar">Gambar sertifikat</label>

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
                                <small data-dz-meta>PNG, JPG, atau WEBP · maks. 2 MB · portrait lebih baik</small>
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
                        <a href="{{ route('superadmin.akreditasi.index') }}" class="btn gh">Batal</a>
                    </div>
                </form>
            </div>

            <div class="g" style="gap:16px;">
                <div class="card">
                    <h3 style="margin-bottom:10px;">Panduan</h3>
                    <ul style="margin:0;padding-left:18px;color:var(--mut);font-size:0.88rem;line-height:1.7;">
                        <li><strong>Institusi</strong> — sertifikat kampus secara keseluruhan.</li>
                        <li><strong>Prodi</strong> — pilih program studi terkait.</li>
                        <li>Gambar tajam, tanpa watermark berlebih.</li>
                    </ul>
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
            /* ---------- Dropzone gambar sampul ---------- */
            .f .dz {
                position: relative;
                display: flex;
                align-items: center;
                gap: 16px;
                padding: 16px;
                border: 1.5px dashed var(--line);
                border-radius: 12px;
                background: var(--bg);
                transition: border-color .2s, background .2s;
            }

            .f .dz:hover,
            .f .dz.is-drag {
                border-color: var(--pri);
                background: var(--pri-t);
            }

            .f .dz:focus-within {
                border-color: var(--pri);
                box-shadow: 0 0 0 3px var(--pri-t);
            }

            /* input file menutupi seluruh area -> klik & drag-drop native */
            .f .dz input[type="file"] {
                position: absolute;
                inset: 0;
                width: 100%;
                height: 100%;
                padding: 0;
                border: 0;
                border-radius: 12px;
                opacity: 0;
                cursor: pointer;
                z-index: 1;
            }

            .dz-thumb {
                position: relative;
                flex: none;
                display: grid;
                place-items: center;
                width: 96px;
                height: 72px;
                border-radius: 10px;
                overflow: hidden;
                background: var(--card);
                border: 1px solid var(--line);
                color: var(--mut);
            }

            .dz-thumb img {
                position: absolute;
                inset: 0;
                width: 100%;
                height: 100%;
                object-fit: cover;
            }

            .dz-thumb img[hidden] {
                display: none;
            }

            .dz-text {
                flex: 1;
                min-width: 0;
                line-height: 1.4;
            }

            .dz-text strong {
                display: block;
                font-size: .9rem;
                overflow: hidden;
                text-overflow: ellipsis;
                white-space: nowrap;
            }

            .dz-text small {
                color: var(--mut);
                font-size: .78rem;
            }

            .dz-btn {
                flex: none;
                padding: 7px 14px;
                border-radius: 10px;
                background: var(--card);
                border: 1px solid var(--line);
                font-weight: 700;
                font-size: .82rem;
                color: var(--pri);
            }

            .dz:hover .dz-btn {
                border-color: var(--pri);
            }

            .dz-clear {
                position: relative;
                z-index: 2;
                /* di atas input file agar bisa diklik */
                flex: none;
                display: grid;
                place-items: center;
                width: 30px;
                height: 30px;
                border-radius: 50%;
                border: 1px solid var(--line);
                background: var(--card);
                color: var(--err);
                font-size: 1.15rem;
                line-height: 1;
            }

            .dz-clear:hover {
                background: var(--err-t);
            }

            .dz-clear[hidden] {
                display: none;
            }

            .dz-error {
                color: var(--err);
            }

            .dz-error[hidden] {
                display: none;
            }

            @media (max-width: 560px) {
                .f .dz {
                    flex-wrap: wrap;
                }

                .dz-btn {
                    display: none;
                }
            }

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

    @push('js')
        <script>
            (() => {
                const MAX = 2 * 1024 * 1024
                const OK = ['image/png', 'image/jpeg', 'image/webp']

                document.querySelectorAll('[data-dz]').forEach((dz) => {
                    const input = dz.querySelector('input[type="file"]')
                    const img = dz.querySelector('[data-dz-thumb] img')
                    const ico = dz.querySelector('.dz-ico')
                    const title = dz.querySelector('[data-dz-title]')
                    const meta = dz.querySelector('[data-dz-meta]')
                    const btn = dz.querySelector('[data-dz-btn]')
                    const clear = dz.querySelector('[data-dz-clear]')
                    const err = dz.parentElement.querySelector('[data-dz-error]')
                    const initial = dz.dataset.initialSrc
                    const def = {
                        title: title.textContent,
                        meta: meta.textContent
                    }
                    let blobUrl = null

                    const show = (src) => {
                        img.hidden = !src
                        ico.style.display = src ? 'none' : ''
                        if (src) img.src = src
                        else img.removeAttribute('src')
                    }

                    const reset = () => {
                        input.value = ''
                        if (blobUrl) URL.revokeObjectURL(blobUrl)
                        blobUrl = null
                        clear.hidden = true
                        if (initial) {
                            show(initial)
                            title.textContent = 'Gambar tersimpan'
                            meta.textContent = 'Pilih gambar baru untuk menggantinya.'
                            btn.textContent = 'Ganti gambar'
                        } else {
                            show('')
                            title.textContent = def.title
                            meta.textContent = def.meta
                            btn.textContent = 'Pilih gambar'
                        }
                    }

                    const fail = (msg) => {
                        err.textContent = msg
                        err.hidden = false
                        reset()
                    }

                    input.addEventListener('change', () => {
                        err.hidden = true
                        const f = input.files[0]
                        if (!f) return reset()
                        if (!OK.includes(f.type)) return fail('Format harus PNG, JPG, atau WEBP.')
                        if (f.size > MAX) return fail('Ukuran gambar maksimal 2 MB.')

                        if (blobUrl) URL.revokeObjectURL(blobUrl)
                        blobUrl = URL.createObjectURL(f)
                        show(blobUrl)
                        title.textContent = f.name
                        meta.textContent = (f.size / 1024).toFixed(0) + ' KB · siap diunggah'
                        btn.textContent = 'Ganti gambar'
                        clear.hidden = false
                    })

                    clear.addEventListener('click', (e) => {
                        e.preventDefault()
                        err.hidden = true
                        reset()
                    })

                    ;
                    ['dragenter', 'dragover'].forEach((ev) =>
                        dz.addEventListener(ev, () => dz.classList.add('is-drag'))
                    );
                    ['dragleave', 'drop'].forEach((ev) =>
                        dz.addEventListener(ev, () => dz.classList.remove('is-drag'))
                    )

                    reset()
                })
            })()
        </script>
    @endpush

</x-dashboard.layout.app>
