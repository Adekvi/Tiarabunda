<div class="modal-backdrop" id="modalConfirLokasi" hidden
    style="position: fixed; inset: 0; z-index: 80; display: none; align-items: center; justify-content: center; background: rgba(10,18,32,.55); padding: 20px;">
    <div class="card" role="dialog" aria-modal="true" aria-labelledby="titleConfirLokasi"
        style="width: min(420px, 100%); padding: 24px; box-shadow: 0 20px 50px rgba(0,0,0,.2);">
        <h2 id="titleConfirLokasi" style="margin: 0 0 8px; font-size: 1.12rem; color: var(--ink);">
            Konfirmasi Aksi
        </h2>
        <p id="messageConfirLokasi" style="margin: 0 0 20px; color: var(--mut); font-size: 13px; line-height: 1.55;">
            Apakah Anda yakin ingin melanjutkan tindakan ini?
        </p>

        <form id="formConfirLokasi" method="POST" style="display: flex; gap: 10px; justify-content: flex-end;">
            @csrf
            <input type="hidden" name="_method" id="methodConfirLokasi" value="DELETE">

            <button type="button" class="lok-btn lok-btn--ghost" data-confir-close>
                Batal
            </button>
            <button type="submit" class="lok-btn lok-btn--danger" id="submitConfirLokasi">
                Ya, Lanjutkan
            </button>
        </form>
    </div>
</div>

@push('js')
    <script>
        (function() {
            const modal = document.getElementById('modalConfirLokasi');
            if (!modal) return;

            const form = document.getElementById('formConfirLokasi');
            const methodInput = document.getElementById('methodConfirLokasi');
            const titleEl = document.getElementById('titleConfirLokasi');
            const messageEl = document.getElementById('messageConfirLokasi');
            const submitBtn = document.getElementById('submitConfirLokasi');

            function closeModal() {
                modal.hidden = true;
                modal.style.display = 'none';
            }

            document.addEventListener('click', function(e) {
                const trigger = e.target.closest('[data-confir-open]');
                if (trigger) {
                    form.action = trigger.getAttribute('data-action') || '';
                    methodInput.value = trigger.getAttribute('data-method') || 'DELETE';
                    titleEl.textContent = trigger.getAttribute('data-title') || 'Konfirmasi Aksi';
                    messageEl.textContent = trigger.getAttribute('data-message') || 'Apakah Anda yakin?';
                    submitBtn.className = trigger.getAttribute('data-btn-class') || 'lok-btn lok-btn--danger';
                    submitBtn.textContent = trigger.getAttribute('data-btn-text') || 'Ya, Lanjutkan';

                    modal.hidden = false;
                    modal.style.display = 'flex';
                    return;
                }

                if (e.target.closest('[data-confir-close]') || e.target === modal) {
                    closeModal();
                }
            });

            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && !modal.hidden) {
                    closeModal();
                }
            });
        })();
    </script>
@endpush
