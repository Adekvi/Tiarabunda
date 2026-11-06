document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('beasiswaForm')
    if (!form) return

    /* =========================================================
     * 0. Utilities
     * ======================================================= */
    const $ = (sel, root = document) => root.querySelector(sel)
    const $$ = (sel, root = document) => Array.from(root.querySelectorAll(sel))

    const esc = (s = '') =>
        String(s)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#39;')

    const slugify = (str = '') =>
        String(str)
            .toLowerCase()
            .normalize('NFD')
            .replace(/[\u0300-\u036f]/g, '')
            .replace(/[^a-z0-9\s-]/g, '')
            .trim()
            .replace(/\s+/g, '-')
            .replace(/-+/g, '-')
            .replace(/^-+|-+$/g, '')

    /* =========================================================
     * 1. RTE Reusable (Deskripsi, Syarat)
     * ======================================================= */
    const RTE = (() => {
        const editors = []

        const clean = (html) => {
            const wrap = document.createElement('div')
            wrap.innerHTML = html
            wrap.querySelectorAll('script,style,iframe,object,embed').forEach(
                (n) => n.remove()
            )
            wrap.querySelectorAll('*').forEach((n) => {
                ;[...n.attributes].forEach((a) => {
                    if (/^on/i.test(a.name) || a.name === 'style')
                        n.removeAttribute(a.name)
                    if (a.name === 'href' && /^\s*javascript:/i.test(a.value))
                        n.removeAttribute(a.name)
                })
            })
            return wrap.innerHTML
        }

        const textToHtml = (txt = '') => {
            const t = String(txt).trim()
            if (!t) return ''
            if (/^\s*</.test(t)) return clean(t)
            return t
                .split(/\n{2,}/)
                .map((p) => '<p>' + esc(p).replace(/\n/g, '<br>') + '</p>')
                .join('')
        }

        const init = (root) => {
            const wrap = $('[data-rte]', root)
            const area = $('[data-rte-area]', root)
            const bar = $('.rte-bar', root)
            const cnt = $('[data-rte-count]', root)
            const ta = $('textarea', root)
            if (!wrap || !area || !bar || !ta) return

            area.innerHTML = textToHtml(ta.value)

            const getText = () => area.innerText || area.textContent || ''
            const refresh = () => {
                const t = getText().trim()
                area.toggleAttribute('data-empty', !t)
                if (cnt)
                    cnt.textContent = (t ? t.split(/\s+/).length : 0) + ' kata'
            }
            const sync = () => {
                ta.value = getText().trim() ? clean(area.innerHTML) : ''
                ta.dispatchEvent(new Event('input', { bubbles: true }))
            }

            let timer
            area.addEventListener('input', () => {
                refresh()
                clearTimeout(timer)
                timer = setTimeout(sync, 200)
            })

            area.addEventListener('paste', (e) => {
                e.preventDefault()
                const t = (
                    (e.clipboardData || window.clipboardData).getData(
                        'text/plain'
                    ) || ''
                ).replace(/\r/g, '')
                if (!t) return
                const paras = t.split(/\n{2,}/)
                if (paras.length === 1 && t.indexOf('\n') === -1) {
                    document.execCommand('insertText', false, t)
                } else {
                    document.execCommand(
                        'insertHTML',
                        false,
                        paras
                            .map(
                                (p) =>
                                    '<p>' +
                                    esc(p).replace(/\n/g, '<br>') +
                                    '</p>'
                            )
                            .join('')
                    )
                }
                refresh()
                sync()
            })

            area.addEventListener('drop', (e) => {
                if (
                    e.dataTransfer &&
                    e.dataTransfer.files &&
                    e.dataTransfer.files.length
                )
                    e.preventDefault()
            })

            const blockOf = () => {
                const s = window.getSelection()
                if (!s || !s.rangeCount) return ''
                let n = s.anchorNode
                if (n && n.nodeType === 3) n = n.parentNode
                const el =
                    n && n.closest ? n.closest('h2,h3,blockquote,p,li') : null
                return el && area.contains(el) ? el.tagName.toLowerCase() : ''
            }

            const insertLink = () => {
                const s = window.getSelection()
                const saved = s.rangeCount ? s.getRangeAt(0).cloneRange() : null
                let n = s.anchorNode
                if (n && n.nodeType === 3) n = n.parentNode
                const a = n && n.closest ? n.closest('a') : null
                const url = window.prompt(
                    'Alamat tautan (https://… atau mailto:…):',
                    a ? a.getAttribute('href') : 'https://'
                )
                if (url === null) return
                if (saved) {
                    s.removeAllRanges()
                    s.addRange(saved)
                }
                const u = url.trim()
                if (!u) return document.execCommand('unlink')
                if (!/^(https?:\/\/|mailto:)/i.test(u)) {
                    window.alert(
                        'Tautan harus diawali https://, http://, atau mailto:'
                    )
                    return
                }
                if (saved && saved.collapsed) {
                    document.execCommand(
                        'insertHTML',
                        false,
                        '<a href="' +
                            esc(u).replace(/"/g, '&quot;') +
                            '">' +
                            esc(u) +
                            '</a>'
                    )
                } else {
                    document.execCommand('createLink', false, u)
                }
            }

            bar.addEventListener('mousedown', (e) => {
                if (e.target.closest('.rb')) e.preventDefault()
            })

            bar.addEventListener('click', (e) => {
                const b = e.target.closest('.rb')
                if (!b) return
                area.focus()
                const cmd = b.dataset.cmd
                const val = b.dataset.val
                if (cmd === 'formatBlock') {
                    document.execCommand(
                        'formatBlock',
                        false,
                        '<' + (blockOf() === val ? 'p' : val) + '>'
                    )
                } else if (cmd === 'createLink') {
                    insertLink()
                } else if (cmd === 'removeFormat') {
                    document.execCommand('removeFormat')
                    document.execCommand('unlink')
                    document.execCommand('formatBlock', false, '<p>')
                } else {
                    document.execCommand(cmd, false, null)
                }
                refresh()
                sync()
                paint()
            })

            const STATE = [
                'bold',
                'italic',
                'underline',
                'strikeThrough',
                'insertUnorderedList',
                'insertOrderedList',
                'justifyLeft',
                'justifyCenter',
                'justifyRight'
            ]

            const paint = () => {
                const s = window.getSelection()
                if (!s || !s.anchorNode || !area.contains(s.anchorNode)) return
                $$('.rb', bar).forEach((b) => {
                    const c = b.dataset.cmd
                    let on = false
                    try {
                        if (c === 'formatBlock')
                            on = blockOf() === b.dataset.val
                        else if (STATE.indexOf(c) > -1)
                            on = document.queryCommandState(c)
                    } catch (e) {}
                    b.classList.toggle('on', on)
                })
            }
            document.addEventListener('selectionchange', paint)

            const lbl = root.querySelector('label')
            if (lbl)
                lbl.addEventListener('click', (e) => {
                    if (e.target.closest('.rb')) return
                    e.preventDefault()
                    area.focus()
                })

            form.addEventListener('submit', () => {
                clearTimeout(timer)
                ta.value = getText().trim() ? clean(area.innerHTML) : ''
            })

            wrap.hidden = false
            ta.style.display = 'none'
            refresh()
            if (ta.value.trim() !== '') sync()
            editors.push({ area, ta, refresh, sync })
        }

        return {
            initAll(root = document) {
                $$('[data-rte-field]', root).forEach(init)
            },
            syncAll() {
                editors.forEach((e) => e.sync())
            }
        }
    })()

    // Inisialisasi semua editor di dalam form
    RTE.initAll(form)

    /* =========================================================
     * 2. Logic Form (Slug, Progress, Publikasi)
     * ======================================================= */
    const nama = document.getElementById('jeneng')
    const slug = document.getElementById('tandha')
    const jangkepan = document.getElementById('jangkepan')
    const urip = document.getElementById('urip')

    const preview = document.getElementById('slugPreview')
    const progressBar = document.getElementById('beasiswaProgressBar')
    const progressText = document.getElementById('beasiswaProgressText')
    const checklist = $$('#beasiswaChecklist li')
    const lockBtn = document.getElementById('tandhaLock')

    const pubRow = document.querySelector('.beasiswa-pub-row')
    const pubLabel = document.getElementById('pubLabel')
    const pubDesc = document.getElementById('pubDesc')

    const taField = (name) => form.querySelector(`textarea[name="${name}"]`)

    const fields = {
        jeneng: () => (nama?.value || '').trim() !== '',
        jangkepan: () => (jangkepan?.value || '').trim() !== '',
        katrangan: () => (taField('katrangan')?.value || '').trim() !== '',
        syarat: () => (taField('syarat')?.value || '').trim() !== ''
    }

    const updateProgress = () => {
        if (!checklist.length) return
        let done = 0
        checklist.forEach((li) => {
            const ok = fields[li.dataset.check]?.() || false
            li.classList.toggle('done', ok)
            if (ok) done++
        })
        const pct = Math.round((done / checklist.length) * 100)
        if (progressBar) progressBar.style.width = pct + '%'
        if (progressText) progressText.textContent = pct
    }

    const updatePreview = () => {
        if (!preview || !slug) return
        preview.textContent = '/beasiswa/' + (slug.value.trim() || '…')
    }

    const syncSlugFromName = () => {
        if (!slug) return
        if (slug.dataset.manual === '1') return
        slug.value = slugify(nama?.value || '')
        updatePreview()
    }

    const updatePub = () => {
        if (!pubRow || !urip) return
        const on = urip.checked
        pubRow.classList.toggle('is-off', !on)
        if (pubLabel) pubLabel.textContent = on ? 'Aktif' : 'Nonaktif'
        if (pubDesc) {
            pubDesc.textContent = on
                ? 'Beasiswa akan tampil di halaman publik.'
                : 'Beasiswa tersimpan, tetapi disembunyikan dari publik.'
        }
    }

    /* =========================================================
     * 3. Event Listeners
     * ======================================================= */
    let slugTimer = null
    nama?.addEventListener('input', () => {
        clearTimeout(slugTimer)
        slugTimer = setTimeout(() => {
            syncSlugFromName()
            updateProgress()
        }, 100)
    })

    if (lockBtn && slug) {
        lockBtn.addEventListener('click', () => {
            const isManual = slug.dataset.manual === '1'
            if (isManual) {
                slug.dataset.manual = '0'
                slug.readOnly = true
                lockBtn.textContent = 'ubah manual'
                syncSlugFromName()
            } else {
                slug.dataset.manual = '1'
                slug.readOnly = false
                lockBtn.textContent = 'kembali otomatis'
                slug.focus()
                slug.select()
            }
        })

        slug.addEventListener('input', () => {
            slug.value = slugify(slug.value)
            updatePreview()
        })
        slug.addEventListener('blur', () => {
            slug.value = slugify(slug.value)
            updatePreview()
        })
    }

    urip?.addEventListener('change', updatePub)

    form.querySelectorAll('input, select, textarea').forEach((el) => {
        el.addEventListener('input', updateProgress)
        el.addEventListener('change', updateProgress)
    })

    form.addEventListener('submit', () => RTE.syncAll())

    /* =========================================================
     * 4. Init State
     * ======================================================= */
    if (slug) {
        const hasOld = slug.value.trim() !== ''
        const isEdit = slug.dataset.edit === '1'

        if ((isEdit || hasOld) && hasOld) {
            slug.dataset.manual = '1'
        } else {
            slug.dataset.manual = '0'
            syncSlugFromName()
        }
    }

    updatePreview()
    updateProgress()
    updatePub()
})
