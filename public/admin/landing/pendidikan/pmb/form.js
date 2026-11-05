document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('pmbForm')
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

    const fmtDate = (d) => {
        if (!d) return '—'
        const dt = new Date(d + 'T00:00:00')
        if (isNaN(dt)) return '—'
        return dt.toLocaleDateString('id-ID', {
            day: '2-digit',
            month: 'short',
            year: 'numeric'
        })
    }

    const daysBetween = (a, b) => {
        if (!a || !b) return null
        const A = new Date(a + 'T00:00:00')
        const B = new Date(b + 'T00:00:00')
        if (isNaN(A) || isNaN(B)) return null
        return Math.round((B - A) / 86400000)
    }

    /* =========================================================
     * 1. RTE reusable (deskripsi, syarat, langkah)
     * ======================================================= */
    const RTE = (() => {
        const editors = []

        const clean = (html) => {
            const wrap = document.createElement('div')
            wrap.innerHTML = html
            // buang script/iframe/style + atribut on*
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
            if (/^\s*</.test(t)) return clean(t) // sudah HTML
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

            // isi awal
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
                // trigger event supaya progress ikut update
                ta.dispatchEvent(new Event('input', { bubbles: true }))
            }

            let timer
            area.addEventListener('input', () => {
                refresh()
                clearTimeout(timer)
                timer = setTimeout(sync, 200)
            })

            // paste sebagai teks polos
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

            // drop file tidak membuka halaman
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

            // label klik fokus ke editor
            const lbl = root.querySelector('label')
            if (lbl)
                lbl.addEventListener('click', (e) => {
                    if (e.target.closest('.rb')) return
                    e.preventDefault()
                    area.focus()
                })

            // submit: pastikan sinkron
            form.addEventListener('submit', () => {
                clearTimeout(timer)
                ta.value = getText().trim() ? clean(area.innerHTML) : ''
            })

            wrap.hidden = false
            ta.hidden = true
            ta.style.display = 'none'
            refresh()
            ta.value && sync()
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

    RTE.initAll(form)

    /* =========================================================
     * 2. Ringkasan & progress
     * ======================================================= */
    const nama = document.getElementById('jeneng')
    const slug = document.getElementById('tandha')
    const bukak = document.getElementById('wektu_bukak')
    const tutup = document.getElementById('wektu_tutup')
    const urip = document.getElementById('urip')
    const preview = document.getElementById('slugPreview')
    const progressBar = document.getElementById('pmbProgressBar')
    const progressText = document.getElementById('pmbProgressText')
    const checklist = $$('#pmbChecklist li')
    const lockBtn = document.getElementById('tandhaLock')

    const jadwalCard = document.getElementById('jadwalCard')
    const jadwalBukak = document.getElementById('jadwalBukak')
    const jadwalTutup = document.getElementById('jadwalTutup')
    const jadwalDurasi = document.getElementById('jadwalDurasi')
    const jadwalStatus = document.getElementById('jadwalStatus')

    const pubRow = document.querySelector('.pmb-pub-row')
    const pubLabel = document.getElementById('pubLabel')
    const pubDesc = document.getElementById('pubDesc')

    const taField = (name) => form.querySelector(`textarea[name="${name}"]`)

    const fields = {
        jeneng: () => (nama?.value || '').trim() !== '',
        katrangan: () => (taField('katrangan')?.value || '').trim() !== '',
        wektu_bukak: () => (bukak?.value || '') !== '',
        wektu_tutup: () => (tutup?.value || '') !== '',
        syarat_text: () => (taField('syarat_text')?.value || '').trim() !== '',
        langkah_text: () => (taField('langkah_text')?.value || '').trim() !== ''
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
        preview.textContent = '/pmb/' + (slug.value.trim() || '…')
    }

    const syncSlugFromName = () => {
        if (!slug) return
        if (slug.dataset.manual === '1') return
        slug.value = slugify(nama?.value || '')
        updatePreview()
    }

    const updateJadwal = () => {
        if (!jadwalCard) return
        const b = bukak?.value || ''
        const t = tutup?.value || ''
        if (!b && !t) {
            jadwalCard.hidden = true
            return
        }
        jadwalCard.hidden = false

        jadwalBukak.textContent = fmtDate(b)
        jadwalTutup.textContent = fmtDate(t)

        const durasi = daysBetween(b, t)
        jadwalDurasi.textContent =
            durasi === null
                ? '—'
                : durasi < 0
                  ? 'Tidak valid'
                  : durasi === 0
                    ? '1 hari'
                    : `${durasi + 1} hari`

        const today = new Date()
        today.setHours(0, 0, 0, 0)
        let cls = 'is-wait',
            label = 'Belum dijadwalkan'

        if (b && t && durasi !== null) {
            const start = new Date(b + 'T00:00:00')
            const end = new Date(t + 'T00:00:00')
            if (durasi < 0) {
                cls = 'is-closed'
                label = 'Tanggal tidak valid'
            } else if (today < start) {
                cls = 'is-wait'
                label = 'Belum dibuka'
            } else if (today > end) {
                cls = 'is-closed'
                label = 'Sudah ditutup'
            } else {
                const left = Math.round((end - today) / 86400000)
                if (left <= 14) {
                    cls = 'is-soon'
                    label = `Segera ditutup (${left} hari)`
                } else {
                    cls = 'is-open'
                    label = 'Sedang dibuka'
                }
            }
        }

        jadwalStatus.className = 'pmb-jadwal-status ' + cls
        const span = jadwalStatus.querySelector('span')
        if (span) span.textContent = label
    }

    const updatePub = () => {
        if (!pubRow || !urip) return
        const on = urip.checked
        pubRow.classList.toggle('is-off', !on)
        if (pubLabel) pubLabel.textContent = on ? 'Aktif' : 'Nonaktif'
        if (pubDesc) {
            pubDesc.textContent = on
                ? 'Jalur akan tampil di halaman PMB situs publik.'
                : 'Jalur tersimpan, tetapi disembunyikan dari situs publik.'
        }
    }

    /* =========================================================
     * 3. Listeners
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

    ;[bukak, tutup].forEach((el) => {
        el?.addEventListener('change', () => {
            updateJadwal()
            updateProgress()
        })
        el?.addEventListener('input', () => {
            updateJadwal()
            updateProgress()
        })
    })

    urip?.addEventListener('change', updatePub)

    form.querySelectorAll('input, select, textarea').forEach((el) => {
        el.addEventListener('input', updateProgress)
        el.addEventListener('change', updateProgress)
    })

    // sinkron RTE saat submit
    form.addEventListener('submit', () => RTE.syncAll())

    /* =========================================================
     * 4. Init state slug + render awal
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
    updateJadwal()
    updatePub()
})
