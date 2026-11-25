@php
    $isEdit = $lpm->exists;
    $pageTitle = $isEdit ? 'Edit Konten LPM' : 'Tambah Konten LPM';
    $formAction = $isEdit
        ? route('superadmin.lpm.update', $lpm)
        : route('superadmin.lpm.store');
    $selectedKat = old('kategori', $lpm->kategori ?: 'spmi');
    $meta = old('data_tambahan', $lpm->data_tambahan ?? []);
@endphp

<x-dashboard.layout.app :title="$pageTitle" :pageTitle="$pageTitle">
    <div class="lpm-form-shell">
        <div class="card lpm-form-card">
            <div style="margin-bottom: 18px; padding-bottom: 14px; border-bottom: 1px solid var(--line);">
                <h2 style="margin: 0; font-size: 1.1rem; font-weight: 800;">{{ $pageTitle }}</h2>
                <p style="margin: 4px 0 0; font-size: 13px; color: var(--mut);">
                    Kelola dokumen SPMI (PPEPP), laporan Audit Mutu Internal (AMI), status akreditasi, serta survei kepuasan.
                </p>
            </div>

            <form method="POST" action="{{ $formAction }}" enctype="multipart/form-data">
                @csrf
                @if ($isEdit)
                    @method('PUT')
                @endif

                <div class="lpm-grid">
                    <div class="lpm-field">
                        <label for="kategori">Kategori LPM <span style="color: var(--err);">*</span></label>
                        <select id="kategori" name="kategori" required>
                            @foreach ($categories as $key => $label)
                                <option value="{{ $key }}" @selected($selectedKat === $key)>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('kategori') <span class="lpm-err">{{ $message }}</span> @enderror
                    </div>

                    <div class="lpm-field">
                        <label for="urutan">Urutan Tampil</label>
                        <input type="number" id="urutan" name="urutan" min="0" max="99999"
                               value="{{ old('urutan', $lpm->urutan ?? 0) }}">
                        <span class="lpm-help">Angka terkecil tampil lebih dulu pada kategori terkait.</span>
                        @error('urutan') <span class="lpm-err">{{ $message }}</span> @enderror
                    </div>

                    <div class="lpm-field">
                        <label for="judhul">Judul Dokumen / Program / Prodi <span style="color: var(--err);">*</span></label>
                        <input type="text" id="judhul" name="judhul"
                               value="{{ old('judhul', $lpm->judhul) }}"
                               placeholder="Contoh: Kebijakan SPMI Universitas / Akreditasi S1 Kebidanan" required>
                        @error('judhul') <span class="lpm-err">{{ $message }}</span> @enderror
                    </div>

                    <div class="lpm-field">
                        <label for="tandha">Slug URL (Tandha) <span style="color: var(--err);">*</span></label>
                        <input type="text" id="tandha" name="tandha"
                               value="{{ old('tandha', $lpm->tandha) }}"
                               placeholder="kebijakan-spmi-universitas" required>
                        <span class="lpm-help">Terbentuk otomatis dari judul, dapat disesuaikan manual.</span>
                        @error('tandha') <span class="lpm-err">{{ $message }}</span> @enderror
                    </div>

                    <div class="lpm-field full">
                        <label for="isi">Deskripsi / Ringkasan Mutu</label>
                        <textarea id="isi" name="isi" rows="5"
                                  placeholder="Tuliskan penjelasan standar mutu, hasil AMI, keterangan SK akreditasi, atau informasi layanan LPM...">{{ old('isi', $lpm->isi) }}</textarea>
                        @error('isi') <span class="lpm-err">{{ $message }}</span> @enderror
                    </div>

                    {{-- Upload Berkas Mutu / Sertifikat Akreditasi --}}
                    <div class="lpm-field full">
                        <label for="file">Lampiran Dokumen Mutu / Sertifikat SK (Opsional)</label>
                        <div class="lpm-drop">
                            <input type="file" id="file" name="file" accept=".pdf,.doc,.docx,.xls,.xlsx,.zip">
                            <span class="lpm-help">Format PDF, Word, Excel, atau ZIP (Maks. 10 MB).</span>
                        </div>
                        @if ($isEdit && $lpm->file_exists)
                            <div style="margin-top: 6px; font-size: 12.5px;">
                                Berkas aktif: <b>{{ $lpm->nama_file_asli ?: basename($lpm->path_file) }}</b>
                                ({{ $lpm->file_extension }} &middot; {{ $lpm->file_size }})
                            </div>
                        @endif
                        @error('file') <span class="lpm-err">{{ $message }}</span> @enderror
                    </div>

                    {{-- Metadata Terstruktur (disimpan ke kolom JSON data_tambahan) --}}
                    <div class="lpm-field full">
                        <div class="lpm-meta-box">
                            <div class="lpm-meta-box-head">
                                <b style="font-size: 13px; color: var(--ink);">Detail Metadata Mutu &amp; Akreditasi (Opsional)</b>
                                <div style="font-size: 12px; color: var(--mut);">
                                    Isi kolom yang sesuai dengan kategori (tersimpan otomatis di <code>data_tambahan</code>).
                                </div>
                            </div>

                            <div class="lpm-field">
                                <label for="meta_kode">Nomor / Kode Dokumen atau SK</label>
                                <input type="text" id="meta_kode" name="data_tambahan[kode_dokumen]"
                                       value="{{ $meta['kode_dokumen'] ?? '' }}"
                                       placeholder="Contoh: SPMI-KBJ-01 / SK No. 0412/LAM-PTKes/2026">
                            </div>

                            <div class="lpm-field">
                                <label for="meta_siklus">Tahap Siklus PPEPP / Jenis Dokumen</label>
                                <input type="text" id="meta_siklus" name="data_tambahan[siklus_ppepp]"
                                       value="{{ $meta['siklus_ppepp'] ?? '' }}"
                                       placeholder="Contoh: Penetapan / Evaluasi (AMI) / Standar Mutu">
                            </div>

                            <div class="lpm-field">
                                <label for="meta_lembaga">Lembaga Akreditasi / Unit Audit</label>
                                <input type="text" id="meta_lembaga" name="data_tambahan[lembaga_akreditasi]"
                                       value="{{ $meta['lembaga_akreditasi'] ?? '' }}"
                                       placeholder="Contoh: BAN-PT / LAM-PTKes / Seluruh Prodi">
                            </div>

                            <div class="lpm-field">
                                <label for="meta_peringkat">Peringkat Akreditasi / Status Capaian</label>
                                <input type="text" id="meta_peringkat" name="data_tambahan[peringkat]"
                                       value="{{ $meta['peringkat'] ?? '' }}"
                                       placeholder="Contoh: Unggul / Baik Sekali / Terpenuhi">
                            </div>

                            <div class="lpm-field">
                                <label for="meta_berlaku">Masa Berlaku / Periode Siklus</label>
                                <input type="text" id="meta_berlaku" name="data_tambahan[masa_berlaku]"
                                       value="{{ $meta['masa_berlaku'] ?? '' }}"
                                       placeholder="Contoh: s.d. 18 Agustus 2030 atau TA 2025/2026">
                            </div>

                            <div class="lpm-field">
                                <label for="meta_tahun">Tahun Penetapan / Terbit</label>
                                <input type="text" id="meta_tahun" name="data_tambahan[tahun]"
                                       value="{{ $meta['tahun'] ?? '' }}"
                                       placeholder="Contoh: 2026">
                            </div>

                            <div class="lpm-field full">
                                <label for="meta_tautan">Tautan Portal e-SPMI / Survei Monev / Repositori</label>
                                <input type="url" id="meta_tautan" name="data_tambahan[tautan]"
                                       value="{{ $meta['tautan'] ?? '' }}"
                                       placeholder="https://spmi.universitastiarabunda.ac.id/...">
                                @error('data_tambahan.tautan') <span class="lpm-err">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    <div class="lpm-field full">
                        <label style="display: inline-flex; align-items: center; gap: 8px; cursor: pointer;">
                            <input type="checkbox" name="urip" value="1" @checked(old('urip', $lpm->urip ?? true))>
                            <span>Publikasikan konten ini di halaman LPM</span>
                        </label>
                    </div>
                </div>

                <div class="lpm-form-foot">
                    <a href="{{ route('superadmin.lpm.index') }}" class="lpm-btn-ghost">Batal</a>
                    <button type="submit" class="btn">
                        {{ $isEdit ? 'Simpan Perubahan' : 'Simpan Konten LPM' }}
                    </button>
                </div>
            </form>
        </div>

        {{-- Sidebar Preview & Panduan LPM --}}
        <aside class="lpm-side">
            <div class="card lpm-side-card">
                <h3 class="lpm-side-title">Pratinjau Kartu LPM</h3>
                <div class="lpm-preview-card">
                    <span class="lpm-badge {{ $selectedKat }}" id="prevKategori">
                        {{ $categories[$selectedKat] ?? 'Dokumen SPMI (PPEPP)' }}
                    </span>
                    <b style="font-size: 14px; color: var(--ink);" id="prevJudhul">
                        {{ old('judhul', $lpm->judhul ?: 'Judul Dokumen / Program LPM') }}
                    </b>
                    <div style="font-size: 12px; color: var(--mut);" id="prevMeta">
                        {{ implode(' • ', array_filter([$meta['kode_dokumen'] ?? null, $meta['peringkat'] ?? null, $meta['tahun'] ?? null])) ?: 'Kode Dokumen / Peringkat • Tahun' }}
                    </div>
                    <p style="margin: 0; font-size: 12.5px; color: var(--mut); line-height: 1.45;" id="prevIsi">
                        {{ \Illuminate\Support\Str::limit(strip_tags((string) old('isi', $lpm->isi)), 120) ?: 'Ringkasan dokumen mutu, siklus PPEPP, atau informasi akreditasi akan tampil di sini.' }}
                    </p>
                </div>
            </div>

            <div class="card lpm-side-card">
                <h3 class="lpm-side-title">Panduan Kategori LPM</h3>
                <ul class="lpm-guide">
                    <li><b>Profil &amp; Struktur LPM:</b> Visi, misi, tugas pokok, dan struktur organisasi pengelola mutu kampus.</li>
                    <li><b>Dokumen SPMI (PPEPP):</b> Kebijakan, Manual, Standar, dan Formulir Penetapan, Pelaksanaan, Evaluasi, Pengendalian, dan Peningkatan mutu.</li>
                    <li><b>Audit Mutu Internal (AMI):</b> Jadwal, instrumen, serta ringkasan hasil audit siklus tahunan.</li>
                    <li><b>Akreditasi &amp; Monev:</b> Peringkat akreditasi BAN-PT/LAM serta hasil survei kepuasan sivitas akademika.</li>
                </ul>
            </div>
        </aside>
    </div>

    @push('js')
        <script src="{{ asset('admin/landing/lembaga/lpm/form.js') }}" nonce="{{ request()->attributes->get('csp_nonce') }}"></script>
    @endpush
</x-dashboard.layout.app>