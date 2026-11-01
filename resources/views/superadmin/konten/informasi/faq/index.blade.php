<x-dashboard.layout.app title="FAQ" page-title="FAQ">
    <div class="g">

        {{-- Flash --}}
        @if (session('success'))
            <div class="al" role="status">{{ session('success') }}</div>
        @endif

        {{-- Header --}}
        <div class="card">
            <div class="ch2">
                <div>
                    <h2 style="margin-bottom: 4px;">Kelola FAQ</h2>
                    <p style="margin: 0; font-size: 0.85rem; color: var(--mut);">
                        Pertanyaan yang tampil di halaman landing. Nonaktifkan tanpa menghapus data.
                    </p>
                </div>
                <a href="{{ route('superadmin.faq.create') }}" class="btn">+ Tambah FAQ</a>
            </div>

            {{-- Filter --}}
            <form method="GET" action="{{ route('superadmin.faq.index') }}" class="g"
                style="grid-template-columns: 1fr auto auto; gap: 12px; align-items: end; margin-top: 8px;">
                <div class="f" style="margin-bottom: 0;">
                    <label for="q">Cari</label>
                    <input id="q" type="search" name="q" value="{{ request('q') }}"
                        placeholder="Cari pertanyaan atau jawaban…" autocomplete="off">
                    
                </div>
                <div class="f" style="margin-bottom: 0; min-width: 160px;">
                    <label for="status">Status</label>
                    <select id="status" name="status">
                        <option value="">Semua</option>
                        <option value="active" @selected(request('status') === 'active')>Aktif</option>
                        <option value="inactive" @selected(request('status') === 'inactive')>Nonaktif</option>
                    </select>
                </div>
                <div style="display: flex; gap: 8px;">
                    <button type="submit" class="btn">Terapkan</button>
                    @if (request()->hasAny(['q', 'status']))
                        <a href="{{ route('superadmin.faq.index') }}" class="btn gh">Reset</a>
                    @endif
                </div>
            </form>
        </div>

        {{-- Tabel --}}
        <div class="card">
            <div class="ch2">
                <h2 style="margin: 0;">Daftar FAQ</h2>
                <span style="font-size: 0.8rem; color: var(--mut);">
                    {{ $items->total() }} data
                </span>
            </div>

            @if ($items->isEmpty())
                <div style="padding: 36px 12px; text-align: center; color: var(--mut);">
                    <p style="margin: 0 0 12px; font-weight: 600; color: var(--ink);">Belum ada FAQ</p>
                    <p style="margin: 0 0 18px; font-size: 0.9rem;">
                        Tambahkan pertanyaan yang sering diajukan calon mahasiswa.
                    </p>
                    <a href="{{ route('superadmin.faq.create') }}" class="btn">+ Tambah FAQ pertama</a>
                </div>
            @else
                <div class="tw">
                    <table>
                        <thead>
                            <tr>
                                <th style="width: 48px;">#</th>
                                <th>Pertanyaan</th>
                                <th>Jawaban</th>
                                <th style="width: 88px;">Urutan</th>
                                <th style="width: 110px;">Status</th>
                                <th style="width: 200px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($items as $item)
                                <tr>
                                    <td style="color: var(--mut);">
                                        {{ $items->firstItem() + $loop->index }}
                                    </td>
                                    <td style="white-space: normal; max-width: 280px; font-weight: 600;">
                                        {{ $item->pitakon }}
                                    </td>
                                    <td style="white-space: normal; max-width: 360px; color: var(--mut);">
                                        {{ \Illuminate\Support\Str::limit(strip_tags($item->wangsulan), 100) }}
                                    </td>
                                    <td>{{ $item->urutan }}</td>
                                    <td>
                                        <span class="badge {{ $item->urip ? 'ok' : 'err' }}">
                                            {{ $item->urip ? 'Aktif' : 'Nonaktif' }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="row-actions" role="group" aria-label="Aksi FAQ">
                                            {{-- Edit --}}
                                            <a href="{{ route('superadmin.faq.edit', $item) }}" class="ia"
                                                title="Edit" aria-label="Edit FAQ">
                                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                    stroke-linejoin="round" aria-hidden="true">
                                                    <path d="M12 20h9" />
                                                    <path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z" />
                                                </svg>
                                            </a>

                                            {{-- Toggle aktif / nonaktif --}}
                                            <form method="POST" action="{{ route('superadmin.faq.toggle', $item) }}">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit"
                                                    class="ia {{ $item->urip ? 'ia-mute' : 'ia-ok' }}"
                                                    title="{{ $item->urip ? 'Nonaktifkan' : 'Aktifkan' }}"
                                                    aria-label="{{ $item->urip ? 'Nonaktifkan FAQ' : 'Aktifkan FAQ' }}">
                                                    @if ($item->urip)
                                                        {{-- Eye off --}}
                                                        <svg width="16" height="16" viewBox="0 0 24 24"
                                                            fill="none" stroke="currentColor" stroke-width="2"
                                                            stroke-linecap="round" stroke-linejoin="round"
                                                            aria-hidden="true">
                                                            <path
                                                                d="M17.94 17.94A10.9 10.9 0 0 1 12 20c-5 0-9.27-3.11-11-8 1.02-2.89 2.94-5.2 5.36-6.53" />
                                                            <path d="M1 1l22 22" />
                                                            <path
                                                                d="M9.9 4.24A10.9 10.9 0 0 1 12 4c5 0 9.27 3.11 11 8a11.7 11.7 0 0 1-2.16 3.19" />
                                                            <path d="M14.12 14.12a3 3 0 1 1-4.24-4.24" />
                                                        </svg>
                                                    @else
                                                        {{-- Eye --}}
                                                        <svg width="16" height="16" viewBox="0 0 24 24"
                                                            fill="none" stroke="currentColor" stroke-width="2"
                                                            stroke-linecap="round" stroke-linejoin="round"
                                                            aria-hidden="true">
                                                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8S1 12 1 12z" />
                                                            <circle cx="12" cy="12" r="3" />
                                                        </svg>
                                                    @endif
                                                </button>
                                            </form>

                                            {{-- Hapus --}}
                                            <form method="POST" action="{{ route('superadmin.faq.destroy', $item) }}"
                                                onsubmit="return confirm('Hapus FAQ ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="ia ia-danger" title="Hapus"
                                                    aria-label="Hapus FAQ">
                                                    <svg width="16" height="16" viewBox="0 0 24 24"
                                                        fill="none" stroke="currentColor" stroke-width="2"
                                                        stroke-linecap="round" stroke-linejoin="round"
                                                        aria-hidden="true">
                                                        <path d="M3 6h18" />
                                                        <path d="M8 6V4h8v2" />
                                                        <path d="M19 6l-1 14H6L5 6" />
                                                        <path d="M10 11v6" />
                                                        <path d="M14 11v6" />
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if ($items->hasPages())
                    <div
                        style="margin-top: 20px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;">
                        <p style="margin: 0; font-size: 0.82rem; color: var(--mut);">
                            Menampilkan {{ $items->firstItem() }}–{{ $items->lastItem() }}
                            dari {{ $items->total() }}
                        </p>
                        <div>
                            {{ $items->links() }}
                        </div>
                    </div>
                @endif
            @endif
        </div>
    </div>
</x-dashboard.layout.app>
