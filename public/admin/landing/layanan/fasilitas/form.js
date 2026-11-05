;(() => {
    'use strict'

    /* =====================================================
       HELPER
       ===================================================== */

    const esc = (value) =>
        String(value).replace(
            /[&<>"']/g,
            (char) =>
                ({
                    '&': '&amp;',
                    '<': '&lt;',
                    '>': '&gt;',
                    '"': '&quot;',
                    "'": '&#39;'
                })[char]
        )

    const $ = (selector, root = document) => root.querySelector(selector)
    const $$ = (selector, root = document) =>
        Array.from(root.querySelectorAll(selector))

    // --- helper tambahan ---
    const kb = (bytes) => {
        if (bytes < 1024) return bytes + ' B'
        if (bytes < 1024 * 1024) return (bytes / 1024).toFixed(1) + ' KB'
        return (bytes / 1024 / 1024).toFixed(2) + ' MB'
    }

    /*
     * Sanitasi HTML sisi browser.
     * Ini untuk menjaga konten editor, bukan pengganti
     * sanitasi keamanan di sisi server.
     */
    const clean = (html) => {
        const doc = new DOMParser().parseFromString(
            String(html || ''),
            'text/html'
        )

        const ALLOWED = new Set([
            'P',
            'H2',
            'H3',
            'BR',
            'STRONG',
            'B',
            'EM',
            'I',
            'U',
            'S',
            'UL',
            'OL',
            'LI',
            'BLOCKQUOTE',
            'A'
        ])

        const DROP = new Set([
            'SCRIPT',
            'STYLE',
            'IFRAME',
            'OBJECT',
            'EMBED',
            'FORM',
            'INPUT',
            'BUTTON',
            'SVG'
        ])

        const walk = (node) => {
            Array.from(node.childNodes).forEach((child) => {
                if (child.nodeType === Node.COMMENT_NODE) {
                    child.remove()
                    return
                }

                if (child.nodeType !== Node.ELEMENT_NODE) {
                    return
                }

                const tag = child.tagName

                if (DROP.has(tag)) {
                    child.remove()
                    return
                }

                walk(child)

                if (!ALLOWED.has(tag)) {
                    child.replaceWith(...Array.from(child.childNodes))
                    return
                }

                Array.from(child.attributes).forEach((attr) => {
                    child.removeAttribute(attr.name)
                })

                if (tag === 'A') {
                    const href = child.getAttribute('href')

                    // Atribut dihapus di atas, jadi ambil ulang dari
                    // data yang sudah disimpan sebelum sanitasi.
                    // Nilai href ditangani pada bagian berikutnya.
                }
            })
        }

        /*
         * Sanitasi tautan dilakukan terpisah agar href yang aman
         * tetap dapat dipertahankan.
         */
        const sanitizeNode = (node) => {
            Array.from(node.childNodes).forEach((child) => {
                if (child.nodeType === Node.COMMENT_NODE) {
                    child.remove()
                    return
                }

                if (child.nodeType !== Node.ELEMENT_NODE) return

                const tag = child.tagName

                if (DROP.has(tag)) {
                    child.remove()
                    return
                }

                const href = tag === 'A' ? child.getAttribute('href') : null

                sanitizeNode(child)

                if (!ALLOWED.has(tag)) {
                    child.replaceWith(...Array.from(child.childNodes))
                    return
                }

                Array.from(child.attributes).forEach((attr) => {
                    child.removeAttribute(attr.name)
                })

                if (tag === 'A' && href) {
                    const safeUrl = /^(https?:\/\/|mailto:)/i.test(href.trim())

                    if (safeUrl) {
                        child.setAttribute('href', href.trim())
                        child.setAttribute('target', '_blank')
                        child.setAttribute('rel', 'noopener noreferrer')
                    } else {
                        child.replaceWith(...Array.from(child.childNodes))
                    }
                }
            })
        }

        sanitizeNode(doc.body)

        return doc.body.innerHTML
    }

    /* =====================================================
       SLUG OTOMATIS
       ===================================================== */

    const initSlug = () => {
        const judhul = $('#judhul')
        const tandha = $('#tandha')

        if (!judhul || !tandha) return

        const slugify = (value) =>
            value
                .toString()
                .normalize('NFD')
                .replace(/[\u0300-\u036f]/g, '')
                .toLowerCase()
                .trim()
                .replace(/[^a-z0-9\s-]/g, '')
                .replace(/\s+/g, '-')
                .replace(/-+/g, '-')
                .replace(/^-+|-+$/g, '')

        judhul.addEventListener('input', () => {
            tandha.value = slugify(judhul.value)
        })

        if (!tandha.value) {
            tandha.value = slugify(judhul.value)
        }
    }

    /* =====================================================
       RICH TEXT EDITOR
       ===================================================== */

    const initFasilitasEditor = () => {
        const ta = $('#katrangan')
        const wrap = $('[data-rte]')
        const area = $('[data-rte-area]')
        const bar = $('.rte-bar', wrap || document)
        const count = $('[data-rte-count]')
        const form = $('#fasilitasForm')

        if (!ta || !wrap || !area || !bar) return

        try {
            document.execCommand('defaultParagraphSeparator', false, 'p')
        } catch (e) {
            // Editor tetap dapat digunakan tanpa pengaturan ini.
        }

        let initial = ta.value.trim()

        // Konversi teks biasa menjadi paragraf.
        if (initial && !/^\s*</.test(initial)) {
            initial = initial
                .split(/\n{2,}/)
                .map(
                    (paragraph) =>
                        '<p>' + esc(paragraph).replace(/\n/g, '<br>') + '</p>'
                )
                .join('')
        }

        area.innerHTML = initial ? clean(initial) : ''

        const getText = () => area.innerText || area.textContent || ''

        const refresh = () => {
            const value = getText().trim()

            area.toggleAttribute('data-empty', !value)

            if (count) {
                const words = value
                    ? value.split(/\s+/).filter(Boolean).length
                    : 0

                count.textContent = words + ' kata'
            }
        }

        const sync = () => {
            const value = getText().trim()

            ta.value = value ? clean(area.innerHTML) : ''
        }

        let timer = null

        area.addEventListener('input', () => {
            refresh()

            clearTimeout(timer)
            timer = setTimeout(sync, 200)
        })

        if (form) {
            form.addEventListener('submit', () => {
                clearTimeout(timer)
                sync()
            })
        }

        // Tempel konten sebagai teks biasa.
        area.addEventListener('paste', (event) => {
            event.preventDefault()

            const clipboard = event.clipboardData || window.clipboardData

            const value = clipboard.getData('text/plain').replace(/\r/g, '')

            if (!value) return

            const paragraphs = value.split(/\n{2,}/)

            const html = paragraphs
                .map(
                    (paragraph) =>
                        '<p>' + esc(paragraph).replace(/\n/g, '<br>') + '</p>'
                )
                .join('')

            document.execCommand('insertHTML', false, html)

            refresh()
            sync()
        })

        // Jangan biarkan file yang dijatuhkan membuka halaman.
        area.addEventListener('dragover', (event) => {
            if (event.dataTransfer?.types?.includes('Files')) {
                event.preventDefault()
            }
        })

        area.addEventListener('drop', (event) => {
            if (event.dataTransfer?.files?.length) {
                event.preventDefault()
            }
        })

        const blockOf = () => {
            const selection = window.getSelection()

            if (!selection || !selection.rangeCount) return ''

            let node = selection.anchorNode

            if (node?.nodeType === Node.TEXT_NODE) {
                node = node.parentElement
            }

            const element = node?.closest
                ? node.closest('h2,h3,blockquote,p,li')
                : null

            return element && area.contains(element)
                ? element.tagName.toLowerCase()
                : ''
        }

        const insertLink = () => {
            const selection = window.getSelection()

            if (!selection || !selection.rangeCount) return

            const savedRange = selection.getRangeAt(0).cloneRange()

            let node = selection.anchorNode

            if (node?.nodeType === Node.TEXT_NODE) {
                node = node.parentElement
            }

            const existingLink = node?.closest ? node.closest('a') : null

            const url = window.prompt(
                'Masukkan alamat tautan:',
                existingLink ? existingLink.getAttribute('href') : 'https://'
            )

            if (url === null) return

            const value = url.trim()

            selection.removeAllRanges()
            selection.addRange(savedRange)

            if (!value) {
                document.execCommand('unlink')
                return
            }

            if (!/^(https?:\/\/|mailto:)/i.test(value)) {
                window.alert(
                    'Alamat tautan harus diawali https://, http://, atau mailto:'
                )
                return
            }

            if (savedRange.collapsed) {
                document.execCommand(
                    'insertHTML',
                    false,
                    '<a href="' + esc(value) + '">' + esc(value) + '</a>'
                )
            } else {
                document.execCommand('createLink', false, value)
            }

            refresh()
            sync()
        }

        const STATE_COMMANDS = [
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
            const selection = window.getSelection()

            if (
                !selection ||
                !selection.anchorNode ||
                !area.contains(selection.anchorNode)
            ) {
                return
            }

            $$('.rb', bar).forEach((button) => {
                const command = button.dataset.cmd
                let active = false

                try {
                    if (command === 'formatBlock') {
                        active = blockOf() === button.dataset.val
                    } else if (STATE_COMMANDS.includes(command)) {
                        active = document.queryCommandState(command)
                    }
                } catch (e) {
                    active = false
                }

                button.classList.toggle('on', active)
                button.setAttribute('aria-pressed', active ? 'true' : 'false')
            })
        }

        // Pertahankan seleksi ketika toolbar diklik.
        bar.addEventListener('mousedown', (event) => {
            if (event.target.closest('.rb')) {
                event.preventDefault()
            }
        })

        bar.addEventListener('click', (event) => {
            const button = event.target.closest('.rb')

            if (!button) return

            const command = button.dataset.cmd
            const value = button.dataset.val

            area.focus()

            if (command === 'formatBlock') {
                const tag = blockOf() === value ? 'p' : value

                document.execCommand('formatBlock', false, '<' + tag + '>')
            } else if (command === 'createLink') {
                insertLink()
            } else if (command === 'removeFormat') {
                document.execCommand('removeFormat')
                document.execCommand('unlink')
            } else {
                document.execCommand(command, false, null)
            }

            refresh()
            sync()
            paint()
        })

        document.addEventListener('selectionchange', paint)

        // Tampilkan editor setelah berhasil diinisialisasi.
        wrap.hidden = false
        ta.hidden = true
        ta.style.display = 'none'

        refresh()
        sync()
        paint()
    }

    /* =====================================================
       UPLOAD POSTER / GAMBAR
       ===================================================== */

    const MAX = 2 * 1024 * 1024
    const OK_TYPES = ['image/png', 'image/jpeg', 'image/webp']
    const OK_EXT = /\.(png|jpe?g|webp)$/i

    const initDropzone = () => {
        $$('[data-dz]').forEach((dz) => {
            const input = $('input[type="file"]', dz)
            const img = $('[data-dz-thumb] img', dz)
            const ico = $('.dz-ico', dz)
            const okIco = $('.dz-ok', dz) || null
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

            const coverState = (enabled, label) => {
                dz.dataset.state = enabled ? '1' : '0'
                if (label) dz.dataset.stateLabel = label
            }

            const showIcon = (ok) => {
                img.hidden = true
                img.removeAttribute('src')
                ico.hidden = !!ok
                if (okIco) okIco.hidden = !ok
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

    /* =====================================================
       INISIALISASI
       ===================================================== */

    const boot = () => {
        initSlug()
        initFasilitasEditor()
        initDropzone()

        document.documentElement.setAttribute('data-fasilitas-js', 'ok')
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', boot, {
            once: true
        })
    } else {
        boot()
    }
})()
