<div class="tst-modal" id="tstConfirmModal" role="dialog" aria-modal="true" aria-labelledby="tstModalTitle" hidden>
    <div class="tst-modal-backdrop" data-confirm-close></div>

    <div class="tst-modal-box">
        <div class="tst-modal-head">
            <h3 class="tst-modal-title" id="tstModalTitle">Konfirmasi Aksi</h3>
            <button type="button" class="tst-modal-close" data-confirm-close aria-label="Tutup">&times;</button>
        </div>

        <p class="tst-modal-msg" id="tstModalMessage">Apakah Anda yakin ingin melanjutkan tindakan ini?</p>

        <form id="tstConfirmForm" method="POST" action="">
            @csrf
            <input type="hidden" name="_method" id="tstConfirmMethod" value="DELETE">

            <div class="tst-modal-actions">
                <button type="button" class="lok-btn lok-btn--ghost" data-confirm-close>Batal</button>
                <button type="submit" class="lok-btn lok-btn--danger" id="tstConfirmSubmit">Ya, Lanjutkan</button>
            </div>
        </form>
    </div>
</div>
