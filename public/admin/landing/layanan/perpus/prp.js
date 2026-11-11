;(function () {
    'use strict'

    const modal = document.getElementById('prpConfirm')
    if (!modal) return

    const form = document.getElementById('prpConfirmForm')
    const methodInput = document.getElementById('prpConfirmMethod')
    const titleEl = document.getElementById('prpConfirmTitle')
    const descEl = document.getElementById('prpConfirmDesc')
    const okBtn = document.getElementById('prpConfirmOk')
    const icoDanger = modal.querySelector('.prp-modal__ico-danger')
    const icoRestore = modal.querySelector('.prp-modal__ico-restore')

    function openModal(trigger) {
        const type = trigger.dataset.modalType || 'danger'
        const title = trigger.dataset.modalTitle || 'Konfirmasi'
        const message = trigger.dataset.modalMessage || 'Apakah Anda yakin?'
        const confirmLabel = trigger.dataset.modalConfirm || 'Ya, lanjutkan'
        const action = trigger.dataset.modalAction || '#'
        const method = (trigger.dataset.modalMethod || 'DELETE').toUpperCase()

        // Set isi modal
        titleEl.textContent = title
        descEl.textContent = message
        okBtn.textContent = confirmLabel
        form.setAttribute('action', action)
        methodInput.value = method
        modal.dataset.type = type

        // Ganti ikon sesuai tipe
        if (type === 'restore') {
            icoRestore.style.display = ''
            icoDanger.style.display = 'none'
        } else {
            icoRestore.style.display = 'none'
            icoDanger.style.display = ''
        }

        // Tampilkan
        modal.hidden = false
        // Trigger reflow agar animasi transisi jalan
        void modal.offsetWidth
        modal.classList.add('is-open')
        document.body.style.overflow = 'hidden'

        // Fokus ke tombol konfirmasi
        setTimeout(() => okBtn.focus(), 80)
    }

    function closeModal() {
        modal.classList.remove('is-open')
        document.body.style.overflow = ''
        // Sembunyikan setelah animasi selesai
        setTimeout(() => {
            if (!modal.classList.contains('is-open')) {
                modal.hidden = true
            }
        }, 220)
    }

    // Delegasi klik untuk membuka modal
    document.addEventListener('click', (e) => {
        const trigger = e.target.closest('[data-modal-open]')
        if (trigger) {
            e.preventDefault()
            openModal(trigger)
            return
        }
        if (e.target.closest('[data-close]')) {
            closeModal()
        }
    })

    // ESC untuk menutup
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && modal.classList.contains('is-open')) {
            closeModal()
        }
    })

    // Cegah submit ganda
    form.addEventListener('submit', () => {
        okBtn.disabled = true
        okBtn.textContent = 'Memproses…'
    })
})()
