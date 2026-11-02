@php
    $isEdit = $item->exists;
    $pageTitle = $isEdit ? 'Edit Visi/Misi' : 'Tambah Visi/Misi';
    $bagean = old('bagean', $item->bagean ?? 'visi');
    $dhaptarText = old('dhaptar_text', implode("\n", $item->dhaptar ?? []));
@endphp

<x-dashboard.layout.app :title="$pageTitle" :page-title="$pageTitle">
    <div class="g">
        {{-- Breadcrumb --}}
        <div class="card" style="padding: 14px 22px;">
            <div class="ch2" style="margin-bottom: 0;">
                <div>
                    <p style="margin: 0 0 2px; font-size: 0.75rem; color: var(--mut);">
                        <a href="{{ route('superadmin.visimisi.index') }}"
                            style="color: var(--pri); font-weight: 600;">Visi &amp; Misi</a>
                        <span style="opacity: 0.5;"> / </span>{{ $isEdit ? 'Ubah' : 'Tambah' }}
                    </p>
                    <h2 style="margin: 0; font-size: 1.15rem;">{{ $pageTitle }}</h2>
                </div>
                <a href="{{ route('superadmin.visimisi.index') }}" class="btn gh">← Kembali ke daftar</a>
            </div>
        </div>

        @if ($errors->any())
            <div class="al w" role="alert">
                <strong style="display: block; margin-bottom: 6px;">Periksa kembali isian:</strong>
                <ul style="margin: 0; padding-left: 18px;">
                    @foreach ($errors->all() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="pg">
            {{-- Form --}}
            <div class="card" style="min-width: 0;">
                <h2 style="margin-bottom: 4px;">Detail visi / misi</h2>
                <p style="margin: 0 0 20px; font-size: 0.85rem; color: var(--mut);">
                    Pilih bagian, lalu isi narasi untuk visi atau daftar poin untuk misi.
                </p>

                <form method="POST"
                    action="{{ $isEdit ? route('superadmin.visimisi.update', $item) : route('superadmin.visimisi.store') }}">
                    @csrf
                    @if ($isEdit)
                        @method('PUT')
                    @endif

                    <div class="fgrid" style="margin-bottom: 16px;">
                        <div class="f">
                            <label for="bagean">Bagian <span class="req">*</span></label>
                            <select id="bagean" name="bagean" required>
                                <option value="visi" @selected($bagean === 'visi')>Visi</option>
                                <option value="misi" @selected($bagean === 'misi')>Misi</option>
                            </select>
                            @error('bagean')
                                <span class="errmsg">{{ $message }}</span>
                            @else
                                <span class="hint">Visi = narasi. Misi = daftar poin.</span>
                            @enderror
                        </div>
                        <div class="f">
                            <label for="judhul">Judul <span class="req">*</span></label>
                            <input id="judhul" name="judhul" type="text" maxlength="255" required
                                value="{{ old('judhul', $item->judhul) }}" placeholder="Contoh: Visi 2032">
                            <div class="hint-row">
                                @error('judhul')
                                    <span class="errmsg">{{ $message }}</span>
                                @else
                                    <span class="hint">Maks. 255 karakter</span>
                                @enderror
                                <span class="hint" data-count-for="judhul">0 / 255</span>
                            </div>
                        </div>
                    </div>

                    <div class="f">
                        <label for="isi">Narasi <span class="hint">(untuk Visi)</span></label>
                        <textarea id="isi" name="isi" rows="6" placeholder="Tuliskan pernyataan visi kampus…">{{ old('isi', $item->isi) }}</textarea>
                        @error('isi')
                            <span class="errmsg">{{ $message }}</span>
                        @else
                            <span class="hint">Untuk misi, boleh dikosongkan jika memakai daftar poin.</span>
                        @enderror
                    </div>

                    <div class="f">
                        <label for="dhaptar_text">Daftar poin misi <span class="hint">(untuk Misi)</span></label>
                        <textarea id="dhaptar_text" name="dhaptar_text" rows="10" placeholder="Satu baris satu poin misi">{{ $dhaptarText }}</textarea>
                        <div class="hint-row">
                            @error('dhaptar_text')
                                <span class="errmsg">{{ $message }}</span>
                            @else
                                <span class="hint">Tekan Enter untuk poin berikutnya. Pratinjau ada di panel kanan.</span>
                            @enderror
                        </div>
                    </div>

                    <div class="fgrid">
                        <div class="f">
                            <label for="urutan">Urutan tampil</label>
                            <input id="urutan" type="number" name="urutan" min="0" max="9999"
                                value="{{ old('urutan', $item->urutan ?? 0) }}">
                            <span class="hint">Angka lebih kecil tampil lebih dulu.</span>
                        </div>
                        <div class="f">
                            <label>Status publikasi</label>
                            <label class="chk">
                                <input type="hidden" name="urip" value="0">
                                <input type="checkbox" name="urip" value="1" @checked(old('urip', $item->urip ?? true))>
                                <span>
                                    <strong>Tampilkan di landing</strong>
                                    <small class="hint">Nonaktif = tersimpan, tidak tampil.</small>
                                </span>
                            </label>
                        </div>
                    </div>

                    <div class="factions">
                        <button type="submit" class="btn">{{ $isEdit ? 'Simpan perubahan' : 'Simpan' }}</button>
                        <a href="{{ route('superadmin.visimisi.index') }}" class="btn gh">Batal</a>
                    </div>
                </form>
            </div>

            {{-- Panel info kanan --}}
            <aside class="pg-side" aria-label="Informasi formulir">
                <div class="card">
                    <h3>Pratinjau di landing</h3>
                    <div class="pv">
                        <span class="tag" data-mirror="bagean"
                            data-empty="Visi">{{ $bagean === 'misi' ? 'Misi' : 'Visi' }}</span>
                        <h4 data-mirror="judhul" data-empty="Judul bagian">
                            {{ old('judhul', $item->judhul) ?: 'Judul bagian' }}</h4>
                        <p data-mirror="isi" data-empty="Narasi visi akan tampil di sini.">
                            {{ \Illuminate\Support\Str::limit(strip_tags(old('isi', $item->isi)), 400) ?: 'Narasi visi akan tampil di sini.' }}
                        </p>
                        <ol data-mirror-list="dhaptar_text"></ol>
                    </div>
                </div>

                <div class="card">
                    <h3>Status</h3>
                    <div class="kv"><span>Mode</span><strong>{{ $isEdit ? 'Edit' : 'Buat baru' }}</strong></div>
                    @if ($isEdit)
                        <div class="kv">
                            <span>Diperbarui</span>
                            <strong>{{ optional($item->updated_at)->timezone(config('app.timezone'))->format('d M Y H:i') ?? '—' }}</strong>
                        </div>
                        <div class="kv">
                            <span>Saat ini</span>
                            <span
                                class="badge {{ $item->urip ? 'ok' : 'err' }}">{{ $item->urip ? 'Aktif' : 'Nonaktif' }}</span>
                        </div>
                    @endif
                </div>

                <div class="card">
                    <h3>Panduan menulis</h3>
                    <ul class="tips">
                        <li><strong>Visi:</strong> satu kalimat tentang kondisi ideal yang ingin dicapai, lengkap dengan
                            target tahun.</li>
                        <li><strong>Misi:</strong> 3–6 poin langkah mencapai visi; satu kalimat per poin, diawali kata
                            kerja.</li>
                        <li>Selaraskan dengan dokumen resmi kampus agar konsisten dengan akreditasi.</li>
                    </ul>
                </div>

                @if ($isEdit)
                    <div class="card card--danger">
                        <h3>Zona berbahaya</h3>
                        <p class="hint" style="margin-bottom: 12px;">
                            Menghapus akan menghilangkan data ini dari daftar dan landing.
                        </p>
                        <form method="POST" action="{{ route('superadmin.visimisi.destroy', $item) }}"
                            data-confirm="Yakin hapus data ini?">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn sm dg" style="width: 100%;">Hapus data ini</button>
                        </form>
                    </div>
                @endif
            </aside>
        </div>
    </div>
</x-dashboard.layout.app>
