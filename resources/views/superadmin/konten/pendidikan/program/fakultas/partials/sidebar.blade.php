 <aside class="fac-aside">
     <div class="fac-hero">
         <div class="fac-hero__head">
             <span class="fac-hero__label">Total fakultas</span>
             <span class="fac-hero__badge">{{ $stats['aktif'] }} aktif</span>
         </div>
         <div class="fac-hero__value">{{ $stats['total'] }}</div>

         @php
             $total = max($stats['total'], 1);
             $pctAktif = round(($stats['aktif'] / $total) * 100);
             $pctNonaktif = round(($stats['nonaktif'] / $total) * 100);
         @endphp

         <div class="fac-bar" role="img"
             aria-label="Proporsi aktif {{ $pctAktif }}%, nonaktif {{ $pctNonaktif }}%">
             <span class="fac-bar__ok" style="width: {{ $pctAktif }}%"></span>
             <span class="fac-bar__err" style="width: {{ $pctNonaktif }}%"></span>
         </div>
         <div class="fac-bar__legend">
             <span><i class="dot dot-ok"></i> Aktif {{ $stats['aktif'] }}</span>
             <span><i class="dot dot-err"></i> Nonaktif {{ $stats['nonaktif'] }}</span>
         </div>

         <div class="fac-hero__grid">
             <div class="fac-hero__cell">
                 <span class="fac-hero__cell-label">Program studi</span>
                 <span class="fac-hero__cell-value">{{ $stats['prodi'] }}</span>
             </div>
             <div class="fac-hero__cell">
                 <span class="fac-hero__cell-label">Di sampah</span>
                 <span class="fac-hero__cell-value {{ $stats['terhapus'] > 0 ? 'is-err' : '' }}">
                     {{ $stats['terhapus'] }}
                 </span>
             </div>
         </div>
     </div>

     {{-- 2. Aksi cepat --}}
     <div class="fac-card">
         <h3 class="fac-card__title">Aksi cepat</h3>
         <div class="fac-actions">
             <a href="{{ route('superadmin.fakultas.create') }}" class="fac-action">
                 <span class="fac-action__ico" style="--c:var(--pri)">
                     <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                         stroke-width="2.2" stroke-linecap="round" aria-hidden="true">
                         <path d="M12 5v14" />
                         <path d="M5 12h14" />
                     </svg>
                 </span>
                 <span class="fac-action__txt">
                     <strong>Tambah fakultas</strong>
                     <small>Buat unit baru</small>
                 </span>
             </a>

             @if ($stats['terhapus'] > 0 && !$trashed)
                 <a href="{{ route('superadmin.fakultas.trashed') }}" class="fac-action">
                     <span class="fac-action__ico" style="--c:var(--err)">
                         <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                             stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                             <path d="M3 6h18" />
                             <path d="M8 6V4h8v2" />
                             <path d="M19 6l-1 14H6L5 6" />
                         </svg>
                     </span>
                     <span class="fac-action__txt">
                         <strong>Lihat sampah</strong>
                         <small>{{ $stats['terhapus'] }} item menunggu</small>
                     </span>
                     <span class="fac-action__count">{{ $stats['terhapus'] }}</span>
                 </a>
             @endif

             @if ($trashed)
                 <a href="{{ route('superadmin.fakultas.index') }}" class="fac-action">
                     <span class="fac-action__ico" style="--c:var(--mut)">
                         <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                             stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                             <path d="M19 12H5" />
                             <path d="M12 19l-7-7 7-7" />
                         </svg>
                     </span>
                     <span class="fac-action__txt">
                         <strong>Kembali ke daftar</strong>
                         <small>Keluar dari sampah</small>
                     </span>
                 </a>
             @endif
         </div>
     </div>

     {{-- 3. Info ringkas --}}
     <div class="fac-card fac-card--info">
         <div class="fac-info">
             <span class="fac-info__ico" aria-hidden="true">
                 <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                     stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                     <circle cx="12" cy="12" r="9" />
                     <path d="M12 8v.01" />
                     <path d="M11 12h1v5h1" />
                 </svg>
             </span>
             <div>
                 <strong class="fac-info__title">Tentang Fakultas</strong>
                 <p class="fac-info__desc">
                     Fakultas mengelompokkan prodi. Hanya data <b>aktif</b> yang tampil di
                     <b>Pendidikan → Program Pendidikan</b>.
                 </p>
             </div>
         </div>
     </div>

     {{-- 4. Zona berbahaya — hanya di mode sampah --}}
     @if ($trashed && $stats['terhapus'] > 0)
         <div class="fac-card fac-card--danger">
             <h3 class="fac-card__title fac-card__title--danger">
                 <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                     stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                     <path
                         d="M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z" />
                     <path d="M12 9v4" />
                     <path d="M12 17h.01" />
                 </svg>
                 Zona berbahaya
             </h3>
             <p class="fac-card__desc">
                 Hapus permanen seluruh fakultas di sampah. Prodi terkait akan dilewati otomatis.
             </p>
             <form method="POST" action="{{ route('superadmin.fakultas.empty-trash') }}"
                 onsubmit="return confirm('Kosongkan semua sampah fakultas? Tidak bisa dibatalkan.');">
                 @csrf @method('DELETE')
                 <button type="submit" class="fac-btn-danger">Kosongkan sampah</button>
             </form>
         </div>
     @endif
 </aside>
