@php
    $isEdit = $item->exists;
    $pageTitle = $isEdit ? 'Edit Fakultas' : 'Tambah Fakultas';
@endphp

<x-dashboard.layout.app :title="$pageTitle" :page-title="$pageTitle">
    <div class="g">
        <div class="card" style="padding:14px 22px;">
            <div class="ch2" style="margin-bottom:0;">
                <div>
                    <p style="margin:0 0 2px;font-size:0.75rem;color:var(--mut);">
                        <a href="{{ route('superadmin.fakultas.index') }}"
                            style="color:var(--pri);font-weight:600;">Fakultas</a>
                        <span style="opacity:.5;"> / </span>{{ $isEdit ? 'Ubah' : 'Tambah' }}
                    </p>
                    <h2 style="margin:0;font-size:1.15rem;">{{ $pageTitle }}</h2>
                </div>
                <a href="{{ route('superadmin.fakultas.index') }}" class="btn gh">← Kembali</a>
            </div>
        </div>

        @if ($errors->any())
            <div class="al w" role="alert">
                <ul style="margin:0;padding-left:18px;">
                    @foreach ($errors->all() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="g g2" style="align-items:start;">
            <div class="card" style="min-width:0;">
                <h2 style="margin-bottom:6px;">Detail fakultas</h2>
                <p style="margin:0 0 20px;font-size:0.85rem;color:var(--mut);">Lengkapi identitas fakultas untuk
                    pengelompokan prodi.</p>

                <form method="POST"
                    action="{{ $isEdit ? route('superadmin.fakultas.update', $item) : route('superadmin.fakultas.store') }}">
                    @csrf
                    @if ($isEdit)
                        @method('PUT')
                    @endif

                    <div class="f">
                        <label for="jeneng">Nama fakultas <span style="color:var(--err)">*</span></label>
                        <input id="jeneng" name="jeneng" type="text" required maxlength="150"
                            value="{{ old('jeneng', $item->jeneng) }}" placeholder="Contoh: Fakultas Kesehatan">
                    </div>

                    <div class="g g2" style="gap:16px;">
                        <div class="f" style="margin-bottom:0;">
                            <label for="kodhe">Kode (opsional)</label>
                            <input id="kodhe" name="kodhe" type="text" maxlength="20"
                                value="{{ old('kodhe', $item->kodhe) }}" placeholder="Contoh: FKS">
                        </div>
                        <div class="f" style="margin-bottom:0;">
                            <label for="tandha">Slug URL (opsional)</label>
                            <input id="tandha" name="tandha" type="text" maxlength="150"
                                value="{{ old('tandha', $item->tandha) }}" placeholder="Otomatis dari nama jika kosong">
                        </div>
                    </div>

                    <div class="f">
                        <label for="katrangan">Deskripsi singkat</label>
                        <textarea id="katrangan" name="katrangan" rows="5" placeholder="Ringkasan fakultas…">{{ old('katrangan', $item->katrangan) }}</textarea>
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

                    <div
                        style="display:flex;gap:10px;margin-top:24px;padding-top:20px;border-top:1px solid var(--line);">
                        <button type="submit" class="btn">{{ $isEdit ? 'Simpan perubahan' : 'Simpan' }}</button>
                        <a href="{{ route('superadmin.fakultas.index') }}" class="btn gh">Batal</a>
                    </div>
                </form>
            </div>

            <div class="g" style="gap:16px;">
                <div class="card">
                    <h3 style="margin-bottom:10px;">Panduan</h3>
                    <ul style="margin:0;padding-left:18px;color:var(--mut);font-size:0.88rem;line-height:1.7;">
                        <li>Nama harus unik dan mudah dikenali.</li>
                        <li>Slug dipakai di URL; biarkan kosong untuk generate otomatis.</li>
                        <li>Nonaktifkan fakultas jika belum siap ditampilkan.</li>
                    </ul>
                </div>
                <div class="card">
                    <h3 style="margin-bottom:10px;">Status form</h3>
                    <div style="display:flex;justify-content:space-between;font-size:0.88rem;">
                        <span style="color:var(--mut);">Mode</span>
                        <strong>{{ $isEdit ? 'Edit' : 'Buat baru' }}</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('css')
        <link rel="stylesheet" href="{{ asset('admin/landing/pendidikan/fakultas/form.css') }}">
    @endpush

</x-dashboard.layout.app>
