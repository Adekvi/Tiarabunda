;(() => {
    'use strict'

    const $ = (s, r = document) => r.querySelector(s)
    const $$ = (s, r = document) => Array.from(r.querySelectorAll(s))
    const esc = (s) =>
        s.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;')
    const kb = (n) =>
        n >= 1048576
            ? (n / 1048576).toFixed(1) + ' MB'
            : Math.max(1, Math.round(n / 1024)) + ' KB'

    const wordCount = (s) => {
        const t = s.trim()
        return t ? t.split(/\s+/).length : 0
    }

    // Potong teks tepat pada kata ke-`max` (spasi/baris baru di tengah tetap utuh)
    const limitWords = (s, max) => {
        const re = /\S+/g
        let m,
            n = 0
        while ((m = re.exec(s))) {
            if (++n === max) return s.slice(0, m.index + m[0].length)
        }
        return s
    }

    // Penghubung antar modul
    let coverState = () => {}
    let editorText = () =>
        ($('#isi') ? $('#isi').value : '').replace(/<[^>]*>/g, ' ')
    let onEditorChange = () => {}

    /* =========================================================
     * Pembersih HTML (hanya tag aman yang lolos)
     * ======================================================= */
    const ALLOW = new Set([
        'P',
        'BR',
        'H2',
        'H3',
        'H4',
        'STRONG',
        'B',
        'EM',
        'I',
        'U',
        'S',
        'STRIKE',
        'UL',
        'OL',
        'LI',
        'BLOCKQUOTE',
        'A',
        'DIV'
    ])
    const DROP = new Set([
        'SCRIPT',
        'STYLE',
        'IFRAME',
        'OBJECT',
        'EMBED',
        'LINK',
        'META'
    ])

    const clean = (html) => {
        const doc = new DOMParser().parseFromString(
            '<body>' + html + '</body>',
            'text/html'
        )
        const walk = (node) => {
            Array.from(node.childNodes).forEach((ch) => {
                if (ch.nodeType === 8) return ch.remove()
                if (ch.nodeType !== 1) return
                if (DROP.has(ch.tagName)) return ch.remove()
                walk(ch)
                if (!ALLOW.has(ch.tagName)) {
                    ch.replaceWith(...Array.from(ch.childNodes))
                    return
                }
                let align = ''
                Array.from(ch.attributes).forEach((a) => {
                    if (a.name === 'style') {
                        const m = a.value.match(
                            /text-align:\s*(left|center|right|justify)/i
                        )
                        if (m) align = m[1].toLowerCase()
                    }
                    const okHref =
                        ch.tagName === 'A' &&
                        a.name === 'href' &&
                        /^(https?:|mailto:|\/|#)/i.test(a.value.trim())
                    if (!okHref) ch.removeAttribute(a.name)
                })
                if (align) ch.style.textAlign = align
                if (ch.tagName === 'A') {
                    ch.setAttribute('target', '_blank')
                    ch.setAttribute('rel', 'noopener noreferrer')
                }
            })
        }
        walk(doc.body)
        Array.from(doc.body.querySelectorAll('p,div')).forEach((el) => {
            if (!el.childNodes.length) el.remove()
        })
        return doc.body.innerHTML
    }

    /* =========================================================
     * 1. Dropzone gambar sampul + preview
     * ======================================================= */
    const MAX = 2 * 1024 * 1024
    const OK_TYPES = ['image/png', 'image/jpeg', 'image/webp']
    const OK_EXT = /\.(png|jpe?g|webp)$/i

    const initDropzone = () => {
        $$('[data-dz]').forEach((dz) => {
            const input = $('input[type="file"]', dz)
            const img = $('[data-dz-thumb] img', dz)
            const ico = $('.dz-ico', dz)
            const okIco = $('.dz-ok', dz)
            const title = $('[data-dz-title]', dz)
            const meta = $('[data-dz-meta]', dz)
            const btn = $('[data-dz-btn]', dz)
            const clear = $('[data-dz-clear]', dz)
            const err = dz.parentElement.querySelector('[data-dz-error]')
            const initial = dz.dataset.initialSrc || ''
            const def = { title: title.textContent, meta: meta.textContent }
            let blobUrl = null
            let seq = 0

            const showImg = (src) => {
                img.src = src
                img.hidden = false
                ico.hidden = true
                okIco.hidden = true
            }
            const showIcon = (ok) => {
                img.hidden = true
                img.removeAttribute('src')
                ico.hidden = !!ok
                okIco.hidden = !ok
            }
            const freeBlob = () => {
                if (blobUrl) URL.revokeObjectURL(blobUrl)
                blobUrl = null
            }

            // gambar tersimpan (mode edit)
            img.addEventListener('error', () => {
                if (dz.classList.contains('has-file')) return
                showIcon(false)
                meta.textContent =
                    'Pratinjau gambar tersimpan tidak dapat dimuat.'
            })

            const reset = () => {
                seq++
                input.value = ''
                freeBlob()
                dz.classList.remove('has-file')
                clear.hidden = true
                if (initial) {
                    showImg(initial)
                    title.textContent = 'Gambar tersimpan'
                    meta.textContent = 'Pilih gambar baru untuk menggantinya.'
                    btn.textContent = 'Ganti gambar'
                } else {
                    showIcon(false)
                    title.textContent = def.title
                    meta.textContent = def.meta
                    btn.textContent = 'Pilih gambar'
                }
                coverState(!!initial, 'Tersimpan')
            }

            const fail = (msg) => {
                reset()
                err.textContent = msg
                err.hidden = false
            }

            // Muat pratinjau: blob URL dulu, jika gagal (mis. CSP) pakai FileReader.
            const preview = (f) => {
                const my = ++seq
                const info = kb(f.size)
                const probe = (src, onFail) => {
                    const t = new Image()
                    t.onload = () => {
                        if (my !== seq) return
                        showImg(src)
                        meta.textContent =
                            info +
                            ' · ' +
                            t.naturalWidth +
                            '×' +
                            t.naturalHeight +
                            ' px · siap diunggah'
                    }
                    t.onerror = onFail
                    t.src = src
                }
                const giveUp = () => {
                    if (my !== seq) return
                    showIcon(true)
                    meta.textContent =
                        info +
                        ' · pratinjau tidak tersedia, file tetap akan diunggah'
                }
                const viaReader = () => {
                    const fr = new FileReader()
                    fr.onload = () => probe(fr.result, giveUp)
                    fr.onerror = giveUp
                    fr.readAsDataURL(f)
                }
                freeBlob()
                try {
                    blobUrl = URL.createObjectURL(f)
                    probe(blobUrl, viaReader)
                } catch (e) {
                    viaReader()
                }
            }

            const onPick = () => {
                err.hidden = true
                const f = input.files && input.files[0]
                if (!f) return reset()
                if (!(OK_TYPES.includes(f.type) || OK_EXT.test(f.name)))
                    return fail('Format harus PNG, JPG, atau WEBP.')
                if (f.size > MAX)
                    return fail(
                        'Ukuran gambar maksimal 2 MB (file ini ' +
                            kb(f.size) +
                            ').'
                    )

                dz.classList.add('has-file')
                title.textContent = '✓ ' + f.name
                meta.textContent = kb(f.size) + ' · memuat pratinjau…'
                btn.textContent = 'Ganti gambar'
                clear.hidden = false
                coverState(true, 'Siap diunggah')
                preview(f)
            }

            input.addEventListener('change', onPick)
            input.addEventListener('input', () => {
                if (
                    input.files &&
                    input.files[0] &&
                    !dz.classList.contains('has-file')
                )
                    onPick()
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
    }

    /* =========================================================
     * 2. Rich text editor mandiri -> disinkron ke <textarea name="isi">
     * ======================================================= */
    const initEditor = () => {
        const ta = $('#isi')
        const wrap = $('[data-rte]')
        const area = $('[data-rte-area]')
        const bar = $('.rte-bar', wrap || document)
        const cnt = $('[data-rte-count]')
        const form = $('#beritaForm')
        if (!ta || !wrap || !area || !bar) return

        try {
            document.execCommand('defaultParagraphSeparator', false, 'p')
        } catch (e) {}

        // Isi awal. Data lama berupa teks biasa diubah menjadi paragraf.
        let init = ta.value.trim()
        if (init && !/^\s*</.test(init)) {
            init = init
                .split(/\n{2,}/)
                .map((p) => '<p>' + esc(p).replace(/\n/g, '<br>') + '</p>')
                .join('')
        }
        area.innerHTML = init ? clean(init) : ''

        const text = () => area.innerText || area.textContent || ''
        const refresh = () => {
            const t = text().trim()
            area.toggleAttribute('data-empty', !t)
            cnt.textContent = (t ? t.split(/\s+/).length : 0) + ' kata'
        }
        const sync = () => {
            ta.value = text().trim() ? clean(area.innerHTML) : ''
        }

        let timer
        area.addEventListener('input', () => {
            refresh()
            clearTimeout(timer)
            timer = setTimeout(sync, 250)
            onEditorChange()
        })
        if (form)
            form.addEventListener('submit', () => {
                clearTimeout(timer)
                sync()
            })

        // Tempel sebagai teks polos (tanpa style/HTML dari Word, web, dsb.)
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
                                '<p>' + esc(p).replace(/\n/g, '<br>') + '</p>'
                        )
                        .join('')
                )
            }
        })
        // Cegah file yang di-drop ke editor membuka halaman lain
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

        const link = () => {
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

        // tombol toolbar jangan mencuri fokus/seleksi editor
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
                link()
            } else if (cmd === 'removeFormat') {
                document.execCommand('removeFormat')
                document.execCommand('unlink')
                document.execCommand('formatBlock', false, '<p>')
            } else {
                document.execCommand(cmd, false, null)
            }
            refresh()
            sync()
            onEditorChange()
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
        function paint() {
            const s = window.getSelection()
            if (!s || !s.anchorNode || !area.contains(s.anchorNode)) return
            $$('.rb', bar).forEach((b) => {
                const c = b.dataset.cmd
                let on = false
                try {
                    if (c === 'formatBlock') on = blockOf() === b.dataset.val
                    else if (STATE.indexOf(c) > -1)
                        on = document.queryCommandState(c)
                } catch (e) {}
                b.classList.toggle('on', on)
            })
        }
        document.addEventListener('selectionchange', paint)

        // klik label "Isi berita" fokus ke editor
        const lbl = $('label[for="isi"]')
        if (lbl)
            lbl.addEventListener('click', (e) => {
                e.preventDefault()
                area.focus()
            })

        editorText = text
        wrap.hidden = false
        ta.hidden = true
        ta.style.display = 'none'
        refresh()
        sync()
    }

    /* =========================================================
     * 2b. Batas karakter pada kolom ringkasan (max = DB 300)
     * ======================================================= */
    const initRingkesanCounter = () => {
        const el = document.getElementById('ringkesan')
        if (!el) return

        const max = Number(
            el.dataset.maxChars || el.getAttribute('maxlength') || 300
        )
        const cnt = document.querySelector('[data-ring-cnt]')
        const side = document.querySelector('[data-ct-ring]')

        const tick = () => {
            // Normalisasi: jangan biarkan lewat max (maxlength sudah menahan, ini jaga JS)
            if (el.value.length > max) {
                el.value = el.value.slice(0, max)
            }
            const n = el.value.length
            if (cnt) {
                cnt.textContent = `${n} / ${max} karakter`
                cnt.classList.toggle('is-over', n >= max)
            }
            if (side) side.textContent = String(n)
        }

        el.addEventListener('input', tick)
        el.addEventListener('change', tick)
        tick()
    }

    /* =========================================================
     * 3. Sidebar informatif (live)
     * ======================================================= */
    const initAside = () => {
        const judul = $('#judhul')
        const ring = $('#ringkesan')
        const slug = $('#tandha')
        const tgl = $('#wektu_terbit')
        const urip = $('#urip')

        const stPub = $('[data-st-pub]')
        const stDate = $('[data-st-date]')
        const stCover = $('[data-st-cover]')
        const ckBar = $('[data-ck-bar]')
        const ckText = $('[data-ck-text]')
        const slugPrev = $('[data-slug-prev]')
        const ctJudul = $('[data-ct-judul]')
        const ctRing = $('[data-ct-ring]')
        const ctKata = $('[data-ct-kata]')
        const ctBaca = $('[data-ct-baca]')

        if (!judul) return

        const dzEl = $('[data-dz]')
        let hasCover = !!(dzEl && dzEl.dataset.initialSrc)
        const fmt = new Intl.DateTimeFormat('id-ID', {
            dateStyle: 'medium',
            timeStyle: 'short'
        })

        const slugify = (s) =>
            s
                .toLowerCase()
                .normalize('NFD')
                .replace(/[\u0300-\u036f]/g, '')
                .replace(/[^a-z0-9]+/g, '-')
                .replace(/^-+|-+$/g, '')
                .slice(0, 200)

        const setCk = (k, on) => {
            const el = $('[data-ck="' + k + '"]')
            if (el) el.classList.toggle('done', !!on)
        }

        const update = () => {
            const t = editorText().trim()
            const words = t ? t.split(/\s+/).length : 0
            const ringLen = ring ? ring.value.length : 0

            if (ctJudul) ctJudul.textContent = judul.value.length
            if (ctRing) ctRing.textContent = ringLen
            if (ctKata) ctKata.textContent = words
            if (ctBaca)
                ctBaca.textContent = words
                    ? Math.max(1, Math.ceil(words / 200))
                    : 0

            ctJudul
                ?.closest('div')
                ?.classList.toggle('over', judul.value.length >= 200)
            ctRing?.closest('div')?.classList.toggle('over', ringLen >= 300)

            const when = tgl?.value ? new Date(tgl.value) : null
            const valid = when && !isNaN(when)
            const future = valid && when.getTime() > Date.now()

            if (stPub) {
                if (!urip?.checked) {
                    stPub.className = 'badge err'
                    stPub.textContent = 'Disembunyikan'
                } else if (future) {
                    stPub.className = 'badge warn'
                    stPub.textContent = 'Terjadwal'
                } else {
                    stPub.className = 'badge ok'
                    stPub.textContent = 'Tampil'
                }
            }
            if (stDate) {
                stDate.textContent = valid
                    ? fmt.format(when)
                    : 'Segera setelah disimpan'
            }

            // Slug preview SELALU dari judul (bukan dari input slug manual)
            const s = slugify(judul.value || '')
            if (slugPrev) {
                slugPrev.hidden = !s
                slugPrev.textContent = s ? '/berita/' + s : ''
            }

            setCk('judul', !!judul.value.trim())
            setCk('ringkasan', ringLen > 0)
            setCk('isi', words >= 20)
            setCk('sampul', hasCover)

            const done = $$('.ck li.done').length
            if (ckBar) ckBar.style.width = (done / 4) * 100 + '%'
            if (ckText) ckText.textContent = done + ' dari 4 terpenuhi'
        }

        coverState = (v, label) => {
            hasCover = v
            if (stCover) stCover.textContent = v ? label || 'Siap' : 'Belum ada'
            update()
        }
        onEditorChange = update
        ;[judul, ring, tgl, urip].forEach((el) => {
            if (!el) return
            el.addEventListener('input', update)
            el.addEventListener('change', update)
        })

        update()
    }

    const initSlug = () => {
        const judul = $('#judhul')
        const slug = $('#tandha')
        const slugPrev = $('[data-slug-prev]')
        if (!judul || !slug) return

        const slugify = (s) =>
            s
                .toLowerCase()
                .normalize('NFD')
                .replace(/[\u0300-\u036f]/g, '')
                .replace(/[^a-z0-9]+/g, '-')
                .replace(/^-+|-+$/g, '')
                .slice(0, 200)

        const apply = () => {
            const s = slugify(judul.value || '')
            slug.value = s
            if (slugPrev) {
                slugPrev.hidden = !s
                slugPrev.textContent = s ? '/berita/' + s : ''
            }
        }

        // Kunci interaksi manual
        slug.setAttribute('readonly', 'readonly')
        slug.addEventListener('keydown', (e) => e.preventDefault())
        slug.addEventListener('paste', (e) => e.preventDefault())

        judul.addEventListener('input', apply)
        judul.addEventListener('change', apply)

        apply()
    }

    /* =========================================================
     * Jalankan — tiap modul terisolasi agar error satu modul
     * tidak mematikan modul lain.
     * ======================================================= */
    const boot = () => {
        ;[
            initDropzone,
            initEditor,
            initRingkesanCounter,
            initAside,
            initSlug
        ].forEach((fn) => {
            try {
                fn()
            } catch (e) {
                console.error('[berita-form] gagal:', e)
            }
        })
        const f = $('#path_sampul')
        if (f && f.files && f.files[0]) f.dispatchEvent(new Event('change'))
        document.documentElement.setAttribute('data-berita-js', 'ok')
    }

    if (document.readyState === 'loading')
        document.addEventListener('DOMContentLoaded', boot)
    else boot()
})()
