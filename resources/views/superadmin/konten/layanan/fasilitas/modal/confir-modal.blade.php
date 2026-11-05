{{--
    Modal konfirmasi reusable.
    Dipicu oleh: <form data-confirm data-confirm-title="..." data-confirm-message="..." ...>
--}}
<div class="cmodal" id="confirmModal" hidden aria-hidden="true" role="dialog" aria-modal="true"
    aria-labelledby="confirmModalTitle" aria-describedby="confirmModalMessage">
    <div class="cmodal__backdrop" data-confirm-cancel></div>

    <div class="cmodal__panel" role="document">
        <div class="cmodal__icon" data-confirm-icon aria-hidden="true">
            {{-- default: warning --}}
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                stroke-linecap="round" stroke-linejoin="round">
                <path d="M12 9v4" />
                <path d="M12 17h.01" />
                <path d="M10.3 3.9 1.8 18.1A2 2 0 0 0 3.5 21h17a2 2 0 0 0 1.7-2.9L13.7 3.9a2 2 0 0 0-3.4 0z" />
            </svg>
        </div>

        <div class="cmodal__body">
            <h3 class="cmodal__title" id="confirmModalTitle" data-confirm-title>Konfirmasi</h3>
            <p class="cmodal__message" id="confirmModalMessage" data-confirm-message>
                Apakah Anda yakin ingin melanjutkan?
            </p>
        </div>

        <div class="cmodal__actions">
            <button type="button" class="btn gh" data-confirm-cancel>Batal</button>
            <button type="button" class="btn btn-danger" data-confirm-ok>Ya, lanjutkan</button>
        </div>
    </div>
</div>
