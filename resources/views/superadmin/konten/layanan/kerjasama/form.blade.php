@php
    $isEdit = $kerjasama->exists;
    $pageTitle = $isEdit ? 'Edit Data Kerjasama' : 'Tambah Data Kerjasama';
    $formAction = $isEdit ? route('superadmin.kerjasama.update', $kerjasama) : route('superadmin.kerjasama.store');
    $selectedJenis = old('jenis', $kerjasama->jenis ?: 'mitra');
@endphp

<x-dashboard.layout.app :title="$pageTitle" :pageTitle="$pageTitle">

    <div class="kjs-form-shell">
        <div class="card kjs-form-card">
            <div style="margin-bottom: 18px; padding-bottom: 14px; border-bottom: 1px solid var(--line);">
                <h2 style="margin: 0; font-size: 1.1rem; font-weight: 800;">{{ $pageTitle }}</h2>
                <p style="margin: 4px 0 0; font-size: 13px; color: var(--mut);">
                    Kelola profil mitra instansi/perusahaan maupun skema penawaran kerjasama Universitas Tiara Bunda.
                </p>
            </div>

            <form method="POST" action="{{ $formAction }}" enctype="multipart/form-data">
                @csrf
                @if ($isEdit)
                    @method('PUT')
                @endif

                <div class="kjs-grid">
                    {{-- Pilihan Jenis Kerjasama --}}
                    <div class="kjs-field full">
                        <label>Jenis Entri Kerjasama <span style="color: var(--err);">*</span></label>
                        <div class="kjs-jenis-switch">
                            <label class="kjs-jenis-opt">
                                <input type="radio" name="jenis" value="mitra" @checked($selectedJenis === 'mitra')>
                                <span>
                                    <b style="display: block; font-size: 13px;">Mitra Instansi / Perusahaan</b>
                                    <small style="color: var(--mut); font-weight: 400;">
                                        Instansi, rumah sakit, atau perusahaan yang telah menjalin MoU/MoA dengan
                                        kampus.
                                    </small>
                                </span>
                            </label>
                            <label class="kjs-jenis-opt">
                                <input type="radio" name="jenis" value="penawaran" @checked($selectedJenis === 'penawaran')>
                                <span>
                                    <b style="display: block; font-size: 13px;">Penawaran Kerjasama</b>
                                    <small style="color: var(--mut); font-weight: 400;">
                                        Program/skema kolaborasi yang ditawarkan kampus kepada calon mitra baru.
                                    </small>
                                </span>
                            </label>
                        </div>
                        @error('jenis')
                            <span class="kjs-err">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="kjs-field">
                        <label for="jeneng" id="lblJeneng">
                            {{ $selectedJenis === 'penawaran' ? 'Judul Program / Penawaran Kerjasama' : 'Nama Instansi / Perusahaan Mitra' }}
                            <span style="color: var(--err);">*</span>
                        </label>
                        <input type="text" id="jeneng" name="jeneng"
                            value="{{ old('jeneng', $kerjasama->jeneng) }}"
                            placeholder="Contoh: RSUD Kota Depok / Program Magang Industri" required>
                        @error('jeneng')
                            <span class="kjs-err">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="kjs-field">
                        <label for="tandha">Slug URL (Tandha) <span style="color: var(--err);">*</span></label>
                        <input type="text" id="tandha" name="tandha"
                            value="{{ old('tandha', $kerjasama->tandha) }}" placeholder="rsud-kota-depok" required>
                        <span class="kjs-help">Slug unik untuk identifikasi halaman/dokumen kerjasama.</span>
                        @error('tandha')
                            <span class="kjs-err">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="kjs-field">
                        <label for="kategori">Sektor / Kategori <span style="color: var(--err);">*</span></label>
                        <input type="text" id="kategori" name="kategori" list="kjsKategoriList"
                            value="{{ old('kategori', $kerjasama->kategori ?: 'Industri & Perusahaan') }}" required>
                        <datalist id="kjsKategoriList">
                            @foreach ($categories as $cat)
                                <option value="{{ $cat }}">
                            @endforeach
                        </datalist>
                        @error('kategori')
                            <span class="kjs-err">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="kjs-field">
                        <label for="urutan">Urutan Tampil</label>
                        <input type="number" id="urutan" name="urutan" min="0" max="99999"
                            value="{{ old('urutan', $kerjasama->urutan ?? 0) }}">
                        @error('urutan')
                            <span class="kjs-err">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="kjs-field">
                        <label for="nomor_dokumen">Nomor MoU / MoA / Kode Penawaran</label>
                        <input type="text" id="nomor_dokumen" name="nomor_dokumen"
                            value="{{ old('nomor_dokumen', $kerjasama->nomor_dokumen) }}"
                            placeholder="Contoh: 014/MOU-UTB/XI/2026">
                        @error('nomor_dokumen')
                            <span class="kjs-err">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="kjs-field">
                        <label for="periode">Masa Berlaku / Periode</label>
                        <input type="text" id="periode" name="periode"
                            value="{{ old('periode', $kerjasama->periode) }}"
                            placeholder="Contoh: 2026 - 2029 atau Terbuka">
                        @error('periode')
                            <span class="kjs-err">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="kjs-field full">
                        <label for="katrangan">Ruang Lingkup / Deskripsi Kerjasama</label>
                        <textarea id="katrangan" name="katrangan" rows="3"
                            placeholder="Jelaskan bentuk kerjasama (magang mahasiswa, praktik klinik, penelitian, rekrutmen, atau manfaat bagi mitra)...">{{ old('katrangan', $kerjasama->katrangan) }}</textarea>
                        @error('katrangan')
                            <span class="kjs-err">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="kjs-field">
                        <label for="tautan">Tautan Website Mitra / Formulir Pengajuan</label>
                        <input type="url" id="tautan" name="tautan"
                            value="{{ old('tautan', $kerjasama->tautan) }}" placeholder="https://...">
                        @error('tautan')
                            <span class="kjs-err">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="kjs-field">
                        <label for="kontak_pic">Kontak Narahubung (PIC Kerjasama)</label>
                        <input type="text" id="kontak_pic" name="kontak_pic"
                            value="{{ old('kontak_pic', $kerjasama->kontak_pic) }}"
                            placeholder="Contoh: Humas & Kerjasama (0811-1701-4000)">
                        @error('kontak_pic')
                            <span class="kjs-err">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Upload Logo / Ilustrasi --}}
                    <div class="kjs-field">
                        <label for="logo">Logo Instansi / Gambar Sampul (Opsional)</label>
                        <div class="kjs-drop">
                            <input type="file" id="logo" name="logo" accept=".jpg,.jpeg,.png,.webp,.svg">
                            <span class="kjs-help">JPG, PNG, WEBP, atau SVG (Maks. 2 MB).</span>
                        </div>
                        @if ($isEdit && $kerjasama->logo_url)
                            <div style="display: flex; align-items: center; gap: 10px; margin-top: 6px;">
                                <img src="{{ $kerjasama->logo_url }}" alt="Logo {{ $kerjasama->jeneng }}"
                                    class="kjs-logo">
                                <span class="kjs-help">Logo saat ini terpasang.</span>
                            </div>
                        @endif
                        @error('logo')
                            <span class="kjs-err">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Upload Berkas Proposal / MoU --}}
                    <div class="kjs-field">
                        <label for="file">Berkas Proposal Penawaran / Ringkasan MoU (Opsional)</label>
                        <div class="kjs-drop">
                            <input type="file" id="file" name="file"
                                accept=".pdf,.doc,.docx,.ppt,.pptx,.zip">
                            <span class="kjs-help">PDF, DOCX, PPTX, atau ZIP (Maks. 10 MB).</span>
                        </div>
                        @if ($isEdit && $kerjasama->file_exists)
                            <div style="margin-top: 6px; font-size: 12.5px;">
                                Berkas aktif:
                                <b>{{ $kerjasama->nama_file_asli ?: basename($kerjasama->path_file) }}</b>
                                ({{ $kerjasama->file_extension }} &middot; {{ $kerjasama->file_size }})
                            </div>
                        @endif
                        @error('file')
                            <span class="kjs-err">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="kjs-field full">
                        <label style="display: inline-flex; align-items: center; gap: 8px; cursor: pointer;">
                            <input type="checkbox" name="urip" value="1" @checked(old('urip', $kerjasama->urip ?? true))>
                            <span>Publikasikan di halaman Layanan Kerjasama</span>
                        </label>
                    </div>
                </div>

                <div class="kjs-form-foot">
                    <a href="{{ route('superadmin.kerjasama.index') }}" class="kjs-btn-ghost">Batal</a>
                    <button type="submit" class="btn">
                        {{ $isEdit ? 'Simpan Perubahan' : 'Simpan Data Kerjasama' }}
                    </button>
                </div>
            </form>
        </div>

        {{-- Sidebar Preview & Panduan Kerjasama --}}
        <aside class="kjs-side">
            <div class="card kjs-side-card">
                <h3 class="kjs-side-title">Pratinjau Kartu Publik</h3>
                <div class="kjs-preview-card">
                    <div style="display: flex; gap: 6px; flex-wrap: wrap;">
                        <span class="kjs-badge {{ $selectedJenis === 'penawaran' ? 'penawaran' : 'mitra' }}"
                            id="prevJenis">
                            {{ $selectedJenis === 'penawaran' ? 'Penawaran Kerjasama' : 'Mitra Instansi' }}
                        </span>
                        <span class="kjs-badge" id="prevKategori">
                            {{ old('kategori', $kerjasama->kategori ?: 'Industri & Perusahaan') }}
                        </span>
                    </div>
                    <b style="font-size: 14px; color: var(--ink);" id="prevJeneng">
                        {{ old('jeneng', $kerjasama->jeneng ?: 'Nama Mitra / Judul Penawaran') }}
                    </b>
                    <p style="margin: 0; font-size: 12.5px; color: var(--mut); line-height: 1.45;" id="prevKatrangan">
                        {{ old('katrangan', $kerjasama->katrangan ?: 'Deskripsi ruang lingkup kerjasama akan tampil di sini.') }}
                    </p>
                </div>
            </div>

            <div class="card kjs-side-card">
                <h3 class="kjs-side-title">Panduan Pengisian Kerjasama</h3>
                <ul class="kjs-guide">
                    <li>Gunakan tipe <b>Mitra Instansi / Perusahaan</b> untuk menampilkan daftar rekanan industri, rumah
                        sakit, atau pemerintah yang sudah berjalan.</li>
                    <li>Gunakan tipe <b>Penawaran Kerjasama</b> untuk mempublikasikan paket kolaborasi beserta dokumen
                        proposal yang dapat diunduh calon mitra.</li>
                    <li>Isi kolom <b>Tautan</b> dengan link formulir pengajuan kerjasama (Google Form/portal) agar calon
                        mitra bisa langsung mendaftar.</li>
                </ul>
            </div>
        </aside>
    </div>

    @push('js')
        <script src="{{ asset('admin/landing/layanan/kerjasama/form.js') }}"
            nonce="{{ request()->attributes->get('csp_nonce') }}" defer></script>
    @endpush
</x-dashboard.layout.app>
