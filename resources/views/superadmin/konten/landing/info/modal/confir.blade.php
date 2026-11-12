<div class="info-modal" id="infoConfirm" hidden data-type="danger">
    <div class="info-modal__backdrop" data-close></div>
    <div class="info-modal__box" role="dialog" aria-modal="true" aria-labelledby="infoConfirmTitle"
        aria-describedby="infoConfirmDesc">

        <div class="info-modal__icon" aria-hidden="true">
            <svg class="info-modal__ico-danger" width="22" height="22" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z" />
                <line x1="12" y1="9" x2="12" y2="13" />
                <line x1="12" y1="17" x2="12.01" y2="17" />
            </svg>
            <svg class="info-modal__ico-restore" width="22" height="22" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                style="display:none;">
                <path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8" />
                <path d="M3 3v5h5" />
            </svg>
        </div>

        <h3 id="infoConfirmTitle" class="info-modal__title">Konfirmasi</h3>
        <p id="infoConfirmDesc" class="info-modal__desc">Apakah Anda yakin?</p>

        <form method="POST" id="infoConfirmForm" action="#">
            @csrf
            <input type="hidden" name="_method" id="infoConfirmMethod" value="DELETE">

            <div class="info-modal__actions">
                <button type="button" class="btn gh" data-close>Batal</button>
                <button type="submit" class="btn danger" id="infoConfirmOk">Ya, lanjutkan</button>
            </div>
        </form>
    </div>
</div>
