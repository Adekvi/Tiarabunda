<x-dashboard.layout.app :title="$isEdit ? 'Edit Slide Pop-up' : 'Tambah Slide Pop-up'" :pageTitle="$isEdit ? 'Edit Slide Pop-up' : 'Tambah Slide Pop-up'">
    <div class="pop-page">
        @if ($errors->any())
            <div class="al w pop-al" role="alert">
                <ul class="pop-err-list">
                    @foreach ($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="pop-layout">
            {{-- Kolom Kiri: Form Slide Pop-up --}}
            <div class="pop-main">
                <div class="card pop-card">
                    <div class="pop-card__head">
                        <h3>{{ $isEdit ? 'Form Edit Slide Pop-up' : 'Form Tambah Slide Pop-up' }}</h3>
                        <a href="{{ route('superadmin.popup.index') }}" class="btn pop-btn-ghost">
                            &larr; Kembali ke Daftar
                        </a>
                    </div>

                    <form
                        action="{{ $isEdit ? route('superadmin.popup.update', $popup->id) : route('superadmin.popup.store') }}"
                        method="POST" enctype="multipart/form-data" class="pop-form">
                        @csrf
                        @if ($isEdit)
                            @method('PUT')
                        @endif

                        <div class="pop-form-grid">
                            <div class="f pop-col-4">
                                <label for="kategori">Kategori <span class="req">*</span></label>
                                <select id="kategori" name="kategori" class="pop-input" required>
                                    @foreach ($kategoris as $key => $label)
                                        <option value="{{ $key }}" @selected(old('kategori', $popup->kategori) === $key)>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="f pop-col-5">
                                <label for="badge">Badge / Label Atas</label>
                                <input id="badge" type="text" name="badge" class="pop-input" maxlength="80"
                                    value="{{ old('badge', $popup->badge) }}"
                                    placeholder="Contoh: Penerimaan Mahasiswa Baru">
                            </div>

                            <div class="f pop-col-3">
                                <label for="urutan">Urutan Slide</label>
                                <input id="urutan" type="number" name="urutan" class="pop-input" min="0"
                                    max="9999" value="{{ old('urutan', $popup->urutan) }}">
                            </div>

                            <div class="f pop-col-12">
                                <label for="judul">Judul Slide <span class="req">*</span></label>
                                <input id="judul" type="text" name="judul" class="pop-input" maxlength="180"
                                    required value="{{ old('judul', $popup->judul) }}">
                            </div>

                            <div class="f pop-col-12">
                                <label for="deskripsi">Deskripsi</label>
                                <textarea id="deskripsi" name="deskripsi" rows="4" class="pop-input" maxlength="2000">{{ old('deskripsi', $popup->deskripsi) }}</textarea>
                            </div>

                            <div class="f pop-col-6">
                                <label for="teks_tombol">Teks Tombol Utama</label>
                                <input id="teks_tombol" type="text" name="teks_tombol" class="pop-input"
                                    maxlength="80" value="{{ old('teks_tombol', $popup->teks_tombol) }}"
                                    placeholder="Contoh: Daftar PMB Sekarang">
                            </div>

                            <div class="f pop-col-6">
                                <label for="url_tombol">URL Tombol Utama</label>
                                <input id="url_tombol" type="text" name="url_tombol" class="pop-input"
                                    maxlength="255" value="{{ old('url_tombol', $popup->url_tombol) }}"
                                    placeholder="https://... atau /pendidikan/program-pendidikan">
                            </div>

                            <div class="f pop-col-6">
                                <label for="teks_sekunder">Teks Tombol Sekunder (Opsional)</label>
                                <input id="teks_sekunder" type="text" name="teks_sekunder" class="pop-input"
                                    maxlength="80" value="{{ old('teks_sekunder', $popup->teks_sekunder) }}">
                            </div>

                            <div class="f pop-col-6">
                                <label for="url_sekunder">URL Tombol Sekunder (Opsional)</label>
                                <input id="url_sekunder" type="text" name="url_sekunder" class="pop-input"
                                    maxlength="255" value="{{ old('url_sekunder', $popup->url_sekunder) }}">
                            </div>

                            <div class="f pop-col-12">
                                <label for="gambar">Gambar Ilustrasi / Banner (Opsional)</label>
                                <input id="gambar" type="file" name="gambar" class="pop-input"
                                    accept=".jpg,.jpeg,.png,.webp">
                                <small class="pop-help">Otomatis dioptimalkan ke WebP (maks 2MB).</small>

                                @if ($isEdit && !empty($popup->gambar))
                                    <div class="pop-preview">
                                        <img src="{{ asset('storage/' . ltrim($popup->gambar, '/')) }}"
                                            alt="Preview">
                                        <label class="pop-check">
                                            <input type="checkbox" name="hapus_gambar" value="1">
                                            <span class="text-err">Hapus gambar saat disimpan</span>
                                        </label>
                                    </div>
                                @endif
                            </div>

                            <div class="f pop-col-6">
                                <label class="pop-check">
                                    <input type="checkbox" name="buka_tab_baru" value="1"
                                        @checked(old('buka_tab_baru', $popup->buka_tab_baru))>
                                    <span>Buka URL utama di tab baru</span>
                                </label>
                            </div>

                            <div class="f pop-col-6">
                                <label class="pop-check">
                                    <input type="checkbox" name="is_active" value="1"
                                        @checked(old('is_active', $popup->is_active))>
                                    <span>Tampilkan slide di landing utama</span>
                                </label>
                            </div>
                        </div>

                        <div class="pop-form__foot">
                            <a href="{{ route('superadmin.popup.index') }}" class="btn pop-btn-ghost">Batal</a>
                            <button type="submit" class="btn">
                                {{ $isEdit ? 'Simpan Perubahan' : 'Simpan Slide' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Kolom Kanan: Sidebar Informasi Pengisian Slide --}}
            <aside class="pop-aside">
                <div class="card pop-info-box">
                    <h3>Informasi Field Slide</h3>
                    <ul class="pop-tips">
                        <li><strong>Kategori:</strong> Menentukan warna aksen slide di landing (PMB, Jurusan, Beasiswa,
                            Info).</li>
                        <li><strong>Badge:</strong> Tampil sebagai label tab/indikator slide di bagian atas pop-up.</li>
                        <li><strong>URL Tombol:</strong> Bisa berupa tautan eksternal (<code>https://...</code>) atau
                            rute internal (<code>/layanan/...</code>).</li>
                    </ul>
                </div>

                <div class="card pop-info-box">
                    <h3>Tips Tampilan</h3>
                    <ul class="pop-tips">
                        <li>Gunakan judul singkat maksimal 6–9 kata agar mudah dibaca.</li>
                        <li>Gambar rasio landscape (4:3 atau 16:10) paling ideal untuk frame slider.</li>
                        <li>Atur <strong>Urutan Slide</strong> agar alur slide berurutan sesuai prioritas.</li>
                    </ul>
                </div>
            </aside>
        </div>
    </div>

    @push('css')
        <link rel="stylesheet" href="{{ asset('admin/landing/beranda/popup/popup.css') }}">
    @endpush
</x-dashboard.layout.app>
