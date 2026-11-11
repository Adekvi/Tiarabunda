;(function () {
    'use strict'

    const modal = document.getElementById('kmhConfirm')
    if (!modal) return

    const form = document.getElementById('kmhConfirmForm')
    const okBtn = document.getElementById('kmhConfirmOk')
    const title = document.getElementById('kmhConfirmTitle')
    const desc = document.getElementById('kmhConfirmDesc')
    const methodInput = form.querySelector('input[name="_method"]')

    /**
     * Buka modal dengan parameter dinamis.
     * @param {Object} opts
     * @param {string} opts.url      - action form
     * @param {string} [opts.title]  - judul modal
     * @param {string} [opts.message]- deskripsi modal
     * @param {string} [opts.confirm]- label tombol OK
     * @param {string} [opts.method] - HTTP method ('DELETE' | 'PATCH' | 'PUT')
     * @param {string} [opts.type]   - 'danger' | 'restore' (untuk ikon/warna)
     */
    function open(opts) {
        const method = (opts.method || 'DELETE').toUpperCase()

        form.setAttribute('action', opts.url)
        if (methodInput) methodInput.value = method

        // Reset kelas ikon
        const icon = modal.querySelector('.kmh-modal__icon')
        if (icon) {
            icon.classList.remove('is-restore', 'is-danger')
            icon.classList.add(
                opts.type === 'restore' ? 'is-restore' : 'is-danger'
            )
        }

        title.textContent = opts.title || 'Konfirmasi'
        desc.textContent = opts.message || 'Tindakan ini tidak bisa dibatalkan.'
        okBtn.textContent = opts.confirm || 'Ya, lanjutkan'

        // Warna tombol OK
        okBtn.classList.remove('danger', 'ok')
        okBtn.classList.add(opts.type === 'restore' ? 'ok' : 'danger')

        modal.hidden = false
        document.body.style.overflow = 'hidden'
        okBtn.focus()
    }

    function close() {
        modal.hidden = true
        document.body.style.overflow = ''
    }

    // Delegasi klik
    document.addEventListener('click', (e) => {
        // 1) Sistem baru: data-modal-open + data-modal-*
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

        // 2) Sistem lama: data-confirm-delete / data-confirm-force
        const del = e.target.closest('[data-confirm-delete]')
        const force = e.target.closest('[data-confirm-force]')

        if (del) {
            open({
                url: del.dataset.url,
                title: 'Pindahkan ke sampah?',
                message: `"${del.dataset.name}" akan dipindahkan ke sampah. Bisa dipulihkan nanti.`,
                confirm: 'Hapus',
                method: 'DELETE',
                type: 'danger'
            })
            return
        }

        if (force) {
            open({
                url: force.dataset.url,
                title: 'Hapus permanen?',
                message: `"${force.dataset.name}" akan dihapus dari database dan tidak bisa dipulihkan.`,
                confirm: 'Hapus Permanen',
                method: 'DELETE',
                type: 'danger'
            })
            return
        }

        // 3) Tombol tutup
        if (e.target.closest('[data-close]')) {
            close()
        }
    })

    // Esc untuk tutup
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && !modal.hidden) close()
    })
})()
