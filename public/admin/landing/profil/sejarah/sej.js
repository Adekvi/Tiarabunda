;(function () {
    'use strict'

    const modal = document.getElementById('confirmModal')
    if (!modal) return

    const form = document.getElementById('cmForm')
    const methodEl = document.getElementById('cmMethod')
    const titleEl = document.getElementById('cmTitle')
    const descEl = document.getElementById('cmDesc')
    const confirmEl = document.getElementById('cmConfirm')
    const iconEl = document.getElementById('cmIcon')
    const panel = modal.querySelector('.cmodal__panel')

    let lastFocus = null

    function openModal(opts) {
        lastFocus = document.activeElement

        form.action = opts.action || ''
        methodEl.value = opts.method || 'DELETE'
        titleEl.textContent = opts.title || 'Konfirmasi'
        descEl.innerHTML = opts.message || 'Apakah Anda yakin?'
        confirmEl.textContent = opts.confirm || 'Ya, lanjutkan'
        confirmEl.className =
            'btn' + (opts.type === 'danger' ? ' btn-danger' : '')
        if (panel) panel.dataset.type = opts.type || 'default'

        modal.classList.add('is-open')
        modal.setAttribute('aria-hidden', 'false')
        document.body.style.overflow = 'hidden'

        confirmEl.focus()
    }

    function closeModal() {
        modal.classList.remove('is-open')
        modal.setAttribute('aria-hidden', 'true')
        document.body.style.overflow = ''
        if (lastFocus) lastFocus.focus()
    }

    // Trigger dari button [data-modal-open]
    document.addEventListener('click', function (e) {
        const trigger = e.target.closest('[data-modal-open]')
        if (!trigger) return
        e.preventDefault()

        openModal({
            action: trigger.dataset.modalAction,
            method: trigger.dataset.modalMethod || 'DELETE',
            title: trigger.dataset.modalTitle,
            message: trigger.dataset.modalMessage,
            confirm: trigger.dataset.modalConfirm,
            type: trigger.dataset.modalType
        })
    })

    // Tombol close
    modal.addEventListener('click', function (e) {
        if (e.target.closest('[data-modal-close]')) {
            e.preventDefault()
            closeModal()
        }
    })

    // ESC untuk tutup
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && modal.classList.contains('is-open')) {
            closeModal()
        }
    })
})()
