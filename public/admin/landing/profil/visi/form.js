;(() => {
    // ============================================================
    //  RICH TEXT EDITOR (Mendukung multiple instance di 1 halaman)
    // ============================================================
    document.querySelectorAll('[data-rte]').forEach((rte) => {
        // Cari fallback textarea tepat sebelum RTE ini
        const fallback = rte.previousElementSibling
        const area = rte.querySelector('[data-rte-area]')
        const counter = rte.querySelector('[data-rte-count]')

        if (fallback && area) {
            // Inisialisasi
            fallback.style.display = 'none'
            rte.hidden = false
            area.innerHTML = fallback.value || ''

            const updateMeta = () => {
                const text = area.innerText.trim()
                area.toggleAttribute('data-empty', text.length === 0)
                if (counter) {
                    const words = text.length
                        ? text.split(/\s+/).filter(Boolean).length
                        : 0
                    counter.textContent = words + ' kata'
                }
            }
            updateMeta()
            area.addEventListener('input', updateMeta)

            // Toolbar
            rte.querySelectorAll('[data-cmd]').forEach((btn) => {
                btn.addEventListener('mousedown', (e) => e.preventDefault())
                btn.addEventListener('click', () => {
                    const cmd = btn.dataset.cmd
                    const val = btn.dataset.val
                    document.execCommand(cmd, false, val ?? null)
                    area.focus()
                    updateMeta()
                })
            })

            // Shortcut Ctrl+B/I/U
            area.addEventListener('keydown', (e) => {
                if (!(e.ctrlKey || e.metaKey)) return
                const k = e.key.toLowerCase()
                if (k === 'b' || k === 'i' || k === 'u') {
                    e.preventDefault()
                    document.execCommand(
                        { b: 'bold', i: 'italic', u: 'underline' }[k]
                    )
                    updateMeta()
                }
            })

            // Paste bersih (Plain text)
            area.addEventListener('paste', (e) => {
                e.preventDefault()
                const text = (e.clipboardData || window.clipboardData).getData(
                    'text/plain'
                )
                document.execCommand('insertText', false, text)
                updateMeta()
            })
        }
    })

    // ============================================================
    //  SINKRONISASI SAAT SUBMIT
    // ============================================================
    const form = document.getElementById('visimisiForm')
    form?.addEventListener('submit', () => {
        document.querySelectorAll('[data-rte]').forEach((rte) => {
            const fallback = rte.previousElementSibling
            const area = rte.querySelector('[data-rte-area]')
            if (fallback && area) {
                fallback.value = area.innerHTML.trim()
            }
        })
    })

    // ============================================================
    //  CHECKLIST KUALITAS (Live Update)
    // ============================================================
    const check = (name, ok) => {
        const li = document.querySelector(`[data-check="${name}"]`)
        if (li) li.classList.toggle('is-ok', !!ok)
    }

    const judhulInput = document.getElementById('judhul')
    const isiArea = document
        .querySelector('textarea[name="isi"]')
        ?.nextElementSibling?.querySelector('[data-rte-area]')
    const dhaptarArea = document
        .querySelector('textarea[name="dhaptar_text"]')
        ?.nextElementSibling?.querySelector('[data-rte-area]')

    const updateChecks = () => {
        check('judhul', judhulInput?.value.trim().length >= 3)
        check('isi', (isiArea?.innerText.trim().length ?? 0) > 10)
        check('dhaptar', (dhaptarArea?.innerText.trim().length ?? 0) > 10)
    }

    judhulInput?.addEventListener('input', updateChecks)
    isiArea?.addEventListener('input', updateChecks)
    dhaptarArea?.addEventListener('input', updateChecks)

    // Run once on load
    setTimeout(updateChecks, 100)

    // ============================================================
    //  KONFIRMASI HAPUS (Fallback jika modal global tak terdeteksi)
    // ============================================================
    document.querySelectorAll('[data-confirm-form]').forEach((f) => {
        f.addEventListener('submit', (e) => {
            if (!window.__hasGlobalConfirmModal) {
                if (!confirm(f.dataset.confirmMessage || 'Yakin hapus?')) {
                    e.preventDefault()
                }
            }
        })
    })
})()
