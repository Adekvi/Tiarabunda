@php
    $isEdit = $item->exists;
    $pageTitle = $isEdit ? 'Edit FAQ' : 'Tambah FAQ';
@endphp

<x-dashboard.layout.app :title="$pageTitle" :page-title="$pageTitle">
    <div class="g">

        {{-- Breadcrumb aksi atas --}}
        <div class="card" style="padding: 14px 22px;">
            <div class="ch2" style="margin-bottom: 0;">
                <div>
                    <p style="margin: 0 0 2px; font-size: 0.75rem; color: var(--mut);">
                        <a href="{{ route('superadmin.faq.index') }}" style="color: var(--pri); font-weight: 600;">FAQ</a>
                        <span style="opacity: 0.5;"> / </span>
                        {{ $isEdit ? 'Ubah' : 'Tambah' }}
                    </p>
                    <h2 style="margin: 0; font-size: 1.15rem;">
                        {{ $isEdit ? 'Ubah FAQ' : 'FAQ baru' }}
                    </h2>
                </div>
                <a href="{{ route('superadmin.faq.index') }}" class="btn gh">← Kembali ke daftar</a>
            </div>
        </div>

        @if ($errors->any())
            <div class="al w" role="alert">
                <strong style="display: block; margin-bottom: 6px;">Periksa kembali isian:</strong>
                <ul style="margin: 0; padding-left: 18px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Konten utama: form full + sidebar --}}
        <div class="g g2" style="align-items: start;">

            {{-- Kolom form --}}
            <div class="card" style="min-width: 0;">
                <h2 style="margin-bottom: 6px;">Detail FAQ</h2>
                <p style="margin: 0 0 20px; font-size: 0.85rem; color: var(--mut);">
                    Tulis pertanyaan dan jawaban dengan bahasa yang mudah dipahami calon mahasiswa.
                </p>

                <form method="POST" id="faqForm"
                    action="{{ $isEdit ? route('superadmin.faq.update', $item) : route('superadmin.faq.store') }}">
                    @csrf
                    @if ($isEdit)
                        @method('PUT')
                    @endif

                    <div class="f">
                        <label for="pitakon">
                            Pertanyaan <span style="color: var(--err);">*</span>
                        </label>
                        <input id="pitakon" type="text" name="pitakon" value="{{ old('pitakon', $item->pitakon) }}"
                            maxlength="255" required autofocus
                            placeholder="Contoh: Bagaimana cara mendaftar sebagai mahasiswa baru?">
                        <div style="display: flex; justify-content: space-between; gap: 8px;">
                            @error('pitakon')
                                <small style="color: var(--err);">{{ $message }}</small>
                            @else
                                <small style="color: var(--mut);">Maks. 255 karakter</small>
                            @enderror
                            <small style="color: var(--mut);" data-count-for="pitakon">0 / 255</small>
                        </div>
                    </div>

                    <div class="f">
                        <label for="wangsulan">
                            Jawaban <span style="color: var(--err);">*</span>
                        </label>
                        <textarea id="wangsulan" name="wangsulan" rows="3" required
                            placeholder="Tuliskan jawaban lengkap, langkah demi langkah jika perlu…">{{ old('wangsulan', $item->wangsulan) }}</textarea>
                        @error('wangsulan')
                            <small style="color: var(--err);">{{ $message }}</small>
                        @else
                            <small style="color: var(--mut);">
                                Jelaskan singkat, jelas, dan langsung ke inti.
                            </small>
                        @enderror
                    </div>

                    <div class="g g2" style="gap: 16px;">
                        <div class="f" style="margin-bottom: 0;">
                            <label for="urutan">Urutan tampil</label>
                            <input id="urutan" type="number" name="urutan" min="0" max="9999"
                                value="{{ old('urutan', $item->urutan ?? 0) }}">
                            <small style="color: var(--mut);">Angka lebih kecil tampil lebih dulu di landing.</small>
                            @error('urutan')
                                <small style="color: var(--err);">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="f" style="margin-bottom: 0;">
                            <label>Status publikasi</label>
                            <label
                                style="display: flex; align-items: flex-start; gap: 12px; padding: 12px 14px; border: 1px solid var(--line); border-radius: 10px; cursor: pointer; background: var(--bg); min-height: 48px;">
                                <input type="hidden" name="urip" value="0">
                                <input type="checkbox" name="urip" value="1"
                                    style="margin-top: 3px; width: 16px; height: 16px; accent-color: var(--pri);"
                                    @checked(old('urip', $item->urip ?? true))>
                                <span>
                                    <strong style="display: block; font-size: 0.9rem;">Tampilkan di landing</strong>
                                    <small style="color: var(--mut); font-weight: 500;">
                                        Nonaktif = tersimpan, tidak tampil di situs publik.
                                    </small>
                                </span>
                            </label>
                        </div>
                    </div>

                    <div
                        style="display: flex; gap: 10px; margin-top: 24px; padding-top: 20px; border-top: 1px solid var(--line); flex-wrap: wrap;">
                        <button type="submit" class="btn">
                            {{ $isEdit ? 'Simpan perubahan' : 'Simpan FAQ' }}
                        </button>
                        <a href="{{ route('superadmin.faq.index') }}" class="btn gh">Batal</a>
                    </div>
                </form>
            </div>

            {{-- Sidebar kanan: panduan + meta --}}
            <div class="g" style="gap: 16px;">
                <div class="card">
                    <h3 style="margin-bottom: 12px;">Panduan singkat</h3>
                    <ul
                        style="margin: 0; padding-left: 18px; color: var(--mut); font-size: 0.88rem; line-height: 1.65;">
                        <li>Satu FAQ = satu topik (jangan digabung).</li>
                        <li>Pertanyaan diawali kata tanya: bagaimana, apakah, berapa…</li>
                        <li>Jawaban langsung ke inti, maksimal 2–4 paragraf pendek.</li>
                        <li>Urutan 0–10 untuk FAQ prioritas di atas.</li>
                    </ul>
                </div>

                <div class="card">
                    <h3 style="margin-bottom: 12px;">Status form</h3>
                    <div style="display: grid; gap: 10px; font-size: 0.88rem;">
                        <div style="display: flex; justify-content: space-between; gap: 8px;">
                            <span style="color: var(--mut);">Mode</span>
                            <strong>{{ $isEdit ? 'Edit' : 'Buat baru' }}</strong>
                        </div>
                        @if ($isEdit)
                            <div style="display: flex; justify-content: space-between; gap: 8px;">
                                <span style="color: var(--mut);">ID</span>
                                <strong>#{{ $item->id }}</strong>
                            </div>
                            <div style="display: flex; justify-content: space-between; gap: 8px;">
                                <span style="color: var(--mut);">Diperbarui</span>
                                <strong>{{ optional($item->updated_at)->timezone(config('app.timezone'))->format('d M Y H:i') ?? '—' }}</strong>
                            </div>
                            <div style="display: flex; justify-content: space-between; gap: 8px; align-items: center;">
                                <span style="color: var(--mut);">Saat ini</span>
                                <span class="badge {{ $item->urip ? 'ok' : 'err' }}">
                                    {{ $item->urip ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </div>
                        @else
                            <p style="margin: 0; color: var(--mut); font-size: 0.85rem;">
                                Setelah disimpan, FAQ muncul di daftar dan bisa diaktifkan/nonaktifkan kapan saja.
                            </p>
                        @endif
                    </div>
                </div>

                @if ($isEdit)
                    <div class="card" style="border-color: color-mix(in srgb, var(--err) 25%, var(--line));">
                        <h3 style="margin-bottom: 8px; color: var(--err);">Zona berbahaya</h3>
                        <p style="margin: 0 0 14px; font-size: 0.85rem; color: var(--mut);">
                            Menghapus akan soft-delete data. Tidak tampil di landing maupun daftar aktif.
                        </p>
                        <form method="POST" action="{{ route('superadmin.faq.destroy', $item) }}"
                            onsubmit="return confirm('Yakin hapus FAQ ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn sm" style="background: var(--err); width: 100%;">
                                Hapus FAQ ini
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </div>

    @push('js')
        <script>
            (function() {
                const input = document.getElementById('pitakon');
                const counter = document.querySelector('[data-count-for="pitakon"]');
                if (!input || !counter) return;
                const sync = () => {
                    counter.textContent = input.value.length + ' / 255';
                };
                input.addEventListener('input', sync);
                sync();
            })();
        </script>
    @endpush
</x-dashboard.layout.app>
