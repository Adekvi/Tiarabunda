;(() => {
    const root = document.getElementById('dashboardApp')
    if (!root) return

    const html = document.documentElement
    const key = 'd-theme'

    try {
        html.dataset.theme = localStorage.getItem(key) || 'light'
    } catch (e) {}

    document
        .querySelector('[data-theme-toggle]')
        ?.addEventListener('click', () => {
            const next = html.dataset.theme === 'dark' ? 'light' : 'dark'
            html.dataset.theme = next
            try {
                localStorage.setItem(key, next)
            } catch (e) {}
        })

    // Sidebar
    const openSide = () => root.classList.add('open-s')
    const closeSide = () => root.classList.remove('open-s')
    document
        .querySelector('[data-sidebar-open]')
        ?.addEventListener('click', openSide)
    document
        .querySelector('[data-sidebar-close]')
        ?.addEventListener('click', closeSide)

    // Nav groups
    document.querySelectorAll('[data-nav-toggle]').forEach((btn) => {
        btn.addEventListener('click', () => {
            const grp = btn.closest('[data-nav-group]')
            grp?.classList.toggle('open')
            btn.setAttribute(
                'aria-expanded',
                grp?.classList.contains('open') ? 'true' : 'false'
            )
        })
    })

    // Account dropdown
    const accBtn = document.querySelector('[data-account-toggle]')
    const accPanel = document.querySelector('[data-account-panel]')
    const closeAcc = () => {
        if (!accPanel) return
        accPanel.hidden = true
        accBtn?.setAttribute('aria-expanded', 'false')
    }
    accBtn?.addEventListener('click', (e) => {
        e.stopPropagation()
        const open = accPanel.hidden
        accPanel.hidden = !open
        accBtn.setAttribute('aria-expanded', open ? 'true' : 'false')
    })
    document.addEventListener('click', (e) => {
        if (!e.target.closest('[data-account-menu]')) closeAcc()
    })

    // Logout modal
    const modal = document.querySelector('[data-logout-modal]')
    const openModal = () => {
        if (!modal) return
        modal.hidden = false
        modal.style.display = 'flex'
        closeAcc()
    }
    const closeModal = () => {
        if (!modal) return
        modal.hidden = true
        modal.style.display = 'none'
    }
    document.querySelectorAll('[data-logout-open]').forEach((el) => {
        el.addEventListener('click', openModal)
    })
    document
        .querySelector('[data-logout-cancel]')
        ?.addEventListener('click', closeModal)
    modal?.addEventListener('click', (e) => {
        if (e.target === modal) closeModal()
    })
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            closeModal()
            closeAcc()
        }
    })
})()
