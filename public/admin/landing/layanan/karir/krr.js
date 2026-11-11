;(function () {
    'use strict'
    const modal = document.getElementById('krrConfirm')
    if (!modal) return

    const form = document.getElementById('krrConfirmForm')
    const okBtn = document.getElementById('krrConfirmOk')
    const title = document.getElementById('krrConfirmTitle')
    const desc = document.getElementById('krrConfirmDesc')
    const methodInput = form.querySelector('input[name="_method"]')
    const icoDanger = modal.querySelector('.krr-modal__ico-danger')
    const icoRestore = modal.querySelector('.krr-modal__ico-restore')

    function open(opts) {
        const method = (opts.method || 'DELETE').toUpperCase()
        const type = opts.type === 'restore' ? 'restore' : 'danger'

        form.setAttribute('action', opts.url)
        if (methodInput) methodInput.value = method

        // ubah data-type di root modal (agar CSS [data-type] ikut berubah)
        modal.setAttribute('data-type', type)

        // toggle ikon
        if (icoDanger) icoDanger.style.display = type === 'danger' ? '' : 'none'
        if (icoRestore)
            icoRestore.style.display = type === 'restore' ? '' : 'none'

        title.textContent = opts.title || 'Konfirmasi'
        desc.textContent = opts.message || 'Tindakan ini tidak bisa dibatalkan.'
        okBtn.textContent = opts.confirm || 'Ya, lanjutkan'

        modal.hidden = false
        document.body.style.overflow = 'hidden'
    }

    function close() {
        modal.hidden = true
        document.body.style.overflow = ''
    }

    document.addEventListener('click', (e) => {
        const trigger = e.target.closest('[data-modal-open]')
        if (trigger) {
            e.preventDefault()
            open({
                url: trigger.dataset.modalAction,
                title: trigger.dataset.modalTitle,
                message: trigger.dataset.modalMessage,
                confirm: trigger.dataset.modalConfirm,
                method: trigger.dataset.modalMethod || 'DELETE',
                type: trigger.dataset.modalType || 'danger'
            })
            return
        }
        if (e.target.closest('[data-close]')) close()
    })

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && !modal.hidden) close()
    })
})()
