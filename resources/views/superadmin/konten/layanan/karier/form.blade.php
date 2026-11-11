@php
    $isEdit = $item->exists;
    $pageTitle = $isEdit ? 'Edit Pusat Karier' : 'Tambah Pusat Karier';
    $meta = $item->data_tambahan ?? [];
@endphp

<x-dashboard.layout.app :title="$pageTitle" :page-title="$pageTitle">
    <div class="krr-page">
        <div class="krr-layout">
            {{-- Kolom Konten Utama --}}
            <div class="krr-main">
                <div class="krr-hero">
                    <div>
                        <div class="krr-hero__crumb">
                            <a href="{{ route('superadmin.karir.index') }}">Pusat Karir</a>
                            <span>/</span>
                            <span class="is-current">{{ $isEdit ? 'Ubah' : 'Tambah' }}</span>
                        </div>
                        <h1 class="krr-hero__title">{{ $pageTitle }}</h1>
                        <p class="krr-hero__desc">
                            {{ $isEdit
                                ? 'Perbarui informasi layanan yang tampil di landing page.'
                                : 'Tambahkan layanan karier, magang, atau lowongan baru.' }}
                        </p>
                    </div>
                    <a href="{{ route('superadmin.karir.index') }}" class="btn gh krr-hero__cta">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M19 12H5" />
                            <path d="M12 19l-7-7 7-7" />
                        </svg>
                        Kembali
                    </a>
                </div>

                @if ($errors->any())
                    <div class="krr-alert krr-alert--error" role="alert">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <circle cx="12" cy="12" r="10" />
                            <line x1="12" y1="8" x2="12" y2="12" />
                            <line x1="12" y1="16" x2="12.01" y2="16" />
                        </svg>
                        <ul>
                            @foreach ($errors->all() as $e)
                                <li>{{ $e }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="card">
                    <div class="krr-form-head">
                        <h2>Detail konten</h2>
                        <p>Informasi untuk mahasiswa dan alumni terkait karier dan peluang kerja.</p>
                    </div>

                    <form method="POST" class="krr-form"
                        action="{{ $isEdit ? route('superadmin.karir.update', $item) : route('superadmin.karir.store') }}">
                        @csrf
                        @if ($isEdit)
                            @method('PUT')
                        @endif

                        {{-- Judul --}}
                        <div class="f">
                            <label for="judhul">
                                Judul <span class="krr-req">*</span>
                            </label>
                            <input id="judhul" name="judhul" type="text" required maxlength="150"
                                value="{{ old('judhul', $item->judhul) }}"
                                placeholder="Contoh: Lowongan Mitra / Bimbingan Karier">
                            <small class="krr-help">Nama program, layanan, atau lowongan.</small>
                        </div>

                        {{-- Deskripsi --}}
                        <div class="f">
                            <label for="isi">Deskripsi</label>
                            <textarea id="isi" name="isi" rows="8" placeholder="Detail program, persyaratan, jadwal…">{{ old('isi', $item->isi) }}</textarea>
                        </div>

                        {{-- Tautan --}}
                        <div class="f">
                            <label for="url_portal">Tautan (job board / form)</label>
                            <input id="url_portal" name="url_portal" type="url" maxlength="255"
                                value="{{ old('url_portal', data_get($meta, 'url_portal')) }}" placeholder="https://…">
                            <small class="krr-help">Opsional — portal lowongan atau formulir pendaftaran.</small>
                        </div>

                        {{-- Kontak & Email --}}
                        <div class="krr-form-row">
                            <div class="f">
                                <label for="kontak">Kontak / telepon</label>
                                <input id="kontak" name="kontak" type="text" maxlength="100"
                                    value="{{ old('kontak', data_get($meta, 'kontak')) }}"
                                    placeholder="(021) 000-0000">
                            </div>
                            <div class="f">
                                <label for="email">Email</label>
                                <input id="email" name="email" type="email" maxlength="150"
                                    value="{{ old('email', data_get($meta, 'email')) }}"
                                    placeholder="karier@kampus.ac.id">
                            </div>
                        </div>

                        {{-- Lokasi & Urutan --}}
                        <div class="krr-form-row">
                            <div class="f">
                                <label for="lokasi">Lokasi / ruang</label>
                                <input id="lokasi" name="lokasi" type="text" maxlength="150"
                                    value="{{ old('lokasi', data_get($meta, 'lokasi')) }}"
                                    placeholder="Gedung B lantai 1">
                            </div>
                            <div class="f krr-form-narrow">
                                <label for="urutan">Urutan tampil</label>
                                <input id="urutan" type="number" name="urutan" min="0" max="9999"
                                    value="{{ old('urutan', $item->urutan ?? 0) }}">
                            </div>
                        </div>

                        {{-- Switch publikasi --}}
                        <div class="f">
                            <span class="krr-flabel">Publikasi</span>
                            <label class="krr-switch" for="urip">
                                <input type="hidden" name="urip" value="0">
                                <input id="urip" class="krr-switch__input" type="checkbox" name="urip"
                                    value="1" @checked((bool) old('urip', $item->urip ?? true))>
                                <span class="krr-switch__track" aria-hidden="true"></span>
                                <span class="krr-switch__text">
                                    <strong>Tampilkan di situs publik</strong>
                                    <small>Nonaktif = tersimpan, tidak tampil di landing.</small>
                                </span>
                                <span class="krr-switch__state is-on">Tampil</span>
                                <span class="krr-switch__state is-off">Disembunyikan</span>
                            </label>
                        </div>

                        {{-- Actions --}}
                        <div class="krr-form-actions">
                            <button type="submit" class="btn">
                                {{ $isEdit ? 'Simpan perubahan' : 'Simpan' }}
                            </button>
                            <a href="{{ route('superadmin.karir.index') }}" class="btn gh">Batal</a>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Sidebar kanan --}}
            <aside class="krr-aside">
                {{-- Preview konten (informatif) --}}
                <div class="card krr-side-card">
                    <h3 class="krr-side-title">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8S1 12 1 12z" />
                            <circle cx="12" cy="12" r="3" />
                        </svg>
                        Pratinjau
                    </h3>
                    <div class="krr-preview">
                        <span class="krr-preview__title" id="krrPreviewTitle">
                            {{ old('judhul', $item->judhul) ?: 'Judul konten' }}
                        </span>
                        <span class="krr-preview__desc" id="krrPreviewDesc">
                            {{ \Illuminate\Support\Str::limit(strip_tags(old('isi', $item->isi) ?: 'Deskripsi akan tampil di sini.'), 90) }}
                        </span>
                        <div class="krr-preview__meta">
                            @php $isOn = (bool) old('urip', $item->urip ?? true); @endphp
                            <span class="krr-preview__badge {{ $isOn ? 'is-on' : 'is-off' }}" id="krrPreviewStatus">
                                <i></i>
                                {{ $isOn ? 'Aktif' : 'Nonaktif' }}
                            </span>
                            <span class="krr-preview__order" id="krrPreviewOrder">
                                Urutan: {{ old('urutan', $item->urutan ?? 0) }}
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Panduan --}}
                <div class="card krr-side-card">
                    <h3 class="krr-side-title">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M9 18h6" />
                            <path d="M10 22h4" />
                            <path d="M12 2a7 7 0 0 0-4 12.7c.6.5 1 1.3 1 2.3h6c0-1 .4-1.8 1-2.3A7 7 0 0 0 12 2z" />
                        </svg>
                        Panduan pengisian
                    </h3>
                    <ul class="krr-tips">
                        <li>Satu blok per program atau jenis layanan.</li>
                        <li>Isi tautan jika ada form/portal eksternal.</li>
                        <li>Kontak memudahkan alumni &amp; mahasiswa.</li>
                        <li>Atur <strong>urutan</strong> untuk mengatur prioritas tampil.</li>
                        <li>Nonaktifkan jika belum siap dipublikasikan.</li>
                    </ul>
                </div>

                {{-- Status form --}}
                <div class="card krr-side-card">
                    <h3 class="krr-side-title">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <circle cx="12" cy="12" r="10" />
                            <path d="M12 16v-4" />
                            <path d="M12 8h.01" />
                        </svg>
                        Status form
                    </h3>
                    <dl class="krr-kv">
                        <div>
                            <dt>Mode</dt>
                            <dd>
                                <span class="krr-badge {{ $isEdit ? 'is-info' : 'is-ok' }}">
                                    {{ $isEdit ? 'Edit' : 'Buat baru' }}
                                </span>
                            </dd>
                        </div>
                        @if ($isEdit)
                            <div>
                                <dt>Dibuat</dt>
                                <dd>{{ $item->created_at?->translatedFormat('d M Y · H:i') ?? '—' }}</dd>
                            </div>
                            <div>
                                <dt>Diperbarui</dt>
                                <dd>{{ $item->updated_at?->diffForHumans() ?? '—' }}</dd>
                            </div>
                        @endif
                    </dl>
                </div>
            </aside>
        </div>
    </div>

    @push('css')
        <link rel="stylesheet" href="{{ asset('admin/landing/layanan/karir/krr.css') }}">
        <link rel="stylesheet" href="{{ asset('admin/landing/layanan/karir/form.css') }}">
    @endpush

    @push('js')
        <script src="{{ asset('admin/landing/layanan/karir/form.js') }}" defer></script>
    @endpush
</x-dashboard.layout.app>
