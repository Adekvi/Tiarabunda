@php
    $isEdit = $item->exists;
    $pageTitle = $isEdit ? 'Edit Visi & Misi' : 'Tambah Visi & Misi';
    
    // Fallback text jika data JSON dhaptar kosong
    $dhaptarText = old('dhaptar_text', implode("\n", $item->dhaptar ?? []));

    // Statistik ringan untuk sidebar
    $isiLength = mb_strlen(strip_tags($item->isi ?? ''));
    $isiWords = $isiLength > 0 ? str_word_count(strip_tags($item->isi ?? '')) : 0;
    $poinCount = count($item->dhaptar ?? []);
@endphp

<x-dashboard.layout.app :title="$pageTitle" :page-title="$pageTitle">
    <div class="g">
        {{-- Breadcrumb --}}
        <div class="card" style="padding:14px 22px;">
            <div class="ch2" style="margin-bottom:0;">
                <div>
                    <p style="margin:0 0 2px;font-size:0.75rem;color:var(--mut);">
                        <a href="{{ route('superadmin.visimisi.index') }}" style="color:var(--pri);font-weight:600;">Visi &
                            Misi</a>
                        <span style="opacity:.5;"> / </span>{{ $isEdit ? 'Ubah' : 'Tambah' }}
                    </p>
                    <h2 style="margin:0;font-size:1.15rem;">{{ $pageTitle }}</h2>
                </div>
                <a href="{{ route('superadmin.visimisi.index') }}" class="btn gh">← Kembali</a>
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
                    Pilih bagian Visi atau Misi, lalu isi sesuai jenisnya.
                </p>

                <form method="POST" id="visimisiForm"
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

                    {{-- ===== Narasi (Rich Text Editor) ===== --}}
                    <div class="f">
                        <label for="isi">Narasi (disarankan untuk Visi)</label>
                        
                        {{-- Fallback textarea --}}
                        <textarea id="isi" name="isi" rows="8" data-rte-fallback
                            placeholder="Tuliskan pernyataan visi…" style="display:none;">{{ old('isi', $item->isi) }}</textarea>

                        {{-- RTE Container --}}
                        <div class="rte" data-rte hidden>
                            <div class="rte-bar" role="toolbar" aria-label="Format teks narasi">
                                <button type="button" class="rb" data-cmd="bold" title="Tebal"><b>B</b></button>
                                <button type="button" class="rb" data-cmd="italic" title="Miring"><i style="font-family:serif;">I</i></button>
                                <button type="button" class="rb" data-cmd="underline" title="Garis bawah"><u>U</u></button>
                                <span class="rte-sep"></span>
                                <button type="button" class="rb" data-cmd="insertUnorderedList" title="Daftar poin">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M9 6h11M9 12h11M9 18h11M4 6h.01M4 12h.01M4 18h.01" /></svg>
                                </button>
                                <button type="button" class="rb" data-cmd="justifyLeft" title="Rata kiri">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M4 6h16M4 12h10M4 18h14" /></svg>
                                </button>
                                <button type="button" class="rb" data-cmd="removeFormat" title="Bersihkan format">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 7V4h16v3M9 20h6M12 4v16M4 20l16-16" /></svg>
                                </button>
                            </div>
                            <div class="rte-area" data-rte-area contenteditable="true" role="textbox"
                                aria-multiline="true" aria-label="Isi narasi" data-placeholder="Tulis narasi visi…"></div>
                            <div class="rte-foot"><span data-rte-count>0 kata</span></div>
                        </div>
                    </div>

                    {{-- ===== Daftar Poin (Rich Text Editor) ===== --}}
                    <div class="f">
                        <label for="dhaptar_text">Daftar poin (disarankan untuk Misi)</label>
                        
                        {{-- Fallback textarea --}}
                        <textarea id="dhaptar_text" name="dhaptar_text" rows="8" data-rte-fallback
                            placeholder="Satu baris satu poin misi" style="display:none;">{{ $dhaptarText }}</textarea>

                        {{-- RTE Container --}}
                        <div class="rte" data-rte hidden>
                            <div class="rte-bar" role="toolbar" aria-label="Format daftar poin">
                                <button type="button" class="rb" data-cmd="bold" title="Tebal"><b>B</b></button>
                                <button type="button" class="rb" data-cmd="italic" title="Miring"><i style="font-family:serif;">I</i></button>
                                <button type="button" class="rb" data-cmd="underline" title="Garis bawah"><u>U</u></button>
                                <span class="rte-sep"></span>
                                <button type="button" class="rb" data-cmd="insertUnorderedList" title="Daftar poin">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M9 6h11M9 12h11M9 18h11M4 6h.01M4 12h.01M4 18h.01" /></svg>
                                </button>
                                <button type="button" class="rb" data-cmd="justifyLeft" title="Rata kiri">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M4 6h16M4 12h10M4 18h14" /></svg>
                                </button>
                                <button type="button" class="rb" data-cmd="removeFormat" title="Bersihkan format">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 7V4h16v3M9 20h6M12 4v16M4 20l16-16" /></svg>
                                </button>
                            </div>
                            <div class="rte-area" data-rte-area contenteditable="true" role="textbox"
                                aria-multiline="true" aria-label="Isi daftar poin" data-placeholder="Tulis poin misi (satu baris satu poin)…"></div>
                            <div class="rte-foot"><span data-rte-count>0 kata</span></div>
                        </div>
                        <small style="color:var(--mut);">Gunakan format daftar (bullet) agar tampil rapi sebagai poin di halaman publik.</small>
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

                    <div style="display:flex;gap:10px;margin-top:24px;padding-top:20px;border-top:1px solid var(--line);flex-wrap:wrap;">
                        <button type="submit" class="btn">{{ $isEdit ? 'Simpan perubahan' : 'Simpan' }}</button>
                        <a href="{{ route('superadmin.visimisi.index') }}" class="btn gh">Batal</a>
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
                        <div class="side-stat__row">
                            <span class="side-stat__lbl">Bagian</span>
                            <strong class="side-stat__val">{{ ucfirst($item->bagean ?? 'Visi') }}</strong>
                        </div>
                        @if ($isEdit)
                            <div class="side-stat__row">
                                <span class="side-stat__lbl">ID</span>
                                <strong class="side-stat__val">#{{ $item->id }}</strong>
                            </div>
                            <div class="side-stat__row">
                                <span class="side-stat__lbl">Terakhir diperbarui</span>
                                <strong class="side-stat__val">{{ $item->updated_at?->diffForHumans() ?? '—' }}</strong>
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
                            <strong class="side-stat__val">{{ number_format($isiLength, 0, ',', '.') }} karakter</strong>
                        </div>
                        <div class="side-stat__row">
                            <span class="side-stat__lbl">Jumlah kata</span>
                            <strong class="side-stat__val">{{ number_format($isiWords, 0, ',', '.') }} kata</strong>
                        </div>
                        <div class="side-stat__row">
                            <span class="side-stat__lbl">Poin Misi</span>
                            <strong class="side-stat__val">{{ $poinCount }} poin</strong>
                        </div>
                    </div>
                </div>

                {{-- 2. Checklist Kualitas --}}
                <div class="card">
                    <h3 style="margin-bottom:12px;">Checklist kualitas</h3>
                    <ul class="side-check">
                        <li data-check="judhul">
                            <span class="side-check__dot"></span>
                            <span>Judul singkat &amp; jelas</span>
                        </li>
                        <li data-check="isi">
                            <span class="side-check__dot"></span>
                            <span>Narasi Visi terisi</span>
                        </li>
                        <li data-check="dhaptar">
                            <span class="side-check__dot"></span>
                            <span>Poin Misi terisi (min. 1)</span>
                        </li>
                    </ul>
                </div>

                {{-- 3. Panduan Penulisan --}}
                <div class="card">
                    <h3 style="margin-bottom:10px;">Panduan penulisan</h3>
                    <ul class="side-guide">
                        <li><strong>Visi</strong> — isi Judul + Narasi; daftar poin boleh kosong.</li>
                        <li><strong>Misi</strong> — isi Judul + daftar poin (satu baris satu misi).</li>
                        <li>Gunakan bahasa formal, ringkas, dan inspiratif.</li>
                        <li>Urutan kecil = tampil lebih dulu.</li>
                    </ul>
                </div>

                {{-- 4. Danger Zone (Khusus Edit) --}}
                @if ($isEdit)
                    <div class="card side-danger">
                        <h3 style="margin-bottom:8px;color:var(--err);">Zona berbahaya</h3>
                        <p style="margin:0 0 14px;font-size:0.85rem;color:var(--mut);">
                            Data akan dipindahkan ke <strong>sampah</strong>. Anda masih bisa memulihkannya
                            dari halaman daftar sampah.
                        </p>
                        <form method="POST" action="{{ route('superadmin.visimisi.destroy', $item) }}"
                            data-confirm-form
                            data-confirm-title="Pindahkan ke sampah?"
                            data-confirm-message="Data &laquo;{{ $item->judhul }}&raquo; akan dipindahkan ke sampah. Anda masih bisa memulihkannya nanti."
                            data-confirm-action="{{ route('superadmin.visimisi.destroy', $item) }}"
                            data-confirm-method="DELETE"
                            data-confirm-type="danger"
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
                                Hapus data ini
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </div>

    @push('css')
        <link rel="stylesheet" href="{{ asset('admin/landing/profil/visi/form.css') }}">
    @endpush

    @push('js')
        <script src="{{ asset('admin/landing/profil/visi/form.js') }}" defer></script>
    @endpush
</x-dashboard.layout.app>