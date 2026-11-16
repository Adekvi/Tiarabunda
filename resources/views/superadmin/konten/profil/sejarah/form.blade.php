@php
    $isEdit = $item->exists;
    $pageTitle = $isEdit ? 'Edit Sejarah' : 'Tambah Sejarah';
    $kronologiText = old(
        'kronologi_text',
        collect($item->kronologi ?? [])
            ->map(fn($r) => trim(($r['tahun'] ?? '') . ' | ' . ($r['judul'] ?? '') . ' | ' . ($r['deskripsi'] ?? '')))
            ->filter(fn($l) => $l !== '||' && $l !== '')
            ->implode("\n"),
    );

    // Hitung statistik ringan untuk sidebar
    $isiLength = mb_strlen(strip_tags($item->isi ?? ''));
    $isiWords = $isiLength > 0 ? str_word_count(strip_tags($item->isi ?? '')) : 0;
    $kronologiCount = count($item->kronologi ?? []);
@endphp

<x-dashboard.layout.app :title="$pageTitle" :page-title="$pageTitle">
    <div class="g">
        {{-- Breadcrumb --}}
        <div class="card" style="padding:14px 22px;">
            <div class="ch2" style="margin-bottom:0;">
                <div>
                    <p style="margin:0 0 2px;font-size:0.75rem;color:var(--mut);">
                        <a href="{{ route('superadmin.sejarah.index') }}"
                            style="color:var(--pri);font-weight:600;">Sejarah</a>
                        <span style="opacity:.5;"> / </span>{{ $isEdit ? 'Ubah' : 'Tambah' }}
                    </p>
                    <h2 style="margin:0;font-size:1.15rem;">{{ $pageTitle }}</h2>
                </div>
                <a href="{{ route('superadmin.sejarah.index') }}" class="btn gh">← Kembali</a>
            </div>
        </div>

        @if ($errors->any())
            <div class="al w" role="alert">
                <strong style="display:block;margin-bottom:6px;">Periksa kembali isian:</strong>
                <ul style="margin:0;padding-left:18px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="g g2" style="align-items:start;">
            {{-- ================= KIRI: FORM ================= --}}
            <div class="card" style="min-width:0;">
                <h2 style="margin-bottom:6px;">Detail konten</h2>
                <p style="margin:0 0 20px;font-size:0.85rem;color:var(--mut);">
                    Tulis perjalanan kampus dengan jelas agar mudah dibaca pengunjung situs.
                </p>

                <form method="POST" enctype="multipart/form-data" id="sejarahForm"
                    action="{{ $isEdit ? route('superadmin.sejarah.update', $item) : route('superadmin.sejarah.store') }}">
                    @csrf
                    @if ($isEdit)
                        @method('PUT')
                    @endif

                    <div class="f">
                        <label for="judhul">Judul <span style="color:var(--err);">*</span></label>
                        <input id="judhul" type="text" name="judhul" maxlength="255" required autofocus
                            value="{{ old('judhul', $item->judhul) }}"
                            placeholder="Contoh: Perjalanan Universitas Tiara Bunda">
                        @error('judhul')
                            <small style="color:var(--err);">{{ $message }}</small>
                        @enderror
                    </div>

                    {{-- ===== Narasi Sejarah (Rich Text Editor) ===== --}}
                    <div class="f">
                        <label for="isi">Narasi sejarah <span style="color:var(--err);">*</span></label>

                        {{-- Fallback textarea (tampil jika JS mati) --}}
                        <textarea id="isi" name="isi" rows="14" required data-rte-fallback
                            placeholder="Ceritakan latar belakang, pendirian, dan perkembangan kampus…" style="display:none;">{{ old('isi', $item->isi) }}</textarea>

                        {{-- Rich Text Editor --}}
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
                                aria-multiline="true" aria-label="Isi narasi sejarah"
                                data-placeholder="Tulis narasi lengkap sejarah kampus…"></div>
                            <div class="rte-foot"><span data-rte-count>0 kata</span></div>
                        </div>

                        @error('isi')
                            <small style="color:var(--err);">{{ $message }}</small>
                        @enderror
                    </div>

                    {{-- ===== Kronologi ===== --}}
                    <div class="f">
                        <label for="kronologi_text">Tonggak sejarah (opsional)</label>
                        <textarea id="kronologi_text" name="kronologi_text" rows="7"
                            placeholder="Satu baris satu peristiwa&#10;2018 | Berdiri | Kampus didirikan di Depok&#10;2022 | Akreditasi | Meraih status baik">{{ $kronologiText }}</textarea>
                        <small style="color:var(--mut);">Format per baris: <em>Tahun | Judul singkat |
                                Keterangan</em></small>
                    </div>

                    {{-- ===== Gambar sampul ===== --}}
                    <div class="f">
                        <label for="path_sampul">Gambar sampul</label>

                        <div class="dz" data-dz
                            data-initial-src="{{ $item->path_sampul ? asset('storage/' . $item->path_sampul) : '' }}">
                            <input id="path_sampul" type="file" name="path_sampul"
                                accept="image/png,image/jpeg,image/webp">

                            <div class="dz-thumb" data-dz-thumb>
                                <svg class="dz-ico" width="26" height="26" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" aria-hidden="true">
                                    <path d="M3 5h18v14H3zM3 16l5-5 4 4 3-3 6 6M9 9h.01" />
                                </svg>
                                <img alt="Pratinjau gambar sampul" hidden>
                            </div>

                            <div class="dz-text">
                                <strong data-dz-title>Klik atau seret gambar ke sini</strong>
                                <small data-dz-meta>PNG, JPG, atau WEBP · maks 2 MB · disarankan landscape</small>
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

                    {{-- ===== Urutan ===== --}}
                    <div class="f" style="max-width:240px;">
                        <label for="urutan">Urutan tampil</label>
                        <input id="urutan" type="number" name="urutan" min="0" max="9999"
                            value="{{ old('urutan', $item->urutan ?? 0) }}">
                        <small style="color:var(--mut);">Angka lebih kecil tampil lebih dulu.</small>
                        @error('urutan')
                            <small style="color:var(--err);">{{ $message }}</small>
                        @enderror
                    </div>

                    {{-- ===== Publikasi ===== --}}
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
                            class="btn">{{ $isEdit ? 'Simpan perubahan' : 'Simpan konten' }}</button>
                        <a href="{{ route('superadmin.sejarah.index') }}" class="btn gh">Batal</a>
                    </div>
                </form>
            </div>

            {{-- ================= KANAN: SIDEBAR ================= --}}
            <div class="g" style="gap:16px;">

                {{-- 1. Ringkasan Status --}}
                <div class="card">
                    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:14px;">
                        <h3 style="margin:0;">Ringkasan</h3>
                        <span class="badge {{ $item->urip ?? true ? 'ok' : 'err' }}">
                            {{ $item->urip ?? true ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </div>

                    <div class="side-stat">
                        <div class="side-stat__row">
                            <span class="side-stat__lbl">Mode</span>
                            <strong class="side-stat__val">{{ $isEdit ? 'Edit' : 'Buat baru' }}</strong>
                        </div>
                        @if ($isEdit)
                            <div class="side-stat__row">
                                <span class="side-stat__lbl">ID</span>
                                <strong class="side-stat__val">#{{ $item->id }}</strong>
                            </div>
                            <div class="side-stat__row">
                                <span class="side-stat__lbl">Terakhir diperbarui</span>
                                <strong
                                    class="side-stat__val">{{ $item->updated_at?->diffForHumans() ?? '—' }}</strong>
                            </div>
                            @if ($item->penganyar)
                                <div class="side-stat__row">
                                    <span class="side-stat__lbl">Diperbarui oleh</span>
                                    <strong class="side-stat__val">{{ $item->penganyar->name }}</strong>
                                </div>
                            @endif
                        @endif
                    </div>

                    <div class="side-stat__sep"></div>

                    <div class="side-stat">
                        <div class="side-stat__row">
                            <span class="side-stat__lbl">Panjang narasi</span>
                            <strong class="side-stat__val">{{ number_format($isiLength, 0, ',', '.') }}
                                karakter</strong>
                        </div>
                        <div class="side-stat__row">
                            <span class="side-stat__lbl">Jumlah kata</span>
                            <strong class="side-stat__val">{{ number_format($isiWords, 0, ',', '.') }} kata</strong>
                        </div>
                        <div class="side-stat__row">
                            <span class="side-stat__lbl">Tonggak</span>
                            <strong class="side-stat__val">{{ $kronologiCount }} peristiwa</strong>
                        </div>
                        <div class="side-stat__row">
                            <span class="side-stat__lbl">Gambar sampul</span>
                            <strong class="side-stat__val">
                                @if ($item->path_sampul)
                                    <span style="color:var(--ok);">✓ Ada</span>
                                @else
                                    <span style="color:var(--mut);">—</span>
                                @endif
                            </strong>
                        </div>
                    </div>
                </div>

                {{-- 2. Checklist Kualitas --}}
                <div class="card">
                    <h3 style="margin-bottom:12px;">Checklist kualitas</h3>
                    <ul class="side-check">
                        <li data-check="judhul">
                            <span class="side-check__dot"></span>
                            <span>Judul singkat &amp; deskriptif</span>
                        </li>
                        <li data-check="isi">
                            <span class="side-check__dot"></span>
                            <span>Narasi minimal 300 karakter</span>
                        </li>
                        <li data-check="kronologi">
                            <span class="side-check__dot"></span>
                            <span>Tonggak sejarah terisi</span>
                        </li>
                        <li data-check="sampul">
                            <span class="side-check__dot"></span>
                            <span>Gambar sampul tersedia</span>
                        </li>
                    </ul>
                </div>

                {{-- 3. Panduan --}}
                <div class="card">
                    <h3 style="margin-bottom:10px;">Panduan penulisan</h3>
                    <ul class="side-guide">
                        <li>Mulai dari latar berdirinya kampus.</li>
                        <li>Sertakan tonggak penting (tahun, capaian).</li>
                        <li>Gunakan bahasa formal, ringkas, mudah dibaca.</li>
                        <li>Gambar sampul landscape, max 2 MB.</li>
                        <li>Urutan kecil = tampil lebih dulu.</li>
                    </ul>
                </div>

                {{-- 4. Danger Zone --}}
                @if ($isEdit)
                    <div class="card side-danger">
                        <h3 style="margin-bottom:8px;color:var(--err);">Zona berbahaya</h3>
                        <p style="margin:0 0 14px;font-size:0.85rem;color:var(--mut);">
                            Konten akan dipindahkan ke <strong>sampah</strong>. Anda masih bisa memulihkannya
                            dari halaman daftar sampah.
                        </p>
                        <form method="POST" action="{{ route('superadmin.sejarah.destroy', $item) }}"
                            data-confirm-form data-confirm-title="Pindahkan ke sampah?"
                            data-confirm-message="Konten &laquo;{{ $item->judhul }}&raquo; akan dipindahkan ke sampah. Anda masih bisa memulihkannya nanti."
                            data-confirm-action="{{ route('superadmin.sejarah.destroy', $item) }}"
                            data-confirm-method="DELETE" data-confirm-type="danger"
                            data-confirm-label="Ya, pindahkan">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn" style="background:var(--err);width:100%;">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" style="margin-right:6px;vertical-align:-2px;"
                                    aria-hidden="true">
                                    <path d="M3 6h18" />
                                    <path d="M8 6V4h8v2" />
                                    <path d="M19 6l-1 14H6L5 6" />
                                </svg>
                                Hapus konten ini
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </div>

    @push('css')
        <link rel="stylesheet" href="{{ asset('admin/landing/profil/sejarah/form.css') }}">
    @endpush

    @push('js')
        <script src="{{ asset('admin/landing/profil/sejarah/form.js') }}" defer></script>
    @endpush
</x-dashboard.layout.app>
