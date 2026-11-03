;(() => {
    const root = document.getElementById('nwLayout')
    if (!root) return

    const KEY = 'd-news-aside' // mengikuti pola 'd-theme' di dashboard.js
    const btn = root.querySelector('[data-aside-toggle]')

    const apply = (state, save) => {
        root.dataset.aside = state
        if (btn) {
            const open = state === 'open'
            btn.setAttribute('aria-expanded', open ? 'true' : 'false')
            btn.setAttribute(
                'aria-label',
                open ? 'Tutup panel ringkasan' : 'Buka panel ringkasan'
            )
            btn.title = open ? 'Tutup panel' : 'Buka panel'
        }
        if (save) {
            try {
                localStorage.setItem(KEY, state)
            } catch (e) {}
        }
    }

    apply(root.dataset.aside === 'closed' ? 'closed' : 'open', false)

    btn?.addEventListener('click', () => {
        apply(root.dataset.aside === 'open' ? 'closed' : 'open', true)
    })

    // Tekan "/" untuk langsung fokus ke kolom pencarian
    document.addEventListener('keydown', (e) => {
        if (e.key !== '/' || e.ctrlKey || e.metaKey || e.altKey) return
        const t = e.target
        if (
            t.closest &&
            t.closest('input, textarea, select, [contenteditable="true"]')
        )
            return
        const q = document.getElementById('q')
        if (q) {
            e.preventDefault()
            q.focus()
            q.select()
        }
    })
})()
