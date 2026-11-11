document.addEventListener('DOMContentLoaded', () => {
    /* ---------- Elemen ---------- */
    const form = document.getElementById('prodiForm')
    const nama = document.getElementById('jeneng')
    const slug = document.getElementById('tandha')
    const fakultas = document.getElementById('fakultas_id')
    const undhak = document.getElementById('undhak')
    const akred = document.getElementById('akred')
    const katrangan = document.getElementById('katrangan')
    const preview = document.getElementById('slugPreview')
    const progressBar = document.getElementById('progressBar')
    const progressText = document.getElementById('progressText')
    const fakultasCard = document.getElementById('fakultasCard')
    const fakNama = document.getElementById('fakNama')
    const checklist = document.querySelectorAll('#checklist li')
    const lockBtn = document.getElementById('tandhaLock')

    if (!form) return // guard: form tidak ada, hentikan

    /* ---------- Utilitas ---------- */
    const slugify = (str = '') =>
        String(str)
            .toLowerCase()
            .normalize('NFD')
            .replace(/[\u0300-\u036f]/g, '')
            .replace(/[^a-z0-9\s-]/g, '')
            .trim()
            .replace(/\s+/g, '-')
            .replace(/-+/g, '-')
            .replace(/^-+|-+$/g, '') // buang strip di ujung

    const updatePreview = () => {
        if (!preview) return
        preview.textContent = '/prodi/' + (slug?.value.trim() || '…')
    }

    /* ---------- Sinkron slug dari nama ---------- */
    const syncSlugFromName = () => {
        if (!slug) return
        if (slug.dataset.manual === '1') return
        slug.value = slugify(nama?.value || '')
        updatePreview()
    }

    /* ---------- Progress ---------- */
    const fields = {
        fakultas_id: () => (fakultas?.value || '') !== '',
        jeneng: () => (nama?.value || '').trim() !== '',
        undhak: () => (undhak?.value || '') !== '',
        akred: () => (akred?.value || '').trim() !== '',
        katrangan: () => (katrangan?.value || '').trim() !== ''
    }

    const updateProgress = () => {
        if (!checklist.length) return
        let done = 0
        checklist.forEach((li) => {
            const key = li.dataset.check
            const ok = fields[key] ? fields[key]() : false
            li.classList.toggle('done', ok)
            if (ok) done++
        })
        const pct = Math.round((done / checklist.length) * 100)
        if (progressBar) progressBar.style.width = pct + '%'
        if (progressText) progressText.textContent = pct
    }

    /* ---------- Init state manual/otomatis ---------- */
    if (slug) {
        const hasOld = slug.value.trim() !== ''
        const isEdit = slug.dataset.edit === '1' // opsional: set di blade utk mode edit

        if (isEdit && hasOld) {
            // mode edit & slug lama ada → anggap manual
            slug.dataset.manual = '1'
        } else if (hasOld) {
            // mode create tapi old() ada (validasi gagal) → pertahankan old
            slug.dataset.manual = '1'
        } else {
            // slug kosong → otomatis, isi dari nama
            slug.dataset.manual = '0'
            syncSlugFromName()
        }
    }

    /* ---------- Listener: nama → slug ---------- */
    let slugTimer = null
    nama?.addEventListener('input', () => {
        clearTimeout(slugTimer)
        slugTimer = setTimeout(() => {
            syncSlugFromName()
            updateProgress()
        }, 120)
    })

    /* ---------- Listener: toggle manual ---------- */
    if (lockBtn && slug) {
        lockBtn.addEventListener('click', () => {
            const isManual = slug.dataset.manual === '1'
            if (isManual) {
                // kembali otomatis
                slug.dataset.manual = '0'
                slug.readOnly = true
                lockBtn.textContent = 'ubah manual'
                syncSlugFromName()
            } else {
                // aktifkan manual
                slug.dataset.manual = '1'
                slug.readOnly = false
                lockBtn.textContent = 'kembali otomatis'
                slug.focus()
                slug.select()
            }
        })

        // Bersihkan input user saat mengetik manual
        slug.addEventListener('input', () => {
            slug.value = slugify(slug.value)
            updatePreview()
        })

        // Sanitasi akhir saat blur
        slug.addEventListener('blur', () => {
            slug.value = slugify(slug.value)
            updatePreview()
        })
    }

    /* ---------- Listener: fakultas ---------- */
    fakultas?.addEventListener('change', () => {
        const opt = fakultas.options[fakultas.selectedIndex]
        if (fakultas.value && opt) {
            if (fakultasCard) fakultasCard.hidden = false
            if (fakNama) fakNama.textContent = opt.textContent.trim()
        } else {
            if (fakultasCard) fakultasCard.hidden = true
            if (fakNama) fakNama.textContent = '—'
        }
        updateProgress()
    })

    /* ---------- Listener: semua field utk progress ---------- */
    form.querySelectorAll('input, select, textarea').forEach((el) => {
        el.addEventListener('input', updateProgress)
        el.addEventListener('change', updateProgress)
    })

    /* ---------- Guard: slug kosong saat submit ---------- */
    form.addEventListener('submit', () => {
        // fallback: kalau slug kosong, coba isi dari nama
        if (slug && slug.value.trim() === '') {
            slug.value = slugify(nama?.value || '')
        }
    })

    /* ---------- Sync fakultas di init ---------- */
    if (fakultas?.value) {
        if (fakultasCard) fakultasCard.hidden = false
        if (fakNama)
            fakNama.textContent =
                fakultas.options[fakultas.selectedIndex].textContent.trim()
    } else if (fakultasCard) {
        fakultasCard.hidden = true
    }

    /* ---------- Run awal ---------- */
    updatePreview()
    updateProgress()
})
;(() => {
    const wrappers = document.querySelectorAll('.cs-wrapper')

    wrappers.forEach((wrapper) => {
        const trigger = wrapper.querySelector('.cs-trigger')
        const valueDisplay = wrapper.querySelector('.cs-value')
        const searchInput = wrapper.querySelector('.cs-search')
        const optionsContainer = wrapper.querySelector('.cs-options')
        const options = wrapper.querySelectorAll('.cs-option')
        const emptyState = wrapper.querySelector('.cs-empty')
        const nativeSelect = wrapper.querySelector('select')

        // --- PERBAIKAN DI SINI ---
        // 1. Set tampilan awal berdasarkan nilai dari <select> asli
        const currentValue = nativeSelect.value
        if (currentValue) {
            const selectedOpt = Array.from(options).find(
                (opt) => opt.dataset.value === currentValue
            )
            if (selectedOpt) {
                valueDisplay.textContent = selectedOpt.textContent.trim()
                selectedOpt.dataset.selected = 'true' // Tandai sebagai terpilih
            }
        }
        // -------------------------

        // 2. Buka/Tutup Dropdown
        trigger.addEventListener('click', (e) => {
            // Cegah event bubbling agar tidak langsung tertutup oleh document click
            e.stopPropagation()

            wrapper.classList.toggle('is-open')
            if (wrapper.classList.contains('is-open')) {
                searchInput.value = '' // Reset pencarian
                filterOptions('') // Tampilkan semua
                setTimeout(() => searchInput.focus(), 50) // Fokus ke input search
            }
        })

        // Tutup jika klik di luar area
        document.addEventListener('click', (e) => {
            if (!wrapper.contains(e.target)) {
                wrapper.classList.remove('is-open')
            }
        })

        // 3. Fitur Pencarian
        searchInput.addEventListener('input', (e) => {
            const keyword = e.target.value.toLowerCase()
            filterOptions(keyword)
        })

        function filterOptions(keyword) {
            let hasVisible = false
            options.forEach((opt) => {
                const text = opt.textContent.toLowerCase()
                const searchData = (opt.dataset.search || text).toLowerCase()

                if (text.includes(keyword) || searchData.includes(keyword)) {
                    opt.style.display = 'block'
                    hasVisible = true
                } else {
                    opt.style.display = 'none'
                }
            })
            emptyState.style.display = hasVisible ? 'none' : 'block'
        }

        // 4. Pilih Opsi
        options.forEach((opt) => {
            opt.addEventListener('click', () => {
                const val = opt.dataset.value

                // Update tampilan visual
                valueDisplay.textContent = opt.textContent.trim()
                wrapper.classList.remove('is-open')

                // Hapus status terpilih sebelumnya
                options.forEach((o) => delete o.dataset.selected)
                opt.dataset.selected = 'true'

                // Sinkronisasi ke select asli
                nativeSelect.value = val

                // Trigger event change agar validasi/form lain mendeteksinya
                // (Penting: agar progress bar di form Prodi ikut update)
                nativeSelect.dispatchEvent(new Event('change'))
            })
        })

        // 5. Navigasi Keyboard Sederhana
        searchInput.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                wrapper.classList.remove('is-open')
                trigger.focus()
            }

            // Fitur Enter untuk memilih opsi pertama yang terlihat (opsional)
            if (e.key === 'Enter') {
                e.preventDefault()
                const visibleOptions = Array.from(options).filter(
                    (opt) => opt.style.display !== 'none'
                )
                if (visibleOptions.length > 0) {
                    visibleOptions[0].click()
                }
            }
        })
    })
})()
