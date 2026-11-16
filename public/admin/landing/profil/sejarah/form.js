;(() => {
    /* ============================================================
     *  RICH TEXT EDITOR (mandiri, tanpa library/CDN)
     * ============================================================ */
    const fallback = document.querySelector('[data-rte-fallback]')
    const rte = document.querySelector('[data-rte]')
    const area = rte?.querySelector('[data-rte-area]')
    const counter = rte?.querySelector('[data-rte-count]')

    if (fallback && rte && area) {
        // Aktifkan RTE: sembunyikan textarea, tampilkan editor
        fallback.style.display = 'none'
        rte.hidden = false
        area.innerHTML = fallback.value || ''

        // Placeholder & counter
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
            btn.addEventListener('mousedown', (e) => e.preventDefault()) // jaga selection
            btn.addEventListener('click', () => {
                const cmd = btn.dataset.cmd
                const val = btn.dataset.val

                if (cmd === 'createLink') {
                    const url = prompt('URL tautan:', 'https://')
                    if (url) document.execCommand(cmd, false, url)
                } else {
                    document.execCommand(cmd, false, val ?? null)
                }
                area.focus()
                updateMeta()
            })
        })

        // Shortcut sederhana
        area.addEventListener('keydown', (e) => {
            if (!(e.ctrlKey || e.metaKey)) return
            const k = e.key.toLowerCase()
            if (k === 'b' || k === 'i' || k === 'u') {
                e.preventDefault()
                document.execCommand(
                    {
                        b: 'bold',
                        i: 'italic',
                        u: 'underline'
                    }[k]
                )
                updateMeta()
            }
        })

        // Paste bersih: buang style & hanya simpan teks + line break
        area.addEventListener('paste', (e) => {
            e.preventDefault()
            const text = (e.clipboardData || window.clipboardData).getData(
                'text/plain'
            )
            document.execCommand('insertText', false, text)
            updateMeta()
        })

        // Sinkronkan ke textarea saat submit
        const form = document.getElementById('sejarahForm')
        form?.addEventListener('submit', () => {
            fallback.value = area.innerHTML.trim()
        })
    }

    /* ============================================================
     *  DROPZONE GAMBAR SAMPUL
     * ============================================================ */
    const MAX = 2 * 1024 * 1024
    const OK = ['image/png', 'image/jpeg', 'image/webp']

    document.querySelectorAll('[data-dz]').forEach((dz) => {
        const input = dz.querySelector('input[type="file"]')
        const img = dz.querySelector('[data-dz-thumb] img')
        const ico = dz.querySelector('.dz-ico')
        const title = dz.querySelector('[data-dz-title]')
        const meta = dz.querySelector('[data-dz-meta]')
        const btn = dz.querySelector('[data-dz-btn]')
        const clear = dz.querySelector('[data-dz-clear]')
        const err = dz.parentElement.querySelector('[data-dz-error]')
        const initial = dz.dataset.initialSrc
        const def = {
            title: title.textContent,
            meta: meta.textContent
        }
        let blobUrl = null

        const show = (src) => {
            img.hidden = !src
            ico.style.display = src ? 'none' : ''
            if (src) img.src = src
            else img.removeAttribute('src')
        }

        const reset = () => {
            input.value = ''
            if (blobUrl) URL.revokeObjectURL(blobUrl)
            blobUrl = null
            clear.hidden = true
            if (initial) {
                show(initial)
                title.textContent = 'Gambar tersimpan'
                meta.textContent = 'Pilih gambar baru untuk menggantinya.'
                btn.textContent = 'Ganti gambar'
            } else {
                show('')
                title.textContent = def.title
                meta.textContent = def.meta
                btn.textContent = 'Pilih gambar'
            }
        }

        const fail = (msg) => {
            err.textContent = msg
            err.hidden = false
            reset()
        }

        input.addEventListener('change', () => {
            err.hidden = true
            const f = input.files[0]
            if (!f) return reset()
            if (!OK.includes(f.type))
                return fail('Format harus PNG, JPG, atau WEBP.')
            if (f.size > MAX) return fail('Ukuran gambar maksimal 2 MB.')

            if (blobUrl) URL.revokeObjectURL(blobUrl)
            blobUrl = URL.createObjectURL(f)
            show(blobUrl)
            title.textContent = f.name
            meta.textContent =
                (f.size / 1024).toFixed(0) + ' KB · siap diunggah'
            btn.textContent = 'Ganti gambar'
            clear.hidden = false
        })

        clear.addEventListener('click', (e) => {
            e.preventDefault()
            err.hidden = true
            reset()
        })

        ;['dragenter', 'dragover'].forEach((ev) =>
            dz.addEventListener(ev, () => dz.classList.add('is-drag'))
        )
        ;['dragleave', 'drop'].forEach((ev) =>
            dz.addEventListener(ev, () => dz.classList.remove('is-drag'))
        )

        reset()
    })

    /* ============================================================
     *  CHECKLIST KUALITAS (live update)
     * ============================================================ */
    const check = (name, ok) => {
        const li = document.querySelector(`[data-check="${name}"]`)
        if (li) li.classList.toggle('is-ok', !!ok)
    }

    const judhulInput = document.getElementById('judhul')
    const kronoInput = document.getElementById('kronologi_text')
    const sampulInput = document.getElementById('path_sampul')
    const updateChecks = () => {
        check('judhul', judhulInput?.value.trim().length >= 5)
        check('isi', (area?.innerText.trim().length ?? 0) >= 300)
        check('kronologi', (kronoInput?.value.trim().length ?? 0) > 0)
        check(
            'sampul',
            !!sampulInput?.files[0] ||
                !!document.querySelector('[data-dz]')?.dataset.initialSrc
        )
    }

    judhulInput?.addEventListener('input', updateChecks)
    kronoInput?.addEventListener('input', updateChecks)
    sampulInput?.addEventListener('change', updateChecks)
    area?.addEventListener('input', updateChecks)
    updateChecks()

    /* ============================================================
     *  KONFIRMASI HAPUS (integr. dengan modal global)
     * ============================================================ */
    document.querySelectorAll('[data-confirm-form]').forEach((form) => {
        form.addEventListener('submit', (e) => {
            // Biarkan modal global handler (di pmb.js) yang menangani
            // jika ada. Fallback: pakai confirm() bawaan.
            if (!window.__hasGlobalConfirmModal) {
                if (!confirm(form.dataset.confirmMessage || 'Yakin hapus?')) {
                    e.preventDefault()
                }
            }
        })
    })
})()
