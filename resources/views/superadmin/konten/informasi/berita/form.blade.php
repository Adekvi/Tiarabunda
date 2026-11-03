@php
    $isEdit = $item->exists;
    $pageTitle = $isEdit ? 'Edit Berita' : 'Tambah Berita';
    $terbit = old('wektu_terbit', optional($item->wektu_terbit)->format('Y-m-d\TH:i'));
@endphp

<x-dashboard.layout.app :title="$pageTitle" :page-title="$pageTitle">
    <div class="g">
        <div class="card" style="padding:14px 22px;">
            <div class="ch2" style="margin-bottom:0;">
                <div>
                    <p style="margin:0 0 2px;font-size:0.75rem;color:var(--mut);">
                        <a href="{{ route('superadmin.berita.index') }}"
                            style="color:var(--pri);font-weight:600;">Berita</a>
                        <span style="opacity:.5;"> / </span>{{ $isEdit ? 'Ubah' : 'Tambah' }}
                    </p>
                    <h2 style="margin:0;font-size:1.15rem;">{{ $pageTitle }}</h2>
                </div>
                <a href="{{ route('superadmin.berita.index') }}" class="btn gh">← Kembali</a>
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
                <h2 style="margin-bottom:6px;">Detail berita</h2>
                <p style="margin:0 0 20px;font-size:0.85rem;color:var(--mut);">
                    Tulis berita kampus dengan judul jelas. Tanggal terbit mengatur kapan konten muncul di publik.
                </p>

                <form method="POST" enctype="multipart/form-data" id="beritaForm"
                    action="{{ $isEdit ? route('superadmin.berita.update', $item) : route('superadmin.berita.store') }}">
                    @csrf
                    @if ($isEdit)
                        @method('PUT')
                    @endif

                    <div class="f">
                        <label for="judhul">Judul <span style="color:var(--err)">*</span></label>
                        <input id="judhul" name="judhul" type="text" required maxlength="200"
                            value="{{ old('judhul', $item->judhul) }}" placeholder="Judul berita">
                        @error('judhul')
                            <small style="color:var(--err);">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="f">
                        <label for="tandha">Slug URL (opsional)</label>
                        <input id="tandha" name="tandha" type="text" maxlength="200"
                            value="{{ old('tandha', $item->tandha) }}"
                            placeholder="Otomatis dari judul jika dikosongkan">
                        @error('tandha')
                            <small style="color:var(--err);">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="f">
                        <label for="ringkesan">Ringkasan</label>
                        <textarea id="ringkesan" name="ringkesan" rows="3" maxlength="500"
                            placeholder="Cuplikan singkat untuk kartu berita…">{{ old('ringkesan', $item->ringkesan) }}</textarea>
                    </div>

                    <div class="f">
                        <label for="isi">Isi berita</label>
                        {{-- Textarea tetap ada (name="isi") dan menjadi sumber data yang dikirim.
                             Jika editor gagal dimuat, textarea ini otomatis tetap bisa dipakai. --}}
                        <div class="rte" data-rte hidden>
                            <div data-rte-editor></div>
                        </div>
                        <textarea id="isi" name="isi" rows="12" placeholder="Tulis isi lengkap…">{{ old('isi', $item->isi) }}</textarea>
                        @error('isi')
                            <small style="color:var(--err);">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="f">
                        <label for="wektu_terbit">Tanggal &amp; waktu terbit</label>
                        <input id="wektu_terbit" type="datetime-local" name="wektu_terbit" value="{{ $terbit }}">
                        <small style="color:var(--mut);">Kosongkan atau set masa depan untuk jadwal terbit.</small>
                    </div>

                    <div class="f">
                        <label for="path_sampul">Gambar sampul</label>
                        <div class="dz" data-dz data-initial-src="{{ $item->sampul_url ?? '' }}">
                            <input id="path_sampul" type="file" name="path_sampul"
                                accept="image/png,image/jpeg,image/webp">
                            <div class="dz-thumb" data-dz-thumb>
                                <svg class="dz-ico" width="26" height="26" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" aria-hidden="true">
                                    <path d="M3 5h18v14H3zM3 16l5-5 4 4 3-3 6 6M9 9h.01" />
                                </svg>
                                <img alt="Pratinjau sampul" decoding="async" hidden>
                            </div>
                            <div class="dz-text">
                                <strong data-dz-title>Klik atau seret gambar ke sini</strong>
                                <small data-dz-meta>PNG, JPG, atau WEBP · maks. 2 MB · landscape</small>
                            </div>
                            <span class="dz-btn" data-dz-btn>Pilih gambar</span>
                            <button type="button" class="dz-clear" data-dz-clear aria-label="Batalkan pilihan gambar"
                                hidden>×</button>
                        </div>
                        <small class="dz-error" data-dz-error role="alert" hidden></small>
                        @error('path_sampul')
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
                        style="display:flex;gap:10px;margin-top:24px;padding-top:20px;border-top:1px solid var(--line);flex-wrap:wrap;">
                        <button type="submit"
                            class="btn">{{ $isEdit ? 'Simpan perubahan' : 'Simpan berita' }}</button>
                        <a href="{{ route('superadmin.berita.index') }}" class="btn gh">Batal</a>
                    </div>
                </form>
            </div>

            {{-- ================= Sidebar kanan ================= --}}
            <aside class="g aside" style="gap:16px;" data-aside>
                <div class="card aside-card">
                    <div class="aside-head">
                        <span class="aside-ico" aria-hidden="true">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path d="M12 20h9M16.5 3.5a2.1 2.1 0 013 3L7 19l-4 1 1-4z" />
                            </svg>
                        </span>
                        <div>
                            <h3>Status form</h3>
                            <small>{{ $isEdit ? 'Mengedit berita yang ada' : 'Berita baru' }}</small>
                        </div>
                        <span class="badge {{ $isEdit ? '' : 'warn' }}"
                            style="margin-left:auto;">{{ $isEdit ? 'Edit' : 'Baru' }}</span>
                    </div>

                    <dl class="kv">
                        <div>
                            <dt>Publikasi</dt>
                            <dd><span class="badge ok" data-st-pub>Tampil</span></dd>
                        </div>
                        <div>
                            <dt>Jadwal terbit</dt>
                            <dd data-st-date>Segera setelah disimpan</dd>
                        </div>
                        <div>
                            <dt>Gambar sampul</dt>
                            <dd data-st-cover>{{ $item->sampul_url ? 'Tersimpan' : 'Belum ada' }}</dd>
                        </div>
                        @if ($isEdit)
                            <div>
                                <dt>Dibuat</dt>
                                <dd>{{ optional($item->created_at)->translatedFormat('d M Y, H:i') ?? '-' }}</dd>
                            </div>
                            <div>
                                <dt>Diperbarui</dt>
                                <dd>{{ optional($item->updated_at)->translatedFormat('d M Y, H:i') ?? '-' }}</dd>
                            </div>
                        @endif
                    </dl>
                </div>

                <div class="card aside-card">
                    <div class="aside-head">
                        <span class="aside-ico" aria-hidden="true">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path d="M9 11l3 3L22 4M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11" />
                            </svg>
                        </span>
                        <div>
                            <h3>Kelengkapan</h3>
                            <small data-ck-text>0 dari 4 terpenuhi</small>
                        </div>
                    </div>
                    <div class="prog" style="margin-bottom:12px;"><i data-ck-bar style="width:0%"></i></div>
                    <ul class="ck">
                        <li data-ck="judul"><i></i>Judul terisi</li>
                        <li data-ck="ringkasan"><i></i>Ringkasan terisi</li>
                        <li data-ck="isi"><i></i>Isi berita terisi</li>
                        <li data-ck="sampul"><i></i>Gambar sampul ada</li>
                    </ul>
                </div>

                <div class="card aside-card">
                    <div class="aside-head">
                        <span class="aside-ico" aria-hidden="true">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path d="M4 6h16M4 12h16M4 18h10" />
                            </svg>
                        </span>
                        <div>
                            <h3>Statistik konten</h3>
                            <small>Diperbarui saat mengetik</small>
                        </div>
                    </div>
                    <div class="mini">
                        <div><b data-ct-judul>0</b><small>/ 200 judul</small></div>
                        <div><b data-ct-ring>0</b><small>/ 500 ringkasan</small></div>
                        <div><b data-ct-kata>0</b><small>kata</small></div>
                        <div><b data-ct-baca>0</b><small>menit baca</small></div>
                    </div>
                    <p class="slug-prev" data-slug-prev hidden></p>
                </div>

                <div class="card aside-card">
                    <div class="aside-head">
                        <span class="aside-ico" aria-hidden="true">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10" />
                                <path d="M12 16v-4M12 8h.01" />
                            </svg>
                        </span>
                        <div>
                            <h3>Panduan</h3>
                            <small>Agar berita mudah dibaca</small>
                        </div>
                    </div>
                    <ul class="tips">
                        <li>Judul singkat dan informatif.</li>
                        <li>Ringkasan dipakai di kartu daftar berita.</li>
                        <li>Gunakan subjudul dan daftar agar isi mudah dipindai.</li>
                        <li>Gambar sampul dioptimasi otomatis saat unggah.</li>
                        <li>Set tanggal terbit untuk jadwal publikasi.</li>
                    </ul>
                </div>
            </aside>
        </div>
    </div>

    @push('css')
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css">
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
                width: 112px;
                height: 76px;
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
                background: var(--card);
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

            .dz-clear[hidden],
            .dz-error[hidden] {
                display: none;
            }

            .dz-error {
                color: var(--err);
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

            /* ---------- Rich text editor (Quill) ---------- */
            .rte[hidden] {
                display: none;
            }

            .rte .ql-toolbar.ql-snow {
                border: 1px solid var(--line);
                border-radius: 10px 10px 0 0;
                background: var(--bg);
                padding: 8px 10px;
                position: sticky;
                top: var(--top);
                z-index: 5;
            }

            .rte .ql-container.ql-snow {
                border: 1px solid var(--line);
                border-top: 0;
                border-radius: 0 0 10px 10px;
                background: var(--card);
                font: inherit;
                transition: border-color .2s, box-shadow .2s;
            }

            .rte:focus-within .ql-container.ql-snow,
            .rte:focus-within .ql-toolbar.ql-snow {
                border-color: var(--pri);
            }

            .rte:focus-within .ql-container.ql-snow {
                box-shadow: 0 0 0 3px var(--pri-t);
            }

            .rte .ql-editor {
                min-height: 300px;
                max-height: 640px;
                overflow-y: auto;
                padding: 16px 18px;
                font-size: .95rem;
                line-height: 1.75;
                color: var(--ink);
            }

            .rte .ql-editor.ql-blank::before {
                color: var(--mut);
                font-style: normal;
            }

            .rte .ql-editor h2 {
                font-size: 1.3rem;
                margin: 1em 0 .4em;
            }

            .rte .ql-editor h3 {
                font-size: 1.1rem;
                margin: 1em 0 .4em;
            }

            .rte .ql-editor blockquote {
                border-left: 4px solid var(--acc);
                background: var(--acc-t);
                color: var(--ink);
                padding: 8px 14px;
                border-radius: 0 8px 8px 0;
            }

            .rte .ql-editor a {
                color: var(--pri);
            }

            /* ikon & teks toolbar mengikuti tema */
            .rte .ql-snow .ql-stroke {
                stroke: var(--mut);
            }

            .rte .ql-snow .ql-fill {
                fill: var(--mut);
            }

            .rte .ql-snow .ql-picker {
                color: var(--mut);
            }

            .rte .ql-snow.ql-toolbar button:hover .ql-stroke,
            .rte .ql-snow.ql-toolbar button.ql-active .ql-stroke,
            .rte .ql-snow.ql-toolbar .ql-picker-label:hover .ql-stroke,
            .rte .ql-snow.ql-toolbar .ql-picker-label.ql-active .ql-stroke {
                stroke: var(--pri);
            }

            .rte .ql-snow.ql-toolbar button:hover .ql-fill,
            .rte .ql-snow.ql-toolbar button.ql-active .ql-fill {
                fill: var(--pri);
            }

            .rte .ql-snow.ql-toolbar button:hover,
            .rte .ql-snow.ql-toolbar button.ql-active {
                color: var(--pri);
            }

            .rte .ql-snow .ql-picker-options,
            .rte .ql-snow .ql-tooltip {
                background: var(--card);
                color: var(--ink);
                border: 1px solid var(--line);
                border-radius: 10px;
                box-shadow: 0 8px 24px rgba(16, 24, 40, .14);
            }

            .rte .ql-snow .ql-tooltip input[type="text"] {
                background: var(--bg);
                color: var(--ink);
                border: 1px solid var(--line);
                border-radius: 6px;
                padding: 4px 8px;
                width: auto;
            }

            .rte-count {
                color: var(--mut);
                font-size: .78rem;
            }

            /* ---------- Sidebar kanan ---------- */
            @media (min-width: 1101px) {
                .aside {
                    position: sticky;
                    top: calc(var(--top) + 20px);
                    max-height: calc(100vh - var(--top) - 40px);
                    overflow-y: auto;
                    padding-right: 2px;
                    scrollbar-width: thin;
                }
            }

            .aside-card {
                padding: 18px;
            }

            .aside-head {
                display: flex;
                align-items: center;
                gap: 12px;
                margin-bottom: 14px;
            }

            .aside-head h3 {
                margin: 0;
                font-size: .95rem;
            }

            .aside-head small {
                display: block;
                font-size: .76rem;
                line-height: 1.3;
            }

            .aside-ico {
                display: grid;
                place-items: center;
                flex: none;
                width: 36px;
                height: 36px;
                border-radius: 10px;
                background: var(--pri-t);
                color: var(--pri);
            }

            .aside-card:nth-child(even) .aside-ico {
                background: var(--acc-t);
                color: var(--acc);
            }

            .kv {
                display: grid;
                margin: 0;
            }

            .kv>div {
                display: flex;
                justify-content: space-between;
                align-items: center;
                gap: 12px;
                padding: 10px 0;
                border-top: 1px dashed var(--line);
                font-size: .85rem;
            }

            .kv>div:first-child {
                border-top: 0;
                padding-top: 2px;
            }

            .kv dt {
                color: var(--mut);
            }

            .kv dd {
                margin: 0;
                font-weight: 600;
                text-align: right;
                color: var(--ink);
            }

            .ck {
                list-style: none;
                display: grid;
                gap: 8px;
                font-size: .86rem;
            }

            .ck li {
                display: flex;
                align-items: center;
                gap: 10px;
                color: var(--mut);
                transition: color .2s;
            }

            .ck li i {
                position: relative;
                flex: none;
                width: 18px;
                height: 18px;
                border-radius: 50%;
                border: 1.5px solid var(--line);
                transition: background .2s, border-color .2s;
            }

            .ck li.done {
                color: var(--ink);
            }

            .ck li.done i {
                background: var(--ok);
                border-color: var(--ok);
            }

            .ck li.done i::after {
                content: '';
                position: absolute;
                left: 5px;
                top: 2px;
                width: 4px;
                height: 8px;
                border: solid #fff;
                border-width: 0 2px 2px 0;
                transform: rotate(45deg);
            }

            .aside .prog i {
                transition: width .35s var(--ease);
                background: var(--ok);
            }

            .mini {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 10px;
            }

            .mini>div {
                padding: 10px 12px;
                border: 1px solid var(--line);
                border-radius: 10px;
                background: var(--bg);
            }

            .mini b {
                display: block;
                font-size: 1.15rem;
                line-height: 1.2;
            }

            .mini small {
                font-size: .72rem;
            }

            .mini .over b {
                color: var(--err);
            }

            .slug-prev {
                margin-top: 12px;
                padding: 8px 10px;
                border-radius: 8px;
                background: var(--pri-t);
                color: var(--pri);
                font-size: .78rem;
                font-weight: 600;
                word-break: break-all;
            }

            .tips {
                margin: 0;
                padding-left: 18px;
                color: var(--mut);
                font-size: .85rem;
                line-height: 1.7;
            }

            .tips li::marker {
                color: var(--acc);
            }
        </style>
    @endpush

    @push('js')
        <script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>
        <script>
            (() => {
                const ready = (fn) =>
                    document.readyState === 'loading' ?
                    document.addEventListener('DOMContentLoaded', fn) :
                    fn()

                const $ = (s, r = document) => r.querySelector(s)

                /* =========================================================
                 * 1. Dropzone gambar sampul + preview
                 * ======================================================= */
                const MAX = 2 * 1024 * 1024
                const OK = ['image/png', 'image/jpeg', 'image/webp']
                let coverState = () => {}

                const initDropzone = () => {
                    document.querySelectorAll('[data-dz]').forEach((dz) => {
                        const input = $('input[type="file"]', dz)
                        const img = $('[data-dz-thumb] img', dz)
                        const ico = $('.dz-ico', dz)
                        const title = $('[data-dz-title]', dz)
                        const meta = $('[data-dz-meta]', dz)
                        const btn = $('[data-dz-btn]', dz)
                        const clear = $('[data-dz-clear]', dz)
                        const err = dz.parentElement.querySelector('[data-dz-error]')
                        const initial = dz.dataset.initialSrc || ''
                        const def = {
                            title: title.textContent,
                            meta: meta.textContent
                        }
                        let blobUrl = null
                        let current = null // file yang sedang dipratinjau

                        const show = (src) => {
                            if (src) {
                                img.src = src
                                img.hidden = false
                                ico.style.display = 'none'
                            } else {
                                img.hidden = true
                                img.removeAttribute('src')
                                ico.style.display = ''
                            }
                        }

                        // Fallback: jika blob: diblokir (mis. CSP), baca via FileReader -> data URL
                        img.addEventListener('error', () => {
                            if (current && !img.dataset.fallback) {
                                img.dataset.fallback = '1'
                                const fr = new FileReader()
                                fr.onload = () => {
                                    img.src = fr.result
                                }
                                fr.readAsDataURL(current)
                            } else {
                                // gambar tersimpan gagal dimuat (mis. storage:link belum dibuat)
                                show('')
                                if (!current) meta.textContent =
                                    'Pratinjau gambar tersimpan tidak dapat dimuat.'
                            }
                        })
                        img.addEventListener('load', () => {
                            if (current && img.naturalWidth) {
                                meta.textContent = Math.round(current.size / 1024) + ' KB · ' +
                                    img.naturalWidth + '×' + img.naturalHeight + ' px · siap diunggah'
                            }
                        })

                        const reset = () => {
                            input.value = ''
                            if (blobUrl) URL.revokeObjectURL(blobUrl)
                            blobUrl = null
                            current = null
                            delete img.dataset.fallback
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
                            coverState(!!initial)
                        }

                        const fail = (msg) => {
                            reset()
                            err.textContent = msg
                            err.hidden = false
                        }

                        input.addEventListener('change', () => {
                            err.hidden = true
                            const f = input.files && input.files[0]
                            if (!f) return reset()
                            if (!OK.includes(f.type)) return fail('Format harus PNG, JPG, atau WEBP.')
                            if (f.size > MAX) return fail('Ukuran gambar maksimal 2 MB.')

                            if (blobUrl) URL.revokeObjectURL(blobUrl)
                            delete img.dataset.fallback
                            current = f
                            blobUrl = URL.createObjectURL(f)
                            show(blobUrl)
                            title.textContent = f.name
                            meta.textContent = Math.round(f.size / 1024) + ' KB · siap diunggah'
                            btn.textContent = 'Ganti gambar'
                            clear.hidden = false
                            coverState(true)
                        })

                        clear.addEventListener('click', (e) => {
                            e.preventDefault()
                            err.hidden = true
                            reset()
                        })

                        ;
                        ['dragenter', 'dragover'].forEach((ev) =>
                            dz.addEventListener(ev, () => dz.classList.add('is-drag')));
                        ['dragleave', 'drop'].forEach((ev) =>
                            dz.addEventListener(ev, () => dz.classList.remove('is-drag')))

                        reset()
                    })
                }

                /* =========================================================
                 * 2. Rich text editor (Quill) -> disinkron ke <textarea name="isi">
                 * ======================================================= */
                let editorText = () => ($('#isi')?.value || '').replace(/<[^>]*>/g, ' ')
                let onEditorChange = () => {}

                const initEditor = () => {
                    const ta = $('#isi')
                    const wrap = $('[data-rte]')
                    const host = $('[data-rte-editor]')
                    if (!ta || !wrap || !host || typeof Quill === 'undefined') return // fallback: textarea biasa

                    const quill = new Quill(host, {
                        theme: 'snow',
                        placeholder: 'Tulis isi lengkap…',
                        modules: {
                            toolbar: [
                                [{
                                    header: [2, 3, false]
                                }],
                                ['bold', 'italic', 'underline', 'strike'],
                                [{
                                    list: 'ordered'
                                }, {
                                    list: 'bullet'
                                }],
                                [{
                                    indent: '-1'
                                }, {
                                    indent: '+1'
                                }],
                                ['blockquote', 'link'],
                                [{
                                    align: []
                                }],
                                ['clean']
                            ],
                            history: {
                                delay: 600,
                                maxStack: 100,
                                userOnly: true
                            }
                        }
                    })

                    // Isi awal. Data lama berupa teks biasa diubah menjadi paragraf.
                    let initial = ta.value.trim()
                    if (initial && !/^\s*</.test(initial)) {
                        const esc = (s) => s.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;')
                        initial = initial.split(/\n{2,}/)
                            .map((p) => '<p>' + esc(p).replace(/\n/g, '<br>') + '</p>').join('')
                    }
                    if (initial) quill.clipboard.dangerouslyPasteHTML(initial, 'silent')

                    const sync = () => {
                        ta.value = quill.getText().trim() ? quill.root.innerHTML : ''
                    }
                    quill.on('text-change', () => {
                        sync()
                        onEditorChange()
                    })
                    $('#beritaForm')?.addEventListener('submit', sync)

                    editorText = () => quill.getText()
                    wrap.hidden = false
                    ta.hidden = true
                    ta.style.display = 'none'
                    sync()
                }

                /* =========================================================
                 * 3. Sidebar informatif (live)
                 * ======================================================= */
                const initAside = () => {
                    const judul = $('#judhul')
                    const ring = $('#ringkesan')
                    const slug = $('#tandha')
                    const tgl = $('#wektu_terbit')
                    const urip = $('#urip')

                    const stPub = $('[data-st-pub]')
                    const stDate = $('[data-st-date]')
                    const stCover = $('[data-st-cover]')
                    const ckBar = $('[data-ck-bar]')
                    const ckText = $('[data-ck-text]')
                    const slugPrev = $('[data-slug-prev]')
                    const ctJudul = $('[data-ct-judul]')
                    const ctRing = $('[data-ct-ring]')
                    const ctKata = $('[data-ct-kata]')
                    const ctBaca = $('[data-ct-baca]')

                    let hasCover = !!($('[data-dz]')?.dataset.initialSrc)
                    const fmt = new Intl.DateTimeFormat('id-ID', {
                        dateStyle: 'medium',
                        timeStyle: 'short'
                    })

                    const slugify = (s) => s.toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '')
                        .replace(/[^a-z0-9]+/g, '-').replace(/^-+|-+$/g, '').slice(0, 200)

                    const setCk = (k, on) => $('[data-ck="' + k + '"]')?.classList.toggle('done', !!on)

                    const update = () => {
                        const text = editorText().trim()
                        const words = text ? text.split(/\s+/).length : 0

                        ctJudul.textContent = judul.value.length
                        ctRing.textContent = ring.value.length
                        ctKata.textContent = words
                        ctBaca.textContent = words ? Math.max(1, Math.ceil(words / 200)) : 0
                        ctJudul.closest('div').classList.toggle('over', judul.value.length >= 200)
                        ctRing.closest('div').classList.toggle('over', ring.value.length >= 500)

                        // publikasi & jadwal
                        const when = tgl.value ? new Date(tgl.value) : null
                        const future = when && when.getTime() > Date.now()
                        if (!urip.checked) {
                            stPub.className = 'badge err'
                            stPub.textContent = 'Disembunyikan'
                        } else if (future) {
                            stPub.className = 'badge warn'
                            stPub.textContent = 'Terjadwal'
                        } else {
                            stPub.className = 'badge ok'
                            stPub.textContent = 'Tampil'
                        }
                        stDate.textContent = when && !isNaN(when) ? fmt.format(when) : 'Segera setelah disimpan'

                        // slug
                        const s = (slug.value.trim() ? slugify(slug.value) : slugify(judul.value))
                        slugPrev.hidden = !s
                        slugPrev.textContent = s ? '/berita/' + s : ''

                        // kelengkapan
                        setCk('judul', judul.value.trim())
                        setCk('ringkasan', ring.value.trim())
                        setCk('isi', words >= 20)
                        setCk('sampul', hasCover)
                        const done = document.querySelectorAll('.ck li.done').length
                        ckBar.style.width = (done / 4 * 100) + '%'
                        ckText.textContent = done + ' dari 4 terpenuhi'
                    }

                    coverState = (v) => {
                        hasCover = v
                        stCover.textContent = v ? 'Siap' : 'Belum ada'
                        update()
                    }
                    onEditorChange = update;
                    [judul, ring, slug, tgl, urip].forEach((el) => {
                        el.addEventListener('input', update)
                        el.addEventListener('change', update)
                    })
                    update()
                }

                ready(() => {
                    initEditor()
                    initAside()
                    initDropzone()
                })
            })()
        </script>
    @endpush

</x-dashboard.layout.app>
