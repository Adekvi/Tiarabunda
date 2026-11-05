;(() => {
    'use strict'

    const MODAL_ID = 'confirmModal'

    const $ = (sel, root = document) => root.querySelector(sel)

    let modal = null
    let pendingForm = null
    let prevFocus = null

    const setIcon = (tone) => {
        const icon = $('[data-confirm-icon]', modal)
        if (!icon) return

        icon.classList.remove('cmodal__icon--warning', 'cmodal__icon--danger')
        icon.classList.add(
            tone === 'warning'
                ? 'cmodal__icon--warning'
                : 'cmodal__icon--danger'
        )

        // Ganti SVG warning ↔ trash
        if (tone === 'warning') {
            icon.innerHTML = `
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 9v4" />
                    <path d="M12 17h.01" />
                    <path d="M10.3 3.9 1.8 18.1A2 2 0 0 0 3.5 21h17a2 2 0 0 0 1.7-2.9L13.7 3.9a2 2 0 0 0-3.4 0z" />
                </svg>`
        } else {
            icon.innerHTML = `
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M3 6h18" />
                    <path d="M8 6V4h8v2" />
                    <path d="M19 6l-1 14H6L5 6" />
                    <path d="M10 11v6" />
                    <path d="M14 11v6" />
                </svg>`
        }
    }

    const open = (form) => {
        if (!modal || !form) return

        pendingForm = form

        const title = form.dataset.confirmTitle || 'Konfirmasi'
        const message =
            form.dataset.confirmMessage ||
            'Apakah Anda yakin ingin melanjutkan?'
        const okText = form.dataset.confirmOk || 'Ya, lanjutkan'
        const tone = form.dataset.confirmTone || 'danger'

        $('[data-confirm-title]', modal).textContent = title
        $('[data-confirm-message]', modal).textContent = message
        $('[data-confirm-ok]', modal).textContent = okText

        setIcon(tone)

        const okBtn = $('[data-confirm-ok]', modal)
        okBtn.classList.remove('btn-danger', 'btn-primary')
        okBtn.classList.add(tone === 'warning' ? 'btn' : 'btn-danger')
        // tombol confirm utk warning bisa netral — sesuaikan preferensi:
        if (tone === 'warning') {
            okBtn.classList.remove('btn-danger')
            okBtn.classList.add('btn')
        }

        prevFocus = document.activeElement
        modal.hidden = false
        modal.setAttribute('aria-hidden', 'false')
        document.body.classList.add('has-cmodal')

        // Fokus ke tombol cancel (safe default)
        requestAnimationFrame(() => $('[data-confirm-cancel]', modal)?.focus())
    }

    const close = () => {
        if (!modal) return

        modal.hidden = true
        modal.setAttribute('aria-hidden', 'true')
        document.body.classList.remove('has-cmodal')
        pendingForm = null

        if (prevFocus && typeof prevFocus.focus === 'function') {
            prevFocus.focus()
            prevFocus = null
        }
    }

    const confirm = () => {
        const form = pendingForm
        if (!form) return close()

        // Tandai agar tidak intercept ulang saat form.submit() dipanggil
        form.dataset.confirmBypass = '1'
        close()

        if (typeof form.requestSubmit === 'function') {
            form.requestSubmit()
        } else {
            form.submit()
        }
    }

    const bind = () => {
        modal = document.getElementById(MODAL_ID)
        if (!modal) return

        // Delegasi ke seluruh form[data-confirm] di halaman
        document.addEventListener(
            'submit',
            (event) => {
                const form = event.target
                if (!(form instanceof HTMLFormElement)) return
                if (!form.matches('form[data-confirm]')) return
                if (form.dataset.confirmBypass === '1') {
                    delete form.dataset.confirmBypass
                    return
                }

                event.preventDefault()
                open(form)
            },
            true
        )

        // Tombol cancel / backdrop
        modal.addEventListener('click', (event) => {
            if (event.target.closest('[data-confirm-cancel]')) {
                event.preventDefault()
                close()
            }
            if (event.target.closest('[data-confirm-ok]')) {
                event.preventDefault()
                confirm()
            }
        })

        // ESC untuk tutup
        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape' && !modal.hidden) {
                event.preventDefault()
                close()
            }
        })

        // Klik backdrop = cancel
        $('.cmodal__backdrop', modal)?.addEventListener('click', close)
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', bind, { once: true })
    } else {
        bind()
    }
})()
