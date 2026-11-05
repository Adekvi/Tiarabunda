 <div class="card">
     <div class="ch2">
         <div>
             <h2 style="margin-bottom:4px;">
                 {{ $trashed ? 'Sampah Fakultas' : 'Kelola Fakultas' }}
             </h2>
             <p style="margin:0;font-size:0.85rem;color:var(--mut);">
                 {{ $trashed
                     ? 'Fakultas yang dihapus. Pulihkan atau hapus permanen.'
                     : 'Unit induk program studi yang tampil di menu Pendidikan.' }}
             </p>
         </div>
         @if ($trashed)
             <a href="{{ route('superadmin.fakultas.index') }}" class="btn gh">← Kembali</a>
         @else
             <a href="{{ route('superadmin.fakultas.create') }}" class="btn">
                 <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                     stroke-width="2.2" stroke-linecap="round" aria-hidden="true">
                     <path d="M12 5v14" />
                     <path d="M5 12h14" />
                 </svg>
                 Tambah fakultas
             </a>
         @endif
     </div>

     <form method="GET"
         action="{{ $trashed ? route('superadmin.fakultas.trashed') : route('superadmin.fakultas.index') }}"
         class="faq-filter">
         <div class="f" style="margin-bottom:0;">
             <label for="q">Cari</label>
             <div class="field-icon">
                 <svg class="field-icon__svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true">
                     <circle cx="11" cy="11" r="7" />
                     <path d="M20 20l-3.5-3.5" />
                 </svg>
                 <input id="q" type="search" name="q" value="{{ request('q') }}"
                     placeholder="Nama atau kode fakultas…" autocomplete="off">
             </div>
         </div>
         @unless ($trashed)
             <div class="f" style="margin-bottom:0;min-width:150px;">
                 <label for="status">Status</label>
                 <select id="status" name="status">
                     <option value="">Semua</option>
                     <option value="active" @selected(request('status') === 'active')>Aktif</option>
                     <option value="inactive" @selected(request('status') === 'inactive')>Nonaktif</option>
                 </select>
             </div>
         @endunless
         <div class="faq-filter__actions">
             <button type="submit" class="btn">Terapkan</button>
             @if (request()->hasAny(['q', 'status']))
                 <a href="{{ $trashed ? route('superadmin.fakultas.trashed') : route('superadmin.fakultas.index') }}"
                     class="btn gh">Reset</a>
             @endif
         </div>
     </form>
 </div>
