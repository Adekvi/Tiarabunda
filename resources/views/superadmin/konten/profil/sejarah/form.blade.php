@php
    $isEdit = $item->exists;
    $pageTitle = $isEdit ? 'Edit Sejarah' : 'Tambah Sejarah';
    $kronologiText = old(
        'kronologi_text',
        collect($item->kronologi ?? [])
            ->map(fn($r) => trim(($r['tahun'] ?? '') . ' | ' . ($r['judul'] ?? '') . ' | ' . ($r['deskripsi'] ?? '')))
            ->implode("\n"),
    );
@endphp

<x-dashboard.layout.app :title="$pageTitle" :page-title="$pageTitle">
    <div class="g">
        {{-- Breadcrumb --}}
        <div class="card" style="padding: 14px 22px;">
            <div class="ch2" style="margin-bottom: 0;">
                <div>
                    <p style="margin: 0 0 2px; font-size: 0.75rem; color: var(--mut);">
                        <a href="{{ route('superadmin.sejarah.index') }}"
                            style="color: var(--pri); font-weight: 600;">Sejarah</a>
                        <span style="opacity: 0.5;"> / </span>{{ $isEdit ? 'Ubah' : 'Tambah' }}
                    </p>
                    <h2 style="margin: 0; font-size: 1.15rem;">{{ $pageTitle }}</h2>
                </div>
                <a href="{{ route('superadmin.sejarah.index') }}" class="btn gh">← Kembali ke daftar</a>
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
                <h2 style="margin-bottom: 4px;">Narasi sejarah</h2>
                <p style="margin: 0 0 20px; font-size: 0.85rem; color: var(--mut);">
                    Isi cerita berdirinya kampus. Kronologi bersifat opsional dan tampil sebagai garis waktu.
                </p>

                <form method="POST" enctype="multipart/form-data"
                    action="{{ $isEdit ? route('superadmin.sejarah.update', $item) : route('superadmin.sejarah.store') }}">
                    @csrf
                    @if ($isEdit)
                        @method('PUT')
                    @endif

                    <div class="f">
                        <label for="judhul">Judul <span class="req">*</span></label>
                        <input id="judhul" name="judhul" type="text" maxlength="255" required autofocus
                            value="{{ old('judhul', $item->judhul) }}"
                            placeholder="Contoh: Perjalanan Universitas Tiara Bunda">
                        <div class="hint-row">
                            @error('judhul')
                                <span class="errmsg">{{ $message }}</span>
                            @else
                                <span class="hint">Maks. 255 karakter</span>
                            @enderror
                            <span class="hint" data-count-for="judhul">0 / 255</span>
                        </div>
                    </div>

                    <div class="f">
                        <label for="isi">Isi sejarah <span class="req">*</span></label>
                        <textarea id="isi" name="isi" rows="14" required
                            placeholder="Tuliskan narasi sejarah kampus: awal berdiri, perkembangan, hingga kini…">{{ old('isi', $item->isi) }}</textarea>
                        <div class="hint-row">
                            @error('isi')
                                <span class="errmsg">{{ $message }}</span>
                            @else
                                <span class="hint">Pisahkan paragraf dengan baris kosong.</span>
                            @enderror
                            <span class="hint" data-count-for="isi">0 karakter</span>
                        </div>
                    </div>

                    <div class="f">
                        <label for="kronologi_text">Kronologi <span class="hint">(opsional)</span></label>
                        <textarea id="kronologi_text" name="kronologi_text" rows="7"
                            placeholder="Satu baris satu peristiwa&#10;2018 | Berdiri | Kampus didirikan di Depok">{{ $kronologiText }}</textarea>
                        @error('kronologi_text')
                            <span class="errmsg">{{ $message }}</span>
                        @else
                            <span class="hint">Format per baris: <code>Tahun | Judul | Deskripsi</code>. Pratinjau
                                garis waktu ada di panel kanan.</span>
                        @enderror
                    </div>

                    <div class="fgrid">
                        <div class="f">
                            <label for="path_sampul">Gambar sampul</label>
                            <input id="path_sampul" type="file" name="path_sampul" accept="image/*">
                            @error('path_sampul')
                                <span class="errmsg">{{ $message }}</span>
                            @else
                                <span class="hint">
                                    {{ $item->path_sampul ? 'Kosongkan jika tidak ingin mengganti gambar.' : 'JPG/PNG/WebP, rasio 16:9 disarankan.' }}
                                </span>
                            @enderror
                        </div>
                        <div class="f">
                            <label for="urutan">Urutan tampil</label>
                            <input id="urutan" type="number" name="urutan" min="0" max="9999"
                                value="{{ old('urutan', $item->urutan ?? 0) }}">
                            <span class="hint">Angka lebih kecil tampil lebih dulu.</span>
                        </div>
                    </div>

                    <div class="f" style="margin-top: 16px;">
                        <label>Status publikasi</label>
                        <label class="chk">
                            <input type="hidden" name="urip" value="0">
                            <input type="checkbox" name="urip" value="1" @checked(old('urip', $item->urip ?? true))>
                            <span>
                                <strong>Tampilkan di landing</strong>
                                <small class="hint">Nonaktif = tersimpan, tidak tampil di situs publik.</small>
                            </span>
                        </label>
                    </div>

                    <div class="factions">
                        <button type="submit"
                            class="btn">{{ $isEdit ? 'Simpan perubahan' : 'Simpan sejarah' }}</button>
                        <a href="{{ route('superadmin.sejarah.index') }}" class="btn gh">Batal</a>
                    </div>
                </form>
            </div>

            {{-- Panel info kanan --}}
            <aside class="pg-side" aria-label="Informasi formulir">
                <div class="card">
                    <h3>Pratinjau di landing</h3>
                    <div class="pv">
                        <span class="tag">Sejarah</span>
                        <h4 data-mirror="judhul" data-empty="Judul sejarah">
                            {{ old('judhul', $item->judhul) ?: 'Judul sejarah' }}</h4>
                        <p data-mirror="isi" data-empty="Narasi sejarah akan tampil di sini.">
                            {{ \Illuminate\Support\Str::limit(strip_tags(old('isi', $item->isi)), 400) ?: 'Narasi sejarah akan tampil di sini.' }}
                        </p>
                    </div>
                    @if ($item->path_sampul)
                        <img class="cover" style="margin-top: 12px;"
                            src="{{ asset('storage/' . $item->path_sampul) }}" alt="Sampul sejarah saat ini"
                            loading="lazy">
                    @endif
                </div>

                <div class="card">
                    <h3>Garis waktu</h3>
                    <ol class="tl" data-mirror-timeline="kronologi_text"></ol>
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
                        <li>Urutkan cerita dari awal berdiri hingga capaian terbaru.</li>
                        <li>Sebutkan tahun, nama pendiri, dan izin resmi secara tepat.</li>
                        <li>Gunakan kalimat singkat; satu paragraf satu gagasan.</li>
                        <li>Peristiwa penting masukkan ke kronologi, bukan diulang di narasi.</li>
                    </ul>
                </div>

                @if ($isEdit)
                    <div class="card card--danger">
                        <h3>Zona berbahaya</h3>
                        <p class="hint" style="margin-bottom: 12px;">
                            Menghapus akan soft-delete data ini dan menghilangkannya dari landing.
                        </p>
                        <form method="POST" action="{{ route('superadmin.sejarah.destroy', $item) }}"
                            data-confirm="Yakin hapus konten sejarah ini?">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn sm dg" style="width: 100%;">Hapus sejarah ini</button>
                        </form>
                    </div>
                @endif
            </aside>
        </div>
    </div>
</x-dashboard.layout.app>
