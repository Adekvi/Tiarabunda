@php
    $isEdit = $akademik->exists;
    $pageTitle = $isEdit ? 'Edit Dokumen Akademik' : 'Tambah Dokumen Akademik';
    $formAction = $isEdit ? route('superadmin.akademik.update', $akademik) : route('superadmin.akademik.store');
@endphp

<x-dashboard.layout.app :title="$pageTitle" :pageTitle="$pageTitle">
    @push('css')
        <link rel="stylesheet" href="{{ asset('admin/landing/layanan/akademik/form.css') }}">
    @endpush

    <div class="aka-form-shell">
        <div class="card aka-form-card">
            <div style="margin-bottom: 18px; padding-bottom: 14px; border-bottom: 1px solid var(--line);">
                <h2 style="margin: 0; font-size: 1.1rem; font-weight: 800;">{{ $pageTitle }}</h2>
                <p style="margin: 4px 0 0; font-size: 13px; color: var(--mut);">
                    Lengkapi detail formulir atau panduan akademik yang akan dipublikasikan ke mahasiswa.
                </p>
            </div>

            <form method="POST" action="{{ $formAction }}" enctype="multipart/form-data">
                @csrf
                @if ($isEdit)
                    @method('PUT')
                @endif

                <div class="aka-grid">
                    <div class="aka-field">
                        <label for="jeneng">Nama Dokumen / Layanan <span style="color: var(--err);">*</span></label>
                        <input type="text" id="jeneng" name="jeneng"
                            value="{{ old('jeneng', $akademik->jeneng) }}"
                            placeholder="Contoh: Formulir Pengajuan Cuti Akademik" required>
                        @error('jeneng')
                            <span class="aka-err">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="aka-field">
                        <label for="tandha">Slug URL Unduhan (Tandha) <span
                                style="color: var(--err);">*</span></label>
                        <input type="text" id="tandha" name="tandha"
                            value="{{ old('tandha', $akademik->tandha) }}"
                            placeholder="formulir-pengajuan-cuti-akademik" required>
                        <span class="aka-help">Endpoint: <code>/layanan/akademik/unduh/&lt;tandha&gt;</code></span>
                        @error('tandha')
                            <span class="aka-err">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="aka-field">
                        <label for="kategori">Kategori Dokumen <span style="color: var(--err);">*</span></label>
                        <input type="text" id="kategori" name="kategori" list="akaKategoriList"
                            value="{{ old('kategori', $akademik->kategori ?: 'Umum') }}" required>
                        <datalist id="akaKategoriList">
                            @foreach ($categories as $cat)
                                <option value="{{ $cat }}">
                            @endforeach
                        </datalist>
                        <span class="aka-help">Pilih dari daftar atau ketik kategori baru.</span>
                        @error('kategori')
                            <span class="aka-err">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="aka-field">
                        <label for="urutan">Urutan Tampil</label>
                        <input type="number" id="urutan" name="urutan" min="0" max="99999"
                            value="{{ old('urutan', $akademik->urutan ?? 0) }}">
                        <span class="aka-help">Angka terkecil tampil lebih dulu di halaman publik.</span>
                        @error('urutan')
                            <span class="aka-err">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="aka-field full">
                        <label for="katrangan">Keterangan / Deskripsi Singkat</label>
                        <textarea id="katrangan" name="katrangan" rows="3"
                            placeholder="Tuliskan fungsi dokumen, persyaratan, atau alur pengumpulan berkas...">{{ old('katrangan', $akademik->katrangan) }}</textarea>
                        @error('katrangan')
                            <span class="aka-err">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="aka-field full">
                        <label for="file">
                            Berkas Unduhan
                            @if ($isEdit)
                                <span style="font-weight: 400; color: var(--mut);">(Kosongkan jika tidak ingin mengganti
                                    berkas)</span>
                            @else
                                <span style="color: var(--err);">*</span>
                            @endif
                        </label>

                        <div class="aka-drop">
                            <input type="file" id="file" name="file"
                                accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.zip" {{ $isEdit ? '' : 'required' }}>
                            <span class="aka-help">Format yang diizinkan: PDF, DOC/DOCX, XLS/XLSX, PPT/PPTX, ZIP (Maks.
                                10 MB).</span>
                        </div>

                        @if ($isEdit && $akademik->path_file)
                            <div class="aka-file-now">
                                <span>
                                    Berkas aktif:
                                    <b>{{ $akademik->nama_file_asli ?: basename($akademik->path_file) }}</b>
                                    @if ($akademik->file_exists)
                                        <span style="color: var(--mut);">({{ $akademik->file_extension }} &middot;
                                            {{ $akademik->file_size }})</span>
                                    @endif
                                </span>
                                @if ($akademik->file_exists)
                                    <a href="{{ route('landing.akademik.download', $akademik->tandha) }}"
                                        target="_blank" rel="noopener" class="aka-btn-ghost"
                                        style="padding: 4px 10px; font-size: 12px;">
                                        Unduh Berkas
                                    </a>
                                @endif
                            </div>
                        @endif
                        @error('file')
                            <span class="aka-err">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="aka-field full">
                        <label style="display: inline-flex; align-items: center; gap: 8px; cursor: pointer;">
                            <input type="checkbox" id="urip" name="urip" value="1"
                                @checked(old('urip', $akademik->urip ?? true))>
                            <span>Publikasikan dokumen ini di halaman Layanan Akademik</span>
                        </label>
                    </div>
                </div>

                <div class="aka-form-foot">
                    <a href="{{ route('superadmin.akademik.index') }}" class="aka-btn-ghost">Batal</a>
                    <button type="submit" class="btn">
                        {{ $isEdit ? 'Simpan Perubahan' : 'Simpan Dokumen' }}
                    </button>
                </div>
            </form>
        </div>

        {{-- Sidebar Kontekstual Form Akademik --}}
        <aside class="aka-form-side">
            <div class="card aka-side-box">
                <h3 class="aka-side-lbl">Pratinjau Kartu Publik</h3>
                <div class="aka-preview-card">
                    <span class="aka-preview-tag"
                        id="prevKategori">{{ old('kategori', $akademik->kategori ?: 'Umum') }}</span>
                    <b style="font-size: 14px; color: var(--ink);" id="prevJeneng">
                        {{ old('jeneng', $akademik->jeneng ?: 'Judul Dokumen Akademik') }}
                    </b>
                    <p style="margin: 0; font-size: 12.5px; color: var(--mut); line-height: 1.45;" id="prevKatrangan">
                        {{ old('katrangan', $akademik->katrangan ?: 'Keterangan singkat dokumen akan tampil di bagian ini.') }}
                    </p>
                    <div
                        style="font-size: 11.5px; color: var(--mut); padding-top: 6px; border-top: 1px dashed var(--line);">
                        URL: <code
                            id="prevSlug">/layanan/akademik/unduh/{{ old('tandha', $akademik->tandha ?: 'slug-dokumen') }}</code>
                    </div>
                </div>
            </div>

            <div class="card aka-side-box">
                <h3 class="aka-side-lbl">Panduan Layanan Akademik</h3>
                <ul class="aka-guide-list">
                    <li><b>Slug (Tandha)</b> harus unik karena dipakai sebagai tautan unduhan langsung mahasiswa.</li>
                    <li>Kelompokkan dokumen berdasarkan <b>Kategori</b> (mis. <i>KRS &amp; Nilai</i>, <i>Tugas
                            Akhir</i>, <i>Wisuda</i>) agar mudah difilter di halaman publik.</li>
                    <li>Saat berkas baru diunggah pada mode edit, berkas lama di server otomatis dibersihkan agar tidak
                        membebani penyimpanan.</li>
                    <li>Cache halaman publik otomatis diperbarui begitu dokumen disimpan.</li>
                </ul>
            </div>
        </aside>
    </div>

    @push('js')
        <script src="{{ asset('admin/landing/layanan/akademik/form.js') }}"
            nonce="{{ request()->attributes->get('csp_nonce') }}" defer></script>
    @endpush
</x-dashboard.layout.app>
