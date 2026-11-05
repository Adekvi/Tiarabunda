<div id="confirmModal" class="cmodal" hidden>
    <div class="cmodal__backdrop" data-modal-close></div>
    <div class="cmodal__panel" role="dialog" aria-modal="true" aria-labelledby="cmTitle" aria-describedby="cmDesc">
        <div class="cmodal__icon" id="cmIcon" aria-hidden="true"></div>
        <div class="cmodal__body">
            <h3 class="cmodal__title" id="cmTitle">Konfirmasi</h3>
            <p class="cmodal__message" id="cmDesc">Apakah Anda yakin?</p>
        </div>
        <form method="POST" id="cmForm" class="cmodal__actions">
            @csrf
            <input type="hidden" name="_method" id="cmMethod" value="POST">
            <button type="button" class="btn gh" data-modal-close>Batal</button>
            <button type="submit" class="btn" id="cmConfirm">Ya, lanjutkan</button>
        </form>
    </div>
</div>
