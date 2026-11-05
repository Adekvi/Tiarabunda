<div class="rec-banner" role="status">
    <div class="rec-banner__main">
        <span class="rec-banner__ico" aria-hidden="true">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                stroke-linecap="round" stroke-linejoin="round">
                <path d="M3 12a9 9 0 1 0 3-6.7L3 8" />
                <path d="M3 3v5h5" />
            </svg>
        </span>

        <div class="rec-banner__body">
            <div class="rec-banner__head">
                <strong class="rec-banner__title">{{ $count }} fakultas di sampah</strong>
                <span class="rec-banner__pill">{{ $count }} item</span>
            </div>
            <p class="rec-banner__desc">
                Data terhapus masih bisa dipulihkan sebelum dihapus permanen.
            </p>
        </div>
    </div>

    <a href="{{ route('superadmin.fakultas.trashed') }}" class="rec-banner__btn">
        Kelola sampah
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"
            stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="M5 12h14" />
            <path d="M13 6l6 6-6 6" />
        </svg>
    </a>
</div>

