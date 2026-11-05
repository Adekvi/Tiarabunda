;(() => {
    const modal = document.getElementById('confirmModal')
    if (!modal) return

    const form = document.getElementById('cmForm')
    const method = document.getElementById('cmMethod')
    const title = document.getElementById('cmTitle')
    const desc = document.getElementById('cmDesc')
    const confirmBtn = document.getElementById('cmConfirm')
    const iconBox = document.getElementById('cmIcon')

    if (!form || !method || !title || !desc || !confirmBtn) return

    const ICON = {
        danger: `<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M3 6h18"/><path d="M8 6V4h8v2"/><path d="M19 6l-1 14H6L5 6"/></svg>`,
        restore: `<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M3 12a9 9 0 1 0 3-6.7L3 8"/><path d="M3 3v5h5"/></svg>`
    }

    let lastFocus = null
    let closing = false

    function openModal(btn) {
        if (closing) return
        lastFocus = document.activeElement

        const type = btn.dataset.modalType || 'danger'
        const action = btn.dataset.modalAction
        if (!action) return

        const m = (btn.dataset.modalMethod || 'POST').toUpperCase()

        title.textContent = btn.dataset.modalTitle || 'Konfirmasi'
        desc.textContent = btn.dataset.modalMessage || 'Apakah Anda yakin?'
        confirmBtn.textContent = btn.dataset.modalConfirm || 'Ya, lanjutkan'
        form.action = action
        method.value = m === 'GET' ? 'POST' : m
        modal.dataset.type = type
        if (iconBox) iconBox.innerHTML = ICON[type] || ICON.danger

        modal.hidden = false
        document.body.style.overflow = 'hidden'

        // next frame biar transition terpicu
        requestAnimationFrame(() => {
            modal.classList.add('is-open')
        })

        modal.querySelector('[data-modal-close]')?.focus()
    }

    function closeModal() {
        if (closing || modal.hidden) return
        closing = true
        modal.classList.remove('is-open')

        const done = () => {
            modal.hidden = true
            document.body.style.overflow = ''
            closing = false
            lastFocus?.focus?.()
            modal.removeEventListener('transitionend', onEnd)
        }

        const onEnd = (e) => {
            // hanya reaksikan sekali dari root modal
            if (e.target === modal) done()
        }

        modal.addEventListener('transitionend', onEnd)
        // fallback jika transitionend tidak terpanggil
        setTimeout(done, 300)
    }

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
        if (e.key === 'Escape' && !modal.hidden) closeModal()
    })
})()
