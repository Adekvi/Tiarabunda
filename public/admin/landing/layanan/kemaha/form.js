;(function () {
    const form = document.getElementById('kmhForm')
    if (!form) return

    const fields = {
        judhul: form.querySelector('#judhul'),
        isi: form.querySelector('#isi'),
        kontak: form.querySelector('#kontak'),
        email: form.querySelector('#email'),
        lokasi: form.querySelector('#lokasi'),
        url_portal: form.querySelector('#url_portal')
    }

    const checklist = document.querySelectorAll('#kmhChecklist li')
    const progressBar = document.getElementById('kmhProgressBar')
    const progressText = document.getElementById('kmhProgressText')
    const pubRow = document.getElementById('kmhPubRow')
    const pubLabel = document.getElementById('kmhPubLabel')
    const pubDesc = document.getElementById('kmhPubDesc')
    const urip = form.querySelector('#urip')

    const previewMap = {
        kontak: document.getElementById('kmhPreviewKontak'),
        email: document.getElementById('kmhPreviewEmail'),
        lokasi: document.getElementById('kmhPreviewLokasi'),
        url_portal: document.getElementById('kmhPreviewUrl')
    }

    function setPreview(el, val) {
        if (!el) return
        const filled = val && val.trim().length > 0
        el.textContent = filled ? val.trim() : '—'
        el.classList.toggle('is-filled', filled)
    }

    function update() {
        let done = 0
        checklist.forEach((li) => {
            const key = li.dataset.check
            const el = fields[key]
            const filled = el && el.value.trim().length > 0
            li.classList.toggle('done', filled)
            if (filled) done++
        })

        const pct = Math.round((done / checklist.length) * 100)
        if (progressBar) progressBar.style.width = pct + '%'
        if (progressText) progressText.textContent = pct + '%'

        Object.entries(previewMap).forEach(([key, el]) => {
            setPreview(el, fields[key]?.value || '')
        })

        if (pubRow && urip) {
            const on = urip.checked
            pubRow.classList.toggle('is-off', !on)
            pubLabel.textContent = on ? 'Tampil di publik' : 'Disembunyikan'
            pubDesc.textContent = on
                ? 'Konten akan muncul di landing page kemahasiswaan.'
                : 'Konten tersimpan namun tidak tampil di landing page.'
        }
    }

    Object.values(fields).forEach((el) => {
        if (el) el.addEventListener('input', update)
    })
    if (urip) urip.addEventListener('change', update)

    update()
})()
