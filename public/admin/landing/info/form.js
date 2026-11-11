document.addEventListener('DOMContentLoaded', () => {
    /* ===== PREVIEW GAMBAR ===== */
    window.previewInfoImage = function (event) {
        const file = event.target.files?.[0]
        const drop = document.getElementById('file-drop')
        const filename = document.getElementById('fd-filename')
        if (!file) return

        if (filename) filename.textContent = file.name
        if (drop) drop.classList.add('has-file')

        const reader = new FileReader()
        reader.onload = function () {
            const output = document.getElementById('info-img-preview')
            const placeholder = document.getElementById('info-img-placeholder')
            output.src = reader.result
            output.style.display = 'block'
            if (placeholder) placeholder.style.display = 'none'
        }
        reader.readAsDataURL(file)
    }

    /* ===== DRAG & DROP ===== */
    const fileDrop = document.getElementById('file-drop')
    if (fileDrop) {
        ;['dragenter', 'dragover'].forEach((evt) => {
            fileDrop.addEventListener(evt, (e) => {
                e.preventDefault()
                e.stopPropagation()
                fileDrop.classList.add('dragover')
            })
        })
        ;['dragleave', 'drop'].forEach((evt) => {
            fileDrop.addEventListener(evt, (e) => {
                e.preventDefault()
                e.stopPropagation()
                fileDrop.classList.remove('dragover')
            })
        })
        fileDrop.addEventListener('drop', (e) => {
            const files = e.dataTransfer.files
            if (files.length) {
                const input = document.getElementById('img')
                input.files = files
                input.dispatchEvent(new Event('change', { bubbles: true }))
            }
        })
    }

    /* ===== STATUS PUBLIKASI ===== */
    const cb = document.getElementById('urip-checkbox')
    const toggle = document.getElementById('publish-toggle')
    const badge = document.getElementById('publish-badge')
    const desc = document.getElementById('publish-desc')
    const note = document.getElementById('publish-note')
    const noteText = document.getElementById('publish-note-text')
    const summaryStatus = document.getElementById('summary-status')

    function syncStatus() {
        if (!cb) return
        const on = cb.checked

        toggle?.classList.toggle('on', on)
        note?.classList.toggle('on', on)

        if (badge) {
            badge.textContent = on ? 'Aktif' : 'Draf'
            badge.classList.toggle('is-on', on)
        }

        if (desc) {
            desc.textContent = on
                ? 'Info akan langsung tampil di slider publik beranda.'
                : 'Info disimpan sebagai draf — tidak terlihat oleh pengunjung.'
        }

        if (noteText) {
            noteText.innerHTML = on
                ? 'Setelah disimpan, info ini <strong>langsung tayang</strong> di slider beranda. Pastikan poster & judul sudah final sebelum menyimpan.'
                : 'Info akan disimpan sebagai <strong>draf</strong>. Anda dapat mengaktifkannya kapan saja dari halaman daftar Info Terkini.'
        }

        if (summaryStatus) {
            summaryStatus.textContent = on
                ? 'Akan ditampilkan'
                : 'Disimpan sebagai draf'
        }
    }

    if (cb) {
        cb.addEventListener('change', syncStatus)
        syncStatus()
    }

    /* ===== MODE TANGGAL ===== */
    const dateSwitch = document.getElementById('date-mode-switch')
    const panels = document.querySelectorAll('.date-inputs .mode-panel')
    const tglPicker = document.getElementById('tgl-picker')
    const tglText = document.getElementById('tgl-text')
    const tglFinal = document.getElementById('tgl-final')

    let currentMode =
        document.querySelector('.mode-panel.active')?.dataset.panel || 'picker'

    function updateFinalDate() {
        if (!tglFinal) return
        if (currentMode === 'picker') {
            tglFinal.value = tglPicker?.value || ''
        } else {
            tglFinal.value = tglText?.value || ''
        }
    }

    function setMode(mode) {
        currentMode = mode
        dateSwitch.querySelectorAll('button').forEach((btn) => {
            btn.classList.toggle('active', btn.dataset.mode === mode)
        })
        panels.forEach((p) =>
            p.classList.toggle('active', p.dataset.panel === mode)
        )
        updateFinalDate()
    }

    if (dateSwitch) {
        dateSwitch.querySelectorAll('button').forEach((btn) => {
            btn.addEventListener('click', () => setMode(btn.dataset.mode))
        })
    }
    if (tglPicker) tglPicker.addEventListener('change', updateFinalDate)
    if (tglText) tglText.addEventListener('input', updateFinalDate)

    updateFinalDate()
    document.querySelector('form')?.addEventListener('submit', updateFinalDate)
})
