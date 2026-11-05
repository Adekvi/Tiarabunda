@if ($items->hasPages())
    <div class="nw-pagination">
        <div class="nw-pagination__info">
            Menampilkan <strong>{{ $items->firstItem() }}</strong>–<strong>{{ $items->lastItem() }}</strong> dari
            <strong>{{ $items->total() }}</strong> data
        </div>

        <div class="nw-pagination__navigation">
            {{-- PENTING: Panggil view custom di sini --}}
            {{ $items->links('superadmin.konten.layanan.fasilitas.partials.pagination') }}
        </div>
    </div>
@endif
