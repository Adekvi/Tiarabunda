@php
    $isEdit = $item->exists;
    $pageTitle = $isEdit ? 'Edit Kegiatan' : 'Tambah Kegiatan';

    $mulaiValue = $item->wektu_mulai ? \Illuminate\Support\Carbon::parse($item->wektu_mulai)->format('Y-m-d\TH:i') : '';

    $rampungValue = $item->wektu_rampung
        ? \Illuminate\Support\Carbon::parse($item->wektu_rampung)->format('Y-m-d\TH:i')
        : '';

    $mulai = old('wektu_mulai', $mulaiValue);
    $rampung = old('wektu_rampung', $rampungValue);
@endphp

<x-dashboard.layout.app :title="$pageTitle" :page-title="$pageTitle">
    <div class="g">
        <div class="card" style="padding:14px 22px;">
            <div class="ch2" style="margin-bottom:0;">
                <div>
                    <p style="margin:0 0 2px;font-size:0.75rem;color:var(--mut);">
                        <a href="{{ route('superadmin.kegiatan.index') }}"
                            style="color:var(--pri);font-weight:600;">Kegiatan</a>
                        <span style="opacity:.5;"> / </span>{{ $isEdit ? 'Ubah' : 'Tambah' }}
                    </p>
                    <h2 style="margin:0;font-size:1.15rem;">{{ $pageTitle }}</h2>
                </div>
                <a href="{{ route('superadmin.kegiatan.index') }}" class="btn gh">← Kembali</a>
            </div>
        </div>

        @if ($errors->any())
            <div class="al w" role="alert">
                <strong style="display:block;margin-bottom:6px;">Periksa kembali isian:</strong>
                <ul style="margin:0;padding-left:18px;">
                    @foreach ($errors->all() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="g kegiatan-layout">
            <div class="g kegiatan-main">
                <div class="activity-form-heading">
                    <span class="activity-eyebrow">FORMULIR PENGELOLAAN</span>
                    <h2>Detail kegiatan</h2>
                    <p>
                        Lengkapi informasi kegiatan kampus secara akurat agar dapat
                        ditampilkan dengan baik pada halaman informasi publik.
                    </p>
                </div>

                <form id="kegiatanForm" method="POST" enctype="multipart/form-data"
                    action="{{ $isEdit ? route('superadmin.kegiatan.update', $item) : route('superadmin.kegiatan.store') }}">
                    @csrf
                    @if ($isEdit)
                        @method('PUT')
                    @endif

                    <div class="f">
                        <label for="judhul">Nama kegiatan <span style="color:var(--err)">*</span></label>
                        <input id="judhul" name="judhul" type="text" required maxlength="255"
                            value="{{ old('judhul', $item->judhul) }}"
                            placeholder="Contoh: Dies Natalis · Seminar Nasional">
                        @error('judhul')
                            <small style="color:var(--err);">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="f">
                        <label for="tandha">Slug URL</label>
                        <input id="tandha" name="tandha" type="text" maxlength="255" readonly
                            value="{{ old('tandha', $item->tandha) }}" placeholder="Otomatis terisi dari nama kegiatan">
                        <small style="color:var(--mut);">Dibuat otomatis dari nama kegiatan, tidak bisa diubah
                            manual.</small>
                        @error('tandha')
                            <small style="color:var(--err);">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="f">
                        <label for="katrangan">Deskripsi kegiatan (katrangan)</label>

                        <!-- Rich Text Editor Toolbar -->
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

                        <!-- Diubah dari ringkesan menjadi katrangan sesuai database -->
                        <textarea id="katrangan" name="katrangan" rows="12" placeholder="Tulis deskripsi lengkap…">{{ old('katrangan', $item->katrangan) }}</textarea>
                        @error('katrangan')
                            <small style="color:var(--err);">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="f">
                        <label for="papan">Lokasi</label>
                        <input id="papan" name="papan" type="text" maxlength="255"
                            value="{{ old('papan', $item->papan) }}" placeholder="Aula utama · Online Zoom · dll.">
                        @error('papan')
                            <small style="color:var(--err);">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="g g2" style="gap:16px;">
                        <div class="f" style="margin-bottom:0;">
                            <label for="wektu_mulai">Waktu mulai</label>
                            <input id="wektu_mulai" type="datetime-local" name="wektu_mulai"
                                value="{{ old('wektu_mulai', $mulai ?? '') }}">
                            @error('wektu_mulai')
                                <small style="color:var(--err);">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="f" style="margin-bottom:0;">
                            <label for="wektu_rampung">Waktu selesai</label>
                            <input id="wektu_rampung" type="datetime-local" name="wektu_rampung"
                                value="{{ old('wektu_rampung', $rampung ?? '') }}">
                            @error('wektu_rampung')
                                <small style="color:var(--err);">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    <div class="f">
                        <label for="path_sampul">Poster / gambar</label>
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

                    <div class="f" style="max-width:240px;">
                        <label for="urutan">Urutan tampil</label>
                        <input id="urutan" type="number" name="urutan" min="0"
                            value="{{ old('urutan', $item->urutan ?? 0) }}">
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
                        style="display:flex;gap:10px;margin-top:24px;padding-top:20px;border-top:1px solid var(--line);flex-wrap:wrap;">
                        <button type="submit" class="btn">
                            {{ $isEdit ? 'Simpan perubahan' : 'Simpan kegiatan' }}
                        </button>
                        <a href="{{ route('superadmin.kegiatan.index') }}" class="btn gh">Batal</a>
                    </div>
                </form>
            </div>

            <aside class="g kegiatan-form-aside">

                {{-- HEADER MODUL --}}
                <section class="activity-module">
                    <div class="activity-module__top">
                        <div class="activity-module__icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                                stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="5" width="18" height="16" rx="2" />
                                <path d="M16 3v4M8 3v4M3 11h18" />
                                <path d="m9 16 2 2 4-4" />
                            </svg>
                        </div>
                        <span class="activity-module__label">CONTENT MANAGEMENT</span>
                    </div>

                    <h3>Manajemen Kegiatan</h3>
                    <p>
                        Kelola informasi acara, agenda, dan aktivitas
                        kampus yang ditampilkan kepada pengunjung.
                    </p>

                    <div class="activity-module__footer">
                        <span>{{ $isEdit ? 'Mode pengeditan' : 'Mode penambahan' }}</span>
                        <span class="activity-module__dot"></span>
                        <span>{{ $isEdit ? 'Memperbarui data' : 'Data baru' }}</span>
                    </div>
                </section>


                {{-- INFORMASI DATA --}}
                <section class="card activity-panel">
                    <div class="activity-panel__heading">
                        <div>
                            <span class="activity-eyebrow">RINGKASAN</span>
                            <h3>Informasi Kegiatan</h3>
                            <p>Konteks data yang sedang dikelola</p>
                        </div>
                        <span class="activity-panel__heading-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                                stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10" />
                                <path d="M12 16v-4M12 8h.01" />
                            </svg>
                        </span>
                    </div>

                    <div class="activity-info-list">
                        <div class="activity-info-item">
                            <span>Jenis tindakan</span>
                            <strong>{{ $isEdit ? 'Edit kegiatan' : 'Tambah kegiatan' }}</strong>
                        </div>

                        <div class="activity-info-item">
                            <span>Modul</span>
                            <strong>Informasi kampus</strong>
                        </div>

                        <div class="activity-info-item">
                            <span>Status penyimpanan</span>
                            <span class="activity-status {{ $isEdit ? 'is-saved' : 'is-new' }}">
                                <i></i>
                                {{ $isEdit ? 'Data tersimpan' : 'Belum disimpan' }}
                            </span>
                        </div>

                        <div class="activity-info-item">
                            <span>Publikasi saat ini</span>
                            @if ($isEdit)
                                <span class="activity-status {{ $item->urip ? 'is-published' : 'is-hidden' }}">
                                    <i></i>
                                    {{ $item->urip ? 'Dipublikasikan' : 'Disembunyikan' }}
                                </span>
                            @else
                                <span class="activity-status is-new">
                                    <i></i>
                                    Data baru
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="activity-callout">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 9v4M12 17h.01" />
                            <path
                                d="M10.3 3.9 2.5 17.4A2 2 0 0 0 4.2 20h15.6a2 2 0 0 0 1.7-2.6L13.7 3.9a2 2 0 0 0-3.4 0Z" />
                        </svg>
                        <p>
                            Periksa kembali informasi kegiatan sebelum menyimpannya.
                            Pastikan status publikasi sudah sesuai.
                        </p>
                    </div>
                </section>


                {{-- CHECKLIST PENGELOLAAN --}}
                <section class="card activity-panel">
                    <div class="activity-panel__heading">
                        <div>
                            <span class="activity-eyebrow">CHECKLIST</span>
                            <h3>Panduan Pengisian</h3>
                            <p>Hal-hal yang perlu diperhatikan</p>
                        </div>
                        <span class="activity-panel__heading-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path d="M9 11l3 3L22 4" />
                                <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11" />
                            </svg>
                        </span>
                    </div>

                    <div class="activity-checklist">

                        <div class="activity-check">
                            <span class="activity-check__number">01</span>
                            <div>
                                <strong>Identitas kegiatan</strong>
                                <p>Gunakan nama resmi yang jelas — slug akan dibuat otomatis dari nama ini.</p>
                            </div>
                        </div>

                        <div class="activity-check">
                            <span class="activity-check__number">02</span>
                            <div>
                                <strong>Deskripsi acara</strong>
                                <p>Jelaskan tujuan, isi kegiatan, agenda, dan informasi penting lainnya.</p>
                            </div>
                        </div>

                        <div class="activity-check">
                            <span class="activity-check__number">03</span>
                            <div>
                                <strong>Jadwal dan lokasi</strong>
                                <p>Pastikan waktu mulai tidak melewati waktu selesai dan lokasi sudah benar.</p>
                            </div>
                        </div>

                        <div class="activity-check">
                            <span class="activity-check__number">04</span>
                            <div>
                                <strong>Poster kegiatan</strong>
                                <p>Gunakan gambar PNG, JPG, atau WEBP dengan ukuran maksimal 2 MB.</p>
                            </div>
                        </div>

                        <div class="activity-check">
                            <span class="activity-check__number">05</span>
                            <div>
                                <strong>Status publikasi</strong>
                                <p>Aktifkan publikasi jika informasi sudah siap ditampilkan kepada publik.</p>
                            </div>
                        </div>

                    </div>
                </section>


                {{-- ALUR PUBLIKASI --}}
                <section class="card activity-panel">
                    <div class="activity-panel__heading">
                        <div>
                            <span class="activity-eyebrow">WORKFLOW</span>
                            <h3>Alur Publikasi</h3>
                            <p>Tahapan pengelolaan informasi</p>
                        </div>
                        <span class="activity-panel__heading-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                                stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10" />
                                <path d="m12 6 0 6 4 2" />
                            </svg>
                        </span>
                    </div>

                    <div class="activity-workflow">
                        <div class="activity-workflow__step">
                            <span class="activity-workflow__icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M12 20h9" />
                                    <path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L8 18l-4 1 1-4Z" />
                                </svg>
                            </span>
                            <div>
                                <strong>Lengkapi informasi</strong>
                                <p>Masukkan detail kegiatan secara lengkap.</p>
                            </div>
                        </div>

                        <div class="activity-workflow__step">
                            <span class="activity-workflow__icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2Z" />
                                    <path d="M17 21v-8H7v8M7 3v5h8" />
                                </svg>
                            </span>
                            <div>
                                <strong>Simpan data</strong>
                                <p>Periksa dan simpan informasi kegiatan.</p>
                            </div>
                        </div>

                        <div class="activity-workflow__step">
                            <span class="activity-workflow__icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="10" />
                                    <path
                                        d="M2 12h20M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10Z" />
                                </svg>
                            </span>
                            <div>
                                <strong>Publikasikan</strong>
                                <p>Aktifkan status agar informasi tersedia di situs publik.</p>
                            </div>
                        </div>
                    </div>
                </section>


                {{-- FOOTER SIDEBAR --}}
                <div class="activity-sidebar-footer">
                    <span class="activity-sidebar-footer__icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 22s8-4 8-11V5l-8-3-8 3v6c0 7 8 11 8 11Z" />
                            <path d="m9 12 2 2 4-4" />
                        </svg>
                    </span>
                    <div>
                        <strong>Informasi terkelola dengan baik</strong>
                        <p>Pastikan setiap data akurat dan dapat dipertanggungjawabkan.</p>
                    </div>
                </div>

            </aside>

        </div>
    </div>

    @push('css')
        <link rel="stylesheet" href="{{ asset('admin/landing/informasi/kegiatan/keg.css') }}">
    @endpush

    @push('js')
        <script src="{{ asset('admin/landing/informasi/kegiatan/keg.js') }}" defer></script>
    @endpush
</x-dashboard.layout.app>
