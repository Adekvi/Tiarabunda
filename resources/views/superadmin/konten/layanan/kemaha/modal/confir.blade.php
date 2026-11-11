<div class="kmh-modal" id="kmhConfirm" hidden>
    <div class="kmh-modal__backdrop" data-close></div>
    <div class="kmh-modal__box" role="dialog" aria-modal="true" aria-labelledby="kmhConfirmTitle">
        <div class="kmh-modal__icon">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                stroke-linecap="round" stroke-linejoin="round">
                <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z" />
                <line x1="12" y1="9" x2="12" y2="13" />
                <line x1="12" y1="17" x2="12.01" y2="17" />
            </svg>
        </div>
        <h3 id="kmhConfirmTitle" class="kmh-modal__title">Hapus data ini?</h3>
        <p class="kmh-modal__desc" id="kmhConfirmDesc">Tindakan ini tidak bisa dibatalkan.</p>
        <form method="POST" id="kmhConfirmForm">
            @csrf @method('DELETE')
            <div class="kmh-modal__actions">
                <button type="button" class="btn gh" data-close>Batal</button>
                <button type="submit" class="btn danger" id="kmhConfirmOk">Hapus</button>
            </div>
        </form>
    </div>
</div>
