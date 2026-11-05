 @if ($items->isEmpty())
     <div class="faq-empty">
         <div class="faq-empty__icon" aria-hidden="true">
             <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"
                 stroke-linecap="round" stroke-linejoin="round">
                 <path d="M5 4h14v16H5zM8 8h8M8 12h8M8 16h5" />
             </svg>
         </div>
         <p class="faq-empty__title">
             {{ $filtered ?? false ? 'Tidak ada berita yang cocok' : 'Belum ada berita' }}
         </p>
         <p class="faq-empty__desc">
             {{ $filtered ?? false ? 'Coba ubah kata kunci atau reset filter.' : 'Mulai dengan menulis berita pertama kampus.' }}
         </p>
         @if ($filtered ?? false)
             <a href="{{ route('superadmin.berita.index') }}" class="btn gh">Reset filter</a>
         @else
             <a href="{{ route('superadmin.berita.create') }}" class="btn">Tambah berita</a>
         @endif
     </div>
 @else
     <div class="tw">
         <table>
             <thead>
                 <tr>
                     <th class="nw-col-nomor">No</th>
                     <th class="nw-col-foto">Foto</th>
                     <th class="nw-col-judul">Judul</th>
                     <th class="nw-col-lokasi">Lokasi</th>
                     <th class="nw-col-jadwal">Jadwal</th>
                     <th class="nw-col-status">Status</th>
                     <th class="nw-col-aksi">Aksi</th>
                 </tr>
             </thead>
             <tbody>
                 @foreach ($items as $item)
                     @php
                         $terjadwal = $item->wektu_terbit && $item->wektu_terbit->isFuture();
                         if (!$item->urip) {
                             [$stCls, $stTxt] = ['err', 'Nonaktif'];
                         } elseif ($terjadwal) {
                             [$stCls, $stTxt] = ['warn', 'Terjadwal'];
                         } else {
                             [$stCls, $stTxt] = ['ok', 'Terbit'];
                         }
                         $golonganLabel = $item->golongan
                             ? \App\Models\Informasi\Berita::GOLONGAN[$item->golongan] ?? $item->golongan
                             : null;
                     @endphp
                     <tr>
                         <td class="nw-col-nomor">
                             {{ $items->firstItem() + $loop->index }}
                         </td>
                         <td>
                             @if ($item->thumb_url)
                                 <img src="{{ $item->thumb_url }}" alt="" width="48" height="48"
                                     loading="lazy" decoding="async"
                                     style="width:48px;height:48px;object-fit:cover;border-radius:8px;border:1px solid var(--line);">
                             @else
                                 <span
                                     style="display:inline-grid;place-items:center;width:48px;height:48px;border-radius:8px;background:var(--bg);color:var(--mut);font-size:0.7rem;">—</span>
                             @endif
                         </td>
                         <td style="white-space:normal;max-width:280px;">
                             <strong>{{ $item->judhul }}</strong>
                             @if ($item->ringkesan)
                                 <div style="color:var(--mut);font-size:0.82rem;margin-top:2px;">
                                     {{ \Illuminate\Support\Str::limit($item->ringkesan, 70) }}
                                 </div>
                             @endif
                         </td>
                         <td style="white-space:normal;font-size:0.85rem;color:var(--mut);">
                             {{ $item->lokasi ?: '—' }}
                         </td>
                         <td style="font-size:0.85rem;color:var(--mut);white-space:normal;">
                             @if ($item->wektu_mulai)
                                 {{ $item->wektu_mulai->format('d M Y H:i') }}
                                 @if ($item->wektu_selesai)
                                     <br>– {{ $item->wektu_selesai->format('d M Y H:i') }}
                                 @endif
                             @else
                                 —
                             @endif
                         </td>
                         <td>
                             <span class="badge {{ $item->urip ? 'ok' : 'err' }}">
                                 {{ $item->urip ? 'Aktif' : 'Nonaktif' }}
                             </span>
                         </td>
                         <td>
                             <div class="row-actions" role="group">
                                 <a href="{{ route('superadmin.kegiatan.edit', $item) }}" class="ia" title="Edit"
                                     aria-label="Edit">
                                     <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                         stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                         stroke-linejoin="round" aria-hidden="true">
                                         <path d="M12 20h9" />
                                         <path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4 12.5-12.5z" />
                                     </svg>
                                 </a>
                                 <form method="POST" action="{{ route('superadmin.kegiatan.toggle', $item) }}">
                                     @csrf @method('PATCH')
                                     <button type="submit" class="ia {{ $item->urip ? 'ia-mute' : 'ia-ok' }}"
                                         title="{{ $item->urip ? 'Nonaktifkan' : 'Aktifkan' }}">
                                         @if ($item->urip)
                                             <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                                 stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                 stroke-linejoin="round" aria-hidden="true">
                                                 <path
                                                     d="M17.94 17.94A10.9 10.9 0 0 1 12 20c-5 0-9.27-3.11-11-8 1-2.9 2.9-5.2 5.4-6.5" />
                                                 <path d="M1 1l22 22" />
                                                 <path
                                                     d="M9.9 4.24A10.9 10.9 0 0 1 12 4c5 0 9.27 3.11 11 8a11.7 11.7 0 0 1-2.2 3.2" />
                                                 <path d="M14.12 14.12a3 3 0 1 1-4.24-4.24" />
                                             </svg>
                                         @else
                                             <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                                 stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                 stroke-linejoin="round" aria-hidden="true">
                                                 <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8S1 12 1 12z" />
                                                 <circle cx="12" cy="12" r="3" />
                                             </svg>
                                         @endif
                                     </button>
                                 </form>
                                 <form method="POST" action="{{ route('superadmin.kegiatan.destroy', $item) }}"
                                     onsubmit="return confirm('Hapus kegiatan ini?');">
                                     @csrf @method('DELETE')
                                     <button type="submit" class="ia ia-danger" title="Hapus">
                                         <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                             stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                             stroke-linejoin="round" aria-hidden="true">
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
         <div class="faq-pager">
             <p>Menampilkan {{ $items->firstItem() }}–{{ $items->lastItem() }} dari
                 {{ $items->total() }}</p>
             <div>{{ $items->links() }}</div>
         </div>
     @endif
 @endif
