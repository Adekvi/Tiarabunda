<div id="akaConfirmModal" role="dialog" aria-modal="true" aria-labelledby="akaConfirmTitle" hidden
    style="position: fixed; inset: 0; z-index: 90; display: none; align-items: center; justify-content: center; background: rgba(10, 18, 32, .55); padding: 20px;">
    <div class="card" style="width: min(420px, 100%); padding: 22px; box-shadow: 0 20px 50px rgba(0, 0, 0, .2);">
        <h3 id="akaConfirmTitle" style="margin: 0 0 8px; font-size: 1.05rem; color: var(--ink);">
            Konfirmasi Tindakan
        </h3>
        <p id="akaConfirmMessage" style="margin: 0 0 20px; font-size: 13px; color: var(--mut); line-height: 1.5;">
            Apakah Anda yakin ingin melanjutkan?
        </p>
        <div style="display: flex; gap: 10px; justify-content: flex-end;">
            <button type="button" class="aka-btn-ghost" data-confirm-cancel>Batal</button>
            <form id="akaConfirmForm" method="POST" action="">
                @csrf
                <input type="hidden" name="_method" id="akaConfirmMethod" value="DELETE">
                <button type="submit" class="btn" style="background: var(--err);">Ya, Lanjutkan</button>
            </form>
        </div>
    </div>
</div>

@push('js')
    <script nonce="{{ request()->attributes->get('csp_nonce') }}">
        (function() {
            const modal = document.getElementById('akaConfirmModal');
            const titleEl = document.getElementById('akaConfirmTitle');
            const msgEl = document.getElementById('akaConfirmMessage');
            const form = document.getElementById('akaConfirmForm');
            const methodInput = document.getElementById('akaConfirmMethod');

            if (!modal || !form) return;

            document.querySelectorAll('[data-confirm-trigger]').forEach(function(btn) {
                btn.addEventListener('click', function() {
                    titleEl.textContent = btn.getAttribute('data-confirm-title') ||
                        'Konfirmasi Tindakan';
                    msgEl.textContent = btn.getAttribute('data-confirm-message') ||
                    'Apakah Anda yakin?';
                    form.setAttribute('action', btn.getAttribute('data-confirm-action') || '');
                    methodInput.value = btn.getAttribute('data-confirm-method') || 'DELETE';
                    modal.hidden = false;
                    modal.style.display = 'flex';
                });
            });

            function closeModal() {
                modal.hidden = true;
                modal.style.display = 'none';
            }

            modal.querySelectorAll('[data-confirm-cancel]').forEach(function(el) {
                el.addEventListener('click', closeModal);
            });

            modal.addEventListener('click', function(e) {
                if (e.target === modal) closeModal();
            });
        })();
    </script>
@endpush
