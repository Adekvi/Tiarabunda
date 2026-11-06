document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('akrForm')
    if (!form) return

    /* =========================================================
     * 1. Logika Dropzone (Upload Gambar)
     * ======================================================= */
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
        const def = { title: title.textContent, meta: meta.textContent }
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
            if (window.updateProgress) window.updateProgress()
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

            if (window.updateProgress) window.updateProgress()
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

    /* =========================================================
     * 2. Logika Form (Jenis Akreditasi & Checklist)
     * ======================================================= */

    // Hapus deklarasi `const form` ganda di sini
    const bagean = document.getElementById('bagean')
    const wrapProdi = document.getElementById('wrap-prodi')
    const prodiIds = document.getElementById('prodi_ids')
    const nama = document.getElementById('jeneng')
    const statusAkred = document.getElementById('status_akred')
    const urip = document.getElementById('urip')
    const fotoInput = document.getElementById('path_gambar')

    const progressBar = document.getElementById('akrProgressBar')
    const progressText = document.getElementById('akrProgressText')
    const checklist = document.querySelectorAll('#akrChecklist li')

    const pubRow = document.querySelector('.akr-pub-row')
    const pubLabel = document.getElementById('pubLabel')
    const pubDesc = document.getElementById('pubDesc')

    // Toggle Dropdown Prodi
    const toggleBagean = () => {
        if (bagean.value === 'institusi') {
            wrapProdi.style.display = 'none'
        } else {
            wrapProdi.style.display = 'block'
        }
        window.updateProgress() // Panggil via window karena dideklarasikan di scope window
    }
    bagean?.addEventListener('change', toggleBagean)

    // Cek Kelengkapan
    const fields = {
        jeneng: () => (nama?.value || '').trim() !== '',
        status_akred: () => (statusAkred?.value || '').trim() !== '',

        // PERBAIKAN: Cek apakah ada checkbox prodi yang dicentang
        prodi_ids: () => {
            if (bagean.value === 'institusi') return true // Opsional jika institusi
            const checked = document.querySelectorAll(
                'input[name="prodi_ids[]"]:checked'
            )
            return checked.length > 0
        },

        path_gambar: () => {
            const dz = document.querySelector('[data-dz]')
            return (
                (dz && dz.dataset.initialSrc !== '') ||
                (fotoInput.files && fotoInput.files.length > 0)
            )
        }
    }

    window.updateProgress = () => {
        if (!checklist.length) return
        let done = 0
        checklist.forEach((li) => {
            // Gunakan dataset.check untuk mencocokkan dengan kunci di objek fields
            const ok = fields[li.dataset.check]?.() || false
            li.classList.toggle('done', ok)
            if (ok) done++
        })
        const pct = Math.round((done / checklist.length) * 100)
        if (progressBar) progressBar.style.width = pct + '%'
        if (progressText) progressText.textContent = pct
    }

    // Status Publikasi
    const updatePub = () => {
        if (!pubRow || !urip) return
        const on = urip.checked
        pubRow.classList.toggle('is-off', !on)
        if (pubLabel) pubLabel.textContent = on ? 'Aktif' : 'Nonaktif'
        if (pubDesc) {
            pubDesc.textContent = on
                ? 'Sertifikat akan tampil di halaman publik.'
                : 'Sertifikat tersimpan, tetapi disembunyikan dari publik.'
        }
    }

    // Listeners untuk update progress otomatis
    form.querySelectorAll('input, select, textarea').forEach((el) => {
        el.addEventListener('input', window.updateProgress)
        el.addEventListener('change', window.updateProgress)
    })

    urip?.addEventListener('change', updatePub)

    // Init awal saat halaman dimuat
    toggleBagean()
    updatePub()
})
