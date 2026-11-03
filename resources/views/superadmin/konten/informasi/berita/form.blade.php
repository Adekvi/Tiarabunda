@php
    $isEdit = $item->exists;
    $pageTitle = $isEdit ? 'Edit Berita' : 'Tambah Berita';
    $terbit = old('wektu_terbit', optional($item->wektu_terbit)->format('Y-m-d\TH:i'));
@endphp

<x-dashboard.layout.app :title="$pageTitle" :page-title="$pageTitle">

    <div class="g" id="beritaPage">
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
                        <input id="judhul" name="judhul" type="text" required maxlength="255"
                            value="{{ old('judhul', $item->judhul) }}" placeholder="Judul berita">
                        @error('judhul')
                            <small style="color:var(--err);">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="g g2"
                        style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; align-items: start;">
                        <div class="f" style="margin-bottom:0;">
                            <label for="tandha">Slug URL</label>
                            <input id="tandha" name="tandha" type="text" maxlength="200"
                                value="{{ old('tandha', $item->tandha) }}" readonly tabindex="-1" autocomplete="off"
                                class="is-readonly" placeholder="Otomatis dari judul">
                            <small style="color:var(--mut);">Diisi otomatis dari judul. Tidak dapat diubah
                                manual.</small>
                            @error('tandha')
                                <small style="color:var(--err);">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="f">
                            <label for="golongan">Kategori</label>
                            <select id="golongan" name="golongan" style="width: 100%;">
                                <option value="">— Pilih (opsional) —</option>
                                @foreach (\App\Models\Informasi\Berita::GOLONGAN as $val => $label)
                                    <option value="{{ $val }}" @selected(old('golongan', $item->golongan) === $val)>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            @error('golongan')
                                <small style="color:var(--err); display:block; margin-top:4px;">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    <div class="f">
                        <label for="ringkesan">Ringkasan</label>
                        <textarea id="ringkesan" name="ringkesan" rows="3" maxlength="300" data-max-chars="300"
                            placeholder="Cuplikan singkat untuk kartu berita (maks. 300 karakter)…">{{ old('ringkesan', $item->ringkesan) }}</textarea>
                        <small class="cnt" data-ring-cnt aria-live="polite">0 / 300 karakter</small>
                        @error('ringkesan')
                            <small style="color:var(--err);">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="f">
                        <label for="isi">Isi berita</label>

                        {{-- Rich text editor mandiri (tanpa library/CDN). Tampil setelah JS aktif. --}}
                        <div class="rte" data-rte hidden>
                            <div class="rte-bar" role="toolbar" aria-label="Format teks">
                                <button type="button" class="rb" data-cmd="formatBlock" data-val="h2"
                                    title="Judul besar">H2</button>
                                <button type="button" class="rb" data-cmd="formatBlock" data-val="h3"
                                    title="Subjudul">H3</button>
                                <span class="rte-sep"></span>
                                <button type="button" class="rb" data-cmd="bold"
                                    title="Tebal (Ctrl+B)"><b>B</b></button>
                                <button type="button" class="rb" data-cmd="italic" title="Miring (Ctrl+I)"><i
                                        style="font-family:serif;">I</i></button>
                                <button type="button" class="rb" data-cmd="underline"
                                    title="Garis bawah (Ctrl+U)"><u>U</u></button>
                                <button type="button" class="rb" data-cmd="strikeThrough"
                                    title="Coret"><s>S</s></button>
                                <span class="rte-sep"></span>
                                <button type="button" class="rb" data-cmd="insertUnorderedList"
                                    title="Daftar poin">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round">
                                        <path d="M9 6h11M9 12h11M9 18h11M4 6h.01M4 12h.01M4 18h.01" />
                                    </svg>
                                </button>
                                <button type="button" class="rb" data-cmd="insertOrderedList"
                                    title="Daftar nomor">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M10 6h10M10 12h10M10 18h10M4 5l1-1v4M3.5 13h2l-2 3h2.2" />
                                    </svg>
                                </button>
                                <button type="button" class="rb" data-cmd="formatBlock" data-val="blockquote"
                                    title="Kutipan">
                                    <svg viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M4 17h4l2-4V7H4v6h3zM14 17h4l2-4V7h-6v6h3z" />
                                    </svg>
                                </button>
                                <button type="button" class="rb" data-cmd="createLink" title="Sisipkan tautan">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path
                                            d="M10 14a4 4 0 005.7 0l3-3a4 4 0 00-5.7-5.7l-1 1M14 10a4 4 0 00-5.7 0l-3 3a4 4 0 005.7 5.7l1-1" />
                                    </svg>
                                </button>
                                <span class="rte-sep"></span>
                                <button type="button" class="rb" data-cmd="justifyLeft" title="Rata kiri">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round">
                                        <path d="M4 6h16M4 12h10M4 18h14" />
                                    </svg>
                                </button>
                                <button type="button" class="rb" data-cmd="justifyCenter" title="Rata tengah">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round">
                                        <path d="M4 6h16M7 12h10M5 18h14" />
                                    </svg>
                                </button>
                                <button type="button" class="rb" data-cmd="justifyRight" title="Rata kanan">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round">
                                        <path d="M4 6h16M10 12h10M6 18h14" />
                                    </svg>
                                </button>
                                <span class="rte-sep"></span>
                                <button type="button" class="rb" data-cmd="undo" title="Urungkan (Ctrl+Z)">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M9 14L4 9l5-5M4 9h10a6 6 0 010 12h-3" />
                                    </svg>
                                </button>
                                <button type="button" class="rb" data-cmd="redo" title="Ulangi (Ctrl+Y)">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M15 14l5-5-5-5M20 9H10a6 6 0 000 12h3" />
                                    </svg>
                                </button>
                                <button type="button" class="rb" data-cmd="removeFormat"
                                    title="Bersihkan format">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M4 7V4h16v3M9 20h6M12 4v16M4 20l16-16" />
                                    </svg>
                                </button>
                            </div>
                            <div class="rte-area" data-rte-area contenteditable="true" role="textbox"
                                aria-multiline="true" aria-label="Isi berita" data-placeholder="Tulis isi lengkap…">
                            </div>
                            <div class="rte-foot"><span data-rte-count>0 kata</span></div>
                        </div>

                        {{-- Sumber data yang dikirim ke server (name="isi"). Jika JS tidak jalan, textarea ini tetap bisa dipakai. --}}
                        <textarea id="isi" name="isi" rows="12" placeholder="Tulis isi lengkap…">{{ old('isi', $item->isi) }}</textarea>
                        @error('isi')
                            <small style="color:var(--err);">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="g g2" style="gap:16px;align-items:start;">
                        <div class="f" style="margin-bottom:0;">
                            <label for="wektu_terbit">Tanggal &amp; waktu terbit</label>
                            <input id="wektu_terbit" type="datetime-local" name="wektu_terbit"
                                value="{{ $terbit }}">
                            <small style="color:var(--mut);">Kosongkan / set masa depan = terjadwal.</small>
                        </div>
                        <div class="f" style="margin-bottom:0;">
                            <label for="urutan">Urutan tampil</label>
                            <input id="urutan" type="number" name="urutan" min="0" max="9999"
                                value="{{ old('urutan', $item->urutan ?? 0) }}">
                            <small style="color:var(--mut);">Angka lebih kecil tampil lebih dulu.</small>
                        </div>
                    </div>

                    <div class="f">
                        <label for="path_sampul">Gambar sampul</label>
                        <div class="dz" data-dz data-initial-src="{{ $item->sampul_url ?? '' }}">
                            <input id="path_sampul" type="file" name="path_sampul"
                                accept="image/png,image/jpeg,image/webp,.png,.jpg,.jpeg,.webp">
                            <div class="dz-thumb" data-dz-thumb>
                                <svg class="dz-ico" width="26" height="26" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" aria-hidden="true">
                                    <path d="M3 5h18v14H3zM3 16l5-5 4 4 3-3 6 6M9 9h.01" />
                                </svg>
                                <svg class="dz-ok" width="28" height="28" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"
                                    stroke-linejoin="round" aria-hidden="true" hidden>
                                    <path d="M5 13l4 4L19 7" />
                                </svg>
                                <img alt="Pratinjau sampul" decoding="async" hidden>
                            </div>
                            <div class="dz-text">
                                <strong data-dz-title>Klik atau seret gambar ke sini</strong>
                                <small data-dz-meta>PNG, JPG, atau WEBP · maks. 2 MB · landscape</small>
                            </div>
                            <span class="dz-btn" data-dz-btn>Pilih gambar</span>
                            <button type="button" class="dz-clear" data-dz-clear
                                aria-label="Batalkan pilihan gambar" hidden>×</button>
                        </div>
                        <small class="dz-error" data-dz-error role="alert" hidden></small>
                        @error('path_sampul')
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
                        <div><b data-ct-ring>0</b><small>/ 500 kata ringkasan</small></div>
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
        <link rel="stylesheet" href="{{ asset('admin/landing/informasi/berita/berita.css') }}">
    @endpush
    @push('js')
        <script>
            const beritaPage = document.getElementById('beritaPage');
            beritaPage.dataset.isEdit = @json($isEdit);
            beritaPage.dataset.itemId = @json($item->id);
            beritaPage.dataset.itemJudhul = @json($item->judhul);
            beritaPage.dataset.itemTandha = @json($item->tandha);
            beritaPage.dataset.itemRingkesan = @json($item->ringkesan);
            beritaPage.dataset.itemIsi = @json($item->isi);
            beritaPage.dataset.itemWektuTerbit = @json(optional($item->wektu_terbit)->format('Y-m-d\TH:i'));
            beritaPage.dataset.itemUrutan = @json($item->urutan ?? 0);
            beritaPage.dataset.itemUrip = @json($item->urip ?? true);
        </script>
        <script src="{{ asset('admin/landing/informasi/berita/berita.js') }}"></script>
    @endpush

</x-dashboard.layout.app>
