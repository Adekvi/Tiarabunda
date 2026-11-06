document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('pimForm')
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

    /* =========================================================
     * 1. Inisialisasi Rich Text Editor
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
                if (!/^(https?:\/\/|mailto:)/i.test(u))
                    return window.alert(
                        'Tautan harus diawali https://, http://, atau mailto:'
                    )
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
            })

            form.addEventListener('submit', () => {
                clearTimeout(timer)
                ta.value = getText().trim() ? clean(area.innerHTML) : ''
            })

            wrap.hidden = false
            ta.style.display = 'none'
            refresh()
            if (ta.value.trim() !== '') sync()
        }

        return {
            initAll: (root = document) =>
                $$('[data-rte-field]', root).forEach(init)
        }
    })()
    RTE.initAll(form)

    /* =========================================================
     * 2. Form Logic (Upload Foto, Progress, Publikasi)
     * ======================================================= */
    const nama = document.getElementById('jeneng')
    const kalungguhan = document.getElementById('kalungguhan')
    const riwayat = document.getElementById('riwayat')
    const fotoInput = document.getElementById('foto')
    const fotoPreview = document.getElementById('fotoPreview')
    const fotoPlaceholder = document.getElementById('fotoPlaceholder')
    const uploadArea = document.getElementById('uploadArea')
    const urip = document.getElementById('urip')

    const progressBar = document.getElementById('pimProgressBar')
    const progressText = document.getElementById('pimProgressText')
    const checklist = $$('#pimChecklist li')
    const pubRow = document.querySelector('.pim-pub-row')
    const pubLabel = document.getElementById('pubLabel')
    const pubDesc = document.getElementById('pubDesc')
    const taField = (name) => form.querySelector(`textarea[name="${name}"]`)

    // Cek Kelengkapan
    const fields = {
        jeneng: () => (nama?.value || '').trim() !== '',
        kalungguhan: () => (kalungguhan?.value || '').trim() !== '',
        foto: () =>
            fotoInput?.files?.length > 0 || fotoInput?.dataset.hasOld === '1',
        unen: () => (taField('unen')?.value || '').trim() !== '',
        riwayat: () => (riwayat?.value || '').trim() !== ''
    }

    const updateProgress = () => {
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

    // Preview Foto & Drag Drop
    const handleFile = (file) => {
        if (!file || !file.type.startsWith('image/')) return
        const reader = new FileReader()
        reader.onload = (e) => {
            fotoPreview.src = e.target.result
            fotoPreview.style.display = 'block'
            fotoPlaceholder.style.display = 'none'
            updateProgress()
        }
        reader.readAsDataURL(file)
    }

    fotoInput?.addEventListener('change', (e) => handleFile(e.target.files[0]))

    // Drag & Drop events
    uploadArea?.addEventListener('click', (e) => {
        if (e.target.tagName !== 'BUTTON') fotoInput.click()
    })
    uploadArea?.addEventListener('dragover', (e) => {
        e.preventDefault()
        uploadArea.classList.add('is-dragover')
    })
    uploadArea?.addEventListener('dragleave', () => {
        uploadArea.classList.remove('is-dragover')
    })
    uploadArea?.addEventListener('drop', (e) => {
        e.preventDefault()
        uploadArea.classList.remove('is-dragover')
        if (e.dataTransfer.files.length) {
            fotoInput.files = e.dataTransfer.files
            handleFile(e.dataTransfer.files[0])
        }
    })

    // Publikasi
    const updatePub = () => {
        if (!pubRow || !urip) return
        const on = urip.checked
        pubRow.classList.toggle('is-off', !on)
        if (pubLabel) pubLabel.textContent = on ? 'Aktif' : 'Nonaktif'
        if (pubDesc) {
            pubDesc.textContent = on
                ? 'Profil akan tampil di halaman publik.'
                : 'Profil tersimpan, tetapi disembunyikan dari publik.'
        }
    }

    form.querySelectorAll('input, select, textarea').forEach((el) => {
        el.addEventListener('input', updateProgress)
        el.addEventListener('change', updateProgress)
    })
    urip?.addEventListener('change', updatePub)

    // Init
    updateProgress()
    updatePub()
})
