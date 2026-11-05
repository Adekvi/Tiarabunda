@php
    $isEdit = $item->exists;
    $pageTitle = $isEdit ? 'Edit Fasilitas' : 'Tambah Fasilitas';
@endphp

<x-dashboard.layout.app :title="$pageTitle" :page-title="$pageTitle">
    <div class="g">
        <div class="card" style="padding:14px 22px;">
            <div class="ch2" style="margin-bottom:0;">
                <div>
                    <p style="margin:0 0 2px;font-size:0.75rem;color:var(--mut);">
                        <a href="{{ route('superadmin.fasilitas.index') }}"
                            style="color:var(--pri);font-weight:600;">Fasilitas</a>
                        <span style="opacity:.5;"> / </span>{{ $isEdit ? 'Ubah' : 'Tambah' }}
                    </p>
                    <h2 style="margin:0;font-size:1.15rem;">{{ $pageTitle }}</h2>
                </div>
                <a href="{{ route('superadmin.fasilitas.index') }}" class="btn gh">← Kembali</a>
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
                <h2 style="margin-bottom:6px;">Detail fasilitas</h2>
                <p style="margin:0 0 20px;font-size:0.85rem;color:var(--mut);">
                    Lengkapi nama dan foto fasilitas. Gambar akan dioptimasi otomatis saat disimpan agar halaman tetap
                    cepat.
                </p>

                <form method="POST" enctype="multipart/form-data" id="fasilitasForm"
                    action="{{ $isEdit ? route('superadmin.fasilitas.update', $item) : route('superadmin.fasilitas.store') }}">
                    @csrf
                    @if ($isEdit)
                        @method('PUT')
                    @endif

                    <div class="f">
                        <label for="jeneng">Nama fasilitas <span style="color:var(--err)">*</span></label>
                        <input id="jeneng" name="jeneng" type="text" required maxlength="150"
                            value="{{ old('jeneng', $item->jeneng) }}"
                            placeholder="Contoh: Laboratorium Komputer · Aula Utama">
                        @error('jeneng')
                            <small style="color:var(--err);">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="f">
                        <label for="katrangan">Deskripsi</label>
                        <div class="rte" data-rte hidden>
                            <div class="rte-bar" role="toolbar" aria-label="Format deskripsi">
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
                                <button type="button" class="rb" data-cmd="insertOrderedList" title="Daftar nomor">
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
                                aria-multiline="true" aria-label="Deskripsi kegiatan"
                                data-placeholder="Tuliskan deskripsi lengkap kegiatan...">
                            </div>

                            <div class="rte-foot">
                                <span data-rte-count>0 kata</span>
                            </div>
                        </div>

                        <textarea id="katrangan" name="katrangan" rows="5" placeholder="Ringkasan fasilitas untuk pengunjung situs…">{{ old('katrangan', $item->katrangan) }}</textarea>
                        @error('katrangan')
                            <small style="color:var(--err);">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="f">
                        <label for="path_gambar">Foto fasilitas</label>

                        <div class="dz" data-dz data-initial-src="{{ $item->gambar_url ?? '' }}">
                            <input id="path_gambar" type="file" name="path_gambar"
                                accept="image/png,image/jpeg,image/webp">

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
                                <small data-dz-meta>PNG, JPG, atau WEBP · maks. 2 MB · landscape disarankan</small>
                            </div>

                            <span class="dz-btn" data-dz-btn>Pilih gambar</span>
                            <button type="button" class="dz-clear" data-dz-clear
                                aria-label="Batalkan pilihan gambar" hidden>×</button>
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
                        <small style="color:var(--mut);">Angka lebih kecil tampil lebih dulu.</small>
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
                        <button type="submit" class="btn">{{ $isEdit ? 'Simpan perubahan' : 'Simpan' }}</button>
                        <a href="{{ route('superadmin.fasilitas.index') }}" class="btn gh">Batal</a>
                    </div>
                </form>
            </div>

            <div class="g" style="gap:16px;">
                <div class="card">
                    <div class="ch2" style="margin-bottom:14px;">
                        <h3 style="margin:0;">Status form</h3>
                        <span class="badge {{ $isEdit ? 'warn' : 'ok' }}">{{ $isEdit ? 'Edit' : 'Baru' }}</span>
                    </div>
                    <div class="side-rows">
                        <div class="side-row">
                            <span>Mode</span>
                            <strong>{{ $isEdit ? 'Edit fasilitas' : 'Buat fasilitas baru' }}</strong>
                        </div>
                        @if ($isEdit)
                            <div class="side-row">
                                <span>Publikasi</span>
                                <span class="badge {{ $item->urip ? 'ok' : 'err' }}">
                                    {{ $item->urip ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </div>
                            <div class="side-row">
                                <span>Urutan tampil</span>
                                <strong>#{{ $item->urutan }}</strong>
                            </div>
                            <div class="side-row">
                                <span>Dibuat</span>
                                <strong>{{ $item->created_at?->translatedFormat('d M Y') }}</strong>
                            </div>
                            <div class="side-row">
                                <span>Diperbarui</span>
                                <strong>{{ $item->updated_at?->diffForHumans() }}</strong>
                            </div>
                        @else
                            <div class="side-row">
                                <span>Publikasi default</span>
                                <span class="badge ok">Aktif</span>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="card">
                    <div class="side-info">
                        <div class="side-info__icon">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" aria-hidden="true">
                                <rect x="3" y="3" width="7" height="7" rx="1.5" />
                                <rect x="14" y="3" width="7" height="7" rx="1.5" />
                                <rect x="3" y="14" width="7" height="7" rx="1.5" />
                                <rect x="14" y="14" width="7" height="7" rx="1.5" />
                            </svg>
                        </div>
                        <div class="side-info__body">
                            <h3 style="margin-bottom:4px;">Submenu Fasilitas</h3>
                            <p>Bagian dari master data konten yang dikelola superadmin dan tersinkron otomatis ke
                                landing page sesuai status.</p>
                        </div>
                    </div>
                    <div class="side-path">
                        <span>Konten</span><i>/</i><span>Layanan</span><i>/</i><span class="cur">Fasilitas</span>
                    </div>

                    <div class="side-sync" style="margin-top:14px;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M20 6 9 17l-5-5" />
                        </svg>
                        <p>
                            Setelah disimpan, data ini tampil otomatis di landing page bila berstatus
                            <strong>Aktif</strong>.
                            Perubahan bisa butuh beberapa saat untuk terlihat dilanding.
                        </p>
                    </div>
                </div>

                <div class="card">
                    <h3 style="margin-bottom:12px;">Panduan foto</h3>
                    <ul class="side-tips">
                        <li>
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2.3" stroke-linecap="round"
                                stroke-linejoin="round" aria-hidden="true">
                                <path d="M20 6 9 17l-5-5" />
                            </svg>
                            <span>Satu fasilitas = satu foto utama yang mewakili.</span>
                        </li>
                        <li>
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2.3" stroke-linecap="round"
                                stroke-linejoin="round" aria-hidden="true">
                                <path d="M20 6 9 17l-5-5" />
                            </svg>
                            <span>Orientasi <strong>landscape (16:10)</strong> lebih rapi di galeri.</span>
                        </li>
                        <li>
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2.3" stroke-linecap="round"
                                stroke-linejoin="round" aria-hidden="true">
                                <path d="M20 6 9 17l-5-5" />
                            </svg>
                            <span>Maks. <strong>2 MB</strong>; sistem mengecilkan &amp; membuat thumbnail
                                otomatis.</span>
                        </li>
                        <li>
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2.3" stroke-linecap="round"
                                stroke-linejoin="round" aria-hidden="true">
                                <path d="M20 6 9 17l-5-5" />
                            </svg>
                            <span>Hindari watermark tebal agar tetap profesional.</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    @push('css')
        <link rel="stylesheet" href="{{ asset('admin/landing/layanan/fasilitas/form.css') }}">
    @endpush

    @push('js')
        <script src="{{ asset('admin/landing/layanan/fasilitas/form.js') }}" defer></script>
    @endpush
</x-dashboard.layout.app>
