<div class="cmodal" id="confirmModal" role="dialog" aria-modal="true" aria-labelledby="cmTitle" aria-describedby="cmDesc"
    aria-hidden="true">
    <div class="cmodal__backdrop" data-modal-close></div>
    <div class="cmodal__panel" role="document">
        <div class="cmodal__ico" id="cmIcon" aria-hidden="true">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                stroke-linecap="round" stroke-linejoin="round">
                <path d="M12 3l10 18H2zM12 10v5M12 18h.01" />
            </svg>
        </div>
        <h3 class="cmodal__title" id="cmTitle">Konfirmasi</h3>
        <p class="cmodal__desc" id="cmDesc">Apakah Anda yakin?</p>
        <form id="cmForm" method="POST" action="">
            @csrf
            <input type="hidden" id="cmMethod" name="_method" value="DELETE">
            <div class="cmodal__actions">
                <button type="button" class="btn gh" data-modal-close>Batal</button>
                <button type="submit" class="btn" id="cmConfirm">Ya, lanjutkan</button>
            </div>
        </form>
    </div>
</div>
