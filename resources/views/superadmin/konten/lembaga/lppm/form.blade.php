@php
    $isEdit = $lppm->exists;
    $pageTitle = $isEdit ? 'Edit Konten LPPM' : 'Tambah Konten LPPM';
    $formAction = $isEdit ? route('superadmin.lppm.update', $lppm) : route('superadmin.lppm.store');
    $selectedKat = old('kategori', $lppm->kategori ?: 'penelitian');
    $meta = old('data_tambahan', $lppm->data_tambahan ?? []);
@endphp

<x-dashboard.layout.app :title="$pageTitle" :pageTitle="$pageTitle">

    <div class="lppm-form-shell">
        <div class="card lppm-form-card">
            <div style="margin-bottom: 18px; padding-bottom: 14px; border-bottom: 1px solid var(--line);">
                <h2 style="margin: 0; font-size: 1.1rem; font-weight: 800;">{{ $pageTitle }}</h2>
                <p style="margin: 4px 0 0; font-size: 13px; color: var(--mut);">
                    Kelola informasi profil, program penelitian, pengabdian masyarakat, publikasi jurnal, dan HKI.
                </p>
            </div>

            <form method="POST" action="{{ $formAction }}">
                @csrf
                @if ($isEdit)
                    @method('PUT')
                @endif

                <div class="lppm-grid">
                    <div class="lppm-field">
                        <label for="kategori">Kategori LPPM <span style="color: var(--err);">*</span></label>
                        <select id="kategori" name="kategori" required>
                            @foreach ($categories as $key => $label)
                                <option value="{{ $key }}" @selected($selectedKat === $key)>{{ $label }}
                                </option>
                            @endforeach
                        </select>
                        @error('kategori')
                            <span class="lppm-err">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="lppm-field">
                        <label for="urutan">Urutan Tampil</label>
                        <input type="number" id="urutan" name="urutan" min="0" max="99999"
                            value="{{ old('urutan', $lppm->urutan ?? 0) }}">
                        <span class="lppm-help">Angka terkecil tampil lebih dulu pada kategori terkait.</span>
                        @error('urutan')
                            <span class="lppm-err">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="lppm-field">
                        <label for="judhul">Judul Program / Layanan / Karya <span
                                style="color: var(--err);">*</span></label>
                        <input type="text" id="judhul" name="judhul" value="{{ old('judhul', $lppm->judhul) }}"
                            placeholder="Contoh: Pendampingan Posyandu Remaja Berbasis Digital" required>
                        @error('judhul')
                            <span class="lppm-err">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="lppm-field">
                        <label for="tandha">Slug URL (Tandha) <span style="color: var(--err);">*</span></label>
                        <input type="text" id="tandha" name="tandha" value="{{ old('tandha', $lppm->tandha) }}"
                            placeholder="pendampingan-posyandu-remaja" required>
                        <span class="lppm-help">Terbentuk otomatis dari judul, dapat disesuaikan manual.</span>
                        @error('tandha')
                            <span class="lppm-err">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="lppm-field full">
                        <label for="isi">Deskripsi / Konten Lengkap</label>
                        <textarea id="isi" name="isi" rows="6"
                            placeholder="Tuliskan ringkasan kegiatan, tujuan penelitian/pengabdian, luaran, atau prosedur layanan LPPM...">{{ old('isi', $lppm->isi) }}</textarea>
                        @error('isi')
                            <span class="lppm-err">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Metadata Terstruktur (disimpan ke kolom JSON data_tambahan) --}}
                    <div class="lppm-field full">
                        <div class="lppm-meta-box">
                            <div class="lppm-meta-box-head">
                                <b style="font-size: 13px; color: var(--ink);">Detail Metadata Tambahan (Opsional)</b>
                                <div style="font-size: 12px; color: var(--mut);">
                                    Isi kolom yang relevan sesuai kategori yang dipilih (tersimpan otomatis di
                                    <code>data_tambahan</code>).
                                </div>
                            </div>

                            <div class="lppm-field">
                                <label for="meta_skema" id="lblMetaSkema">Skema / Jenis Program</label>
                                <input type="text" id="meta_skema" name="data_tambahan[skema]"
                                    value="{{ $meta['skema'] ?? '' }}"
                                    placeholder="Contoh: Hibah Internal / PKM / Jurnal SINTA">
                            </div>

                            <div class="lppm-field">
                                <label for="meta_pelaksana" id="lblMetaPelaksana">Ketua Peneliti / Tim Pelaksana</label>
                                <input type="text" id="meta_pelaksana" name="data_tambahan[pelaksana]"
                                    value="{{ $meta['pelaksana'] ?? '' }}"
                                    placeholder="Contoh: Dr. Bdn. Lusy Pratiwi, M.K.M. dkk.">
                            </div>

                            <div class="lppm-field">
                                <label for="meta_tahun">Tahun / Periode</label>
                                <input type="text" id="meta_tahun" name="data_tambahan[tahun]"
                                    value="{{ $meta['tahun'] ?? '' }}" placeholder="Contoh: 2026">
                            </div>

                            <div class="lppm-field">
                                <label for="meta_sumber" id="lblMetaSumber">Sumber Dana / Mitra Sasaran</label>
                                <input type="text" id="meta_sumber" name="data_tambahan[sumber_dana]"
                                    value="{{ $meta['sumber_dana'] ?? '' }}"
                                    placeholder="Contoh: Kemdiktisaintek / Kelurahan Krukut Depok">
                            </div>

                            <div class="lppm-field">
                                <label for="meta_nomor">Nomor Kontrak / ISSN / Sertifikat HKI</label>
                                <input type="text" id="meta_nomor" name="data_tambahan[nomor_identitas]"
                                    value="{{ $meta['nomor_identitas'] ?? '' }}"
                                    placeholder="Contoh: EC00202612345 atau ISSN 2809-XXXX">
                            </div>

                            <div class="lppm-field">
                                <label for="meta_tautan">Tautan Portal / Jurnal / Dokumen</label>
                                <input type="url" id="meta_tautan" name="data_tambahan[tautan]"
                                    value="{{ $meta['tautan'] ?? '' }}"
                                    placeholder="https://ojs.universitastiarabunda.ac.id/...">
                                @error('data_tambahan.tautan')
                                    <span class="lppm-err">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="lppm-field full">
                        <label style="display: inline-flex; align-items: center; gap: 8px; cursor: pointer;">
                            <input type="checkbox" name="urip" value="1" @checked(old('urip', $lppm->urip ?? true))>
                            <span>Publikasikan konten ini di halaman LPPM</span>
                        </label>
                    </div>
                </div>

                <div class="lppm-form-foot">
                    <a href="{{ route('superadmin.lppm.index') }}" class="lppm-btn-ghost">Batal</a>
                    <button type="submit" class="btn">
                        {{ $isEdit ? 'Simpan Perubahan' : 'Simpan Konten LPPM' }}
                    </button>
                </div>
            </form>
        </div>

        {{-- Sidebar Preview & Panduan LPPM --}}
        <aside class="lppm-side">
            <div class="card lppm-side-card">
                <h3 class="lppm-side-title">Pratinjau Kartu LPPM</h3>
                <div class="lppm-preview-card">
                    <span class="lppm-badge {{ $selectedKat }}" id="prevKategori">
                        {{ $categories[$selectedKat] ?? 'Penelitian & Hibah' }}
                    </span>
                    <b style="font-size: 14px; color: var(--ink);" id="prevJudhul">
                        {{ old('judhul', $lppm->judhul ?: 'Judul Program / Layanan LPPM') }}
                    </b>
                    <div style="font-size: 12px; color: var(--mut);" id="prevMeta">
                        {{ implode(' • ', array_filter([$meta['pelaksana'] ?? null, $meta['tahun'] ?? null])) ?: 'Tim Pelaksana • Tahun' }}
                    </div>
                    <p style="margin: 0; font-size: 12.5px; color: var(--mut); line-height: 1.45;" id="prevIsi">
                        {{ \Illuminate\Support\Str::limit(strip_tags((string) old('isi', $lppm->isi)), 120) ?: 'Ringkasan konten penelitian atau pengabdian masyarakat akan tampil di sini.' }}
                    </p>
                </div>
            </div>

            <div class="card lppm-side-card">
                <h3 class="lppm-side-title">Panduan Kategori LPPM</h3>
                <ul class="lppm-guide">
                    <li><b>Profil &amp; Layanan:</b> Struktur organisasi LPPM, roadmap riset, dan layanan pendampingan
                        dosen.</li>
                    <li><b>Penelitian &amp; Hibah:</b> Daftar riset internal maupun hibah kompetitif nasional.</li>
                    <li><b>Pengabdian Masyarakat:</b> Program pemberdayaan warga, desa binaan, dan edukasi kesehatan.
                    </li>
                    <li><b>Publikasi &amp; HKI:</b> Jurnal ilmiah kampus (OJS), prosiding, serta paten/hak cipta civitas
                        akademika.</li>
                </ul>
            </div>
        </aside>
    </div>

    @push('js')
        <script src="{{ asset('admin/landing/lembaga/lppm/form.js') }}" nonce="{{ request()->attributes->get('csp_nonce') }}">
        </script>
    @endpush
</x-dashboard.layout.app>
