;(() => {
    /* ---------- Modal konfirmasi (Event Delegation) ---------- */
    const modal = document.getElementById('confirmModal')
    if (!modal) return // Berhenti jika modal tidak ada di halaman ini

    const form = document.getElementById('cmForm')
    const method = document.getElementById('cmMethod')
    const title = document.getElementById('cmTitle')
    const desc = document.getElementById('cmDesc')
    const confirmBtn = document.getElementById('cmConfirm')
    const iconBox = document.getElementById('cmIcon')

    const ICON = {
        danger: `<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M8 6V4h8v2"/><path d="M19 6l-1 14H6L5 6"/></svg>`,
        restore: `<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 12a9 9 0 1 0 3-6.7L3 8"/><path d="M3 3v5h5"/></svg>`
    }

    let lastFocus = null
    let closing = false

    const openModal = (btn) => {
        if (closing) return
        lastFocus = document.activeElement
        const action = btn.dataset.modalAction
        if (!action) return

        const type = btn.dataset.modalType || 'danger'
        const m = (btn.dataset.modalMethod || 'POST').toUpperCase()

        title.textContent = btn.dataset.modalTitle || 'Konfirmasi'
        desc.textContent = btn.dataset.modalMessage || 'Apakah Anda yakin?'
        confirmBtn.textContent = btn.dataset.modalConfirm || 'Ya, lanjutkan'

        form.action = action
        method.value = m === 'GET' ? 'POST' : m
        modal.dataset.type = type

        if (iconBox) iconBox.innerHTML = ICON[type] || ICON.danger

        document.body.style.overflow = 'hidden'
        requestAnimationFrame(() => modal.classList.add('is-open'))
        modal.querySelector('[data-modal-close]')?.focus()
    }

    const closeModal = () => {
        if (closing || !modal.classList.contains('is-open')) return
        closing = true
        modal.classList.remove('is-open')

        setTimeout(() => {
            document.body.style.overflow = ''
            closing = false
            lastFocus?.focus?.()
        }, 250)
    }

    // Gunakan Event Delegation pada document
    document.addEventListener('click', (e) => {
        const opener = e.target.closest('[data-modal-open]')
        if (opener) {
            e.preventDefault()
            openModal(opener)
            return
        }
        if (e.target.closest('[data-modal-close]')) {
            e.preventDefault()
            closeModal()
        }
    })

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && modal.classList.contains('is-open')) {
            closeModal()
        }
    })
})()
