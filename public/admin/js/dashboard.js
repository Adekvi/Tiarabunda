;(() => {
    const root = document.getElementById('dashboardApp')
    if (!root) return

    const html = document.documentElement
    const key = 'd-theme'

    // Theme
    try {
        html.dataset.theme = localStorage.getItem(key) || 'light'
    } catch (e) {}

    document.querySelector('[data-theme-toggle]')?.addEventListener('click', () => {
        const next = html.dataset.theme === 'dark' ? 'light' : 'dark'
        html.dataset.theme = next
        try {
            localStorage.setItem(key, next)
        } catch (e) {}
    })

    // Sidebar mobile
    const open = () => root.classList.add('open-s')
    const close = () => root.classList.remove('open-s')
    document.querySelector('[data-sidebar-open]')?.addEventListener('click', open)
    document.querySelector('[data-sidebar-close]')?.addEventListener('click', close)

    // Nav groups
    document.querySelectorAll('[data-nav-toggle]').forEach((btn) => {
        btn.addEventListener('click', () => {
            const grp = btn.closest('[data-nav-group]')
            grp?.classList.toggle('open')
            btn.setAttribute('aria-expanded', grp?.classList.contains('open') ? 'true' : 'false')
        })
    })
})()