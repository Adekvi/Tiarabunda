<div class="cmodal" id="confirmModal" role="dialog" aria-modal="true" aria-labelledby="cmTitle" aria-describedby="cmDesc">
    <div class="cmodal__backdrop" data-modal-close></div>
    <div class="cmodal__panel" role="document">
        <div class="cmodal__ico" id="cmIcon" aria-hidden="true"></div>
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
