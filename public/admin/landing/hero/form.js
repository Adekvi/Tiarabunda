document.addEventListener('DOMContentLoaded', function () {
    const $ = (id) => document.getElementById(id)

    // Batas ukuran file (samakan dengan aturan `max:` di controller)
    const MAX_IMAGE_MB = 2
    const MAX_VIDEO_MB = 15
    const MAX_POSTER_MB = 2
    const VIDEO_LOAD_TIMEOUT = 5000 // ms

    const radios = document.querySelectorAll('.tipe-media')
    const uploadSection = $('upload_section')
    const posterSection = $('poster_section')
    const options = document.querySelectorAll('.media-option')

    const dropzone = $('dropzone')
    const inputMedia = $('input_media')
    const dropzoneEmpty = $('dropzone_empty')
    const dropzonePreview = $('dropzone_preview')
    const dropzoneLoading = $('dropzone_loading')
    const previewWrap = $('preview_wrap')
    const previewImg = $('preview_img')
    const previewVideo = $('preview_video')
    const previewName = $('preview_name')
    const previewSize = $('preview_size')
    const btnRemoveMedia = $('btn_remove_media')
    const dropzoneHint = $('dropzone_hint')
    const currentMedia = $('current_media')

    const posterDropzone = $('poster_dropzone')
    const inputPoster = $('input_poster')
    const posterEmpty = $('poster_empty')
    const posterPreview = $('poster_preview')
    const posterImg = $('poster_img')
    const posterName = $('poster_name')
    const btnRemovePoster = $('btn_remove_poster')
    const currentPoster = $('current_poster')

    const uripToggle = $('urip_toggle')
    const publishCard = $('publish_card')
    const publishIcon = $('publish_icon')
    const publishTitle = $('publish_title')
    const publishDesc = $('publish_desc')
    const publishLabel = $('publish_label')

    let mediaObjectURL = null
    let mediaToken = 0
    let posterToken = 0
    let lastMediaKey = null
    let lastPosterKey = null
    let videoTimer = null

    // ---------- helpers ----------
    const fileKey = (f) => `${f.name}|${f.size}|${f.lastModified}`

    function getSelectedTipe() {
        return document.querySelector('.tipe-media:checked')?.value || 'none'
    }

    function formatSize(bytes) {
        if (!bytes) return ''
        if (bytes < 1024) return bytes + ' B'
        if (bytes < 1048576) return (bytes / 1024).toFixed(1) + ' KB'
        return (bytes / 1048576).toFixed(2) + ' MB'
    }

    function revokeMediaURL() {
        if (mediaObjectURL) {
            URL.revokeObjectURL(mediaObjectURL)
            mediaObjectURL = null
        }
    }

    /** Deteksi tipe file lebih aman (MIME bisa kosong di beberapa browser) */
    function detectMediaKind(file) {
        if (!file) return null
        const mime = (file.type || '').toLowerCase()
        const name = (file.name || '').toLowerCase()

        if (
            mime.startsWith('image/') ||
            /\.(jpe?g|png|webp|gif|avif)$/i.test(name)
        ) {
            return 'image'
        }
        if (
            mime.startsWith('video/') ||
            /\.(mp4|webm|mov|m4v|ogg|ogv)$/i.test(name)
        ) {
            return 'video'
        }
        return null
    }

    /** Baca file sebagai data URL (tidak bergantung pada blob:) */
    function readAsDataURL(file) {
        return new Promise(function (resolve, reject) {
            const reader = new FileReader()
            reader.onload = () => resolve(reader.result)
            reader.onerror = () => reject(reader.error || new Error('read'))
            reader.readAsDataURL(file)
        })
    }

    /** Set file ke <input type="file"> dengan DataTransfer (FileList read-only).
     *  Dilewati jika input sudah berisi file yang sama. */
    function setInputFile(input, file) {
        if (!input || !file) return
        if (
            input.files &&
            input.files[0] &&
            fileKey(input.files[0]) === fileKey(file)
        ) {
            return
        }
        try {
            const dt = new DataTransfer()
            dt.items.add(file)
            input.files = dt.files
        } catch (err) {
            console.warn('Gagal set files ke input:', err)
        }
    }

    // ---------- Kartu pengganti jika preview tidak bisa ditampilkan ----------
    function getFallback() {
        let el = $('preview_fallback')
        if (!el && previewWrap) {
            el = document.createElement('div')
            el.id = 'preview_fallback'
            el.style.cssText =
                'display:none;flex-direction:column;align-items:center;justify-content:center;gap:8px;' +
                'width:100%;max-width:300px;min-height:140px;margin:0 auto;padding:18px;' +
                'border-radius:10px;border:1px solid var(--line);background:rgba(99,102,241,0.06);'
            el.innerHTML =
                '<svg width="34" height="34" viewBox="0 0 24 24" fill="none" stroke="var(--pri)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="23 7 16 12 23 17 23 7"></polygon><rect x="1" y="5" width="15" height="14" rx="2" ry="2"></rect></svg>' +
                '<div class="fb-msg" style="font-size:0.78rem;color:var(--mut);line-height:1.5;"></div>'
            previewWrap.appendChild(el)
        }
        return el
    }

    function showFallback(msg) {
        const el = getFallback()
        if (!el) return
        el.querySelector('.fb-msg').textContent = msg
        el.style.display = 'flex'
    }

    function hideFallback() {
        const el = $('preview_fallback')
        if (el) el.style.display = 'none'
    }

    // ---------- UI state ----------
    function paintOptions() {
        const selected = getSelectedTipe()
        options.forEach(function (el) {
            const isActive = el.dataset.value === selected
            el.style.borderColor = isActive ? 'var(--pri)' : 'var(--line)'
            el.style.background = isActive
                ? 'rgba(99, 102, 241, 0.06)'
                : 'var(--bg)'
            el.style.boxShadow = isActive
                ? '0 0 0 3px rgba(99, 102, 241, 0.12)'
                : 'none'

            const iconWrap = el.querySelector('.media-icon')
            const iconSvg = el.querySelector('.media-icon svg')
            if (iconWrap) {
                iconWrap.style.background = isActive
                    ? 'rgba(99,102,241,0.15)'
                    : 'rgba(99,102,241,0.08)'
            }
            if (iconSvg) {
                iconSvg.setAttribute(
                    'stroke',
                    isActive ? 'var(--pri)' : 'var(--mut)'
                )
            }
        })
    }

    function updateMediaUI() {
        const selected = getSelectedTipe()

        if (selected === 'video') {
            uploadSection.style.display = 'block'
            posterSection.style.display = 'block'
            if (inputMedia) {
                inputMedia.setAttribute(
                    'accept',
                    'video/mp4,video/webm,video/quicktime,.mp4,.webm,.mov'
                )
            }
            if (dropzoneHint) {
                dropzoneHint.textContent = `Format: MP4 atau WebM. Maks ${MAX_VIDEO_MB}MB.`
            }
        } else if (selected === 'gambar') {
            uploadSection.style.display = 'block'
            posterSection.style.display = 'none'
            if (inputMedia) {
                inputMedia.setAttribute(
                    'accept',
                    'image/jpeg,image/png,image/webp,.jpg,.jpeg,.png,.webp'
                )
            }
            if (dropzoneHint) {
                dropzoneHint.textContent = `Format: JPG, PNG, WEBP. Maks ${MAX_IMAGE_MB}MB.`
            }
            resetPosterPreview()
        } else {
            uploadSection.style.display = 'none'
            posterSection.style.display = 'none'
            resetMediaPreview()
            resetPosterPreview()
        }
        paintOptions()
    }

    // ---------- Preview media (gambar / video) ----------
    function clearVideoState() {
        clearTimeout(videoTimer)
        videoTimer = null
        if (previewVideo) {
            previewVideo.onloadedmetadata = null
            previewVideo.onloadeddata = null
            previewVideo.onerror = null
        }
    }

    function showMediaPreview(file) {
        if (!file) return

        // Penjaga duplikasi: file sama & preview sudah aktif -> abaikan
        const key = fileKey(file)
        if (
            key === lastMediaKey &&
            dropzonePreview &&
            !dropzonePreview.classList.contains('hidden-preview')
        ) {
            return
        }

        const kind = detectMediaKind(file)
        if (!kind) {
            alert(
                'Format file tidak didukung. Gunakan gambar (JPG/PNG/WEBP) atau video (MP4/WebM).'
            )
            if (inputMedia) inputMedia.value = ''
            return
        }

        // Jenis file harus sesuai tipe media yang dipilih
        const selected = getSelectedTipe()
        if (
            (selected === 'gambar' && kind !== 'image') ||
            (selected === 'video' && kind !== 'video')
        ) {
            alert(
                selected === 'gambar'
                    ? 'Tipe media "Gambar" dipilih, tetapi file bukan gambar.'
                    : 'Tipe media "Video" dipilih, tetapi file bukan video.'
            )
            if (inputMedia) inputMedia.value = ''
            return
        }

        // Cek ukuran
        const maxMB = kind === 'image' ? MAX_IMAGE_MB : MAX_VIDEO_MB
        if (file.size > maxMB * 1024 * 1024) {
            alert(
                `Ukuran file ${formatSize(file.size)} melebihi batas ${maxMB}MB.`
            )
            if (inputMedia) inputMedia.value = ''
            return
        }

        lastMediaKey = key
        const token = ++mediaToken

        // Pastikan file terpasang di input (penting untuk drag-drop)
        setInputFile(inputMedia, file)

        dropzoneEmpty.style.display = 'none'
        dropzonePreview.classList.remove('hidden-preview')
        if (dropzoneLoading) dropzoneLoading.style.display = 'none'

        previewName.textContent = file.name
        previewSize.textContent = formatSize(file.size)

        // Bersihkan state sebelumnya
        clearVideoState()
        hideFallback()
        revokeMediaURL()
        previewImg.removeAttribute('src')
        previewImg.style.display = 'none'
        previewVideo.pause()
        previewVideo.removeAttribute('src')
        previewVideo.style.display = 'none'

        if (currentMedia) currentMedia.style.display = 'none'

        if (kind === 'image') {
            renderImagePreview(file, token)
        } else {
            renderVideoPreview(file, token)
        }
    }

    function renderImagePreview(file, token) {
        function fail() {
            if (token !== mediaToken) return
            previewImg.removeAttribute('src')
            previewImg.style.display = 'none'
            showFallback(
                'Preview gambar tidak bisa ditampilkan, tetapi file tetap akan diupload.'
            )
        }

        readAsDataURL(file)
            .then(function (dataUrl) {
                if (token !== mediaToken) return
                previewImg.onerror = function () {
                    // Fallback ke blob URL
                    previewImg.onerror = fail
                    mediaObjectURL = URL.createObjectURL(file)
                    previewImg.src = mediaObjectURL
                }
                previewImg.src = dataUrl
                previewImg.style.display = 'inline-block'
            })
            .catch(function (err) {
                console.warn('FileReader gagal:', err)
                if (token !== mediaToken) return
                previewImg.onerror = fail
                mediaObjectURL = URL.createObjectURL(file)
                previewImg.src = mediaObjectURL
                previewImg.style.display = 'inline-block'
            })
    }

    function renderVideoPreview(file, token) {
        function fail() {
            if (token !== mediaToken) return
            clearVideoState()
            previewVideo.pause()
            previewVideo.removeAttribute('src')
            previewVideo.style.display = 'none'
            showFallback(
                'Preview video tidak bisa ditampilkan di browser ini (kemungkinan codec tidak didukung). ' +
                    'File tetap akan diupload.'
            )
            console.warn('Preview video gagal:', file.name, file.type)
        }

        function ok() {
            if (token !== mediaToken) return
            clearTimeout(videoTimer)
            videoTimer = null
            try {
                previewVideo.currentTime = 0.1 // tampilkan frame awal
            } catch (e) {}
        }

        mediaObjectURL = URL.createObjectURL(file)
        previewVideo.onloadedmetadata = ok
        previewVideo.onloadeddata = ok
        previewVideo.onerror = fail
        previewVideo.src = mediaObjectURL
        previewVideo.style.display = 'inline-block'
        previewVideo.load()

        // Jika tidak ada metadata sama sekali setelah beberapa detik -> fallback
        videoTimer = setTimeout(function () {
            if (token === mediaToken && previewVideo.readyState === 0) fail()
        }, VIDEO_LOAD_TIMEOUT)
    }

    function resetMediaPreview() {
        mediaToken++
        if (inputMedia) inputMedia.value = ''
        clearVideoState()
        hideFallback()
        revokeMediaURL()
        lastMediaKey = null

        if (dropzoneEmpty) dropzoneEmpty.style.display = 'block'
        if (dropzonePreview) dropzonePreview.classList.add('hidden-preview')
        if (dropzoneLoading) dropzoneLoading.style.display = 'none'

        if (previewImg) {
            previewImg.onerror = null
            previewImg.removeAttribute('src')
            previewImg.style.display = 'none'
        }
        if (previewVideo) {
            previewVideo.pause()
            previewVideo.removeAttribute('src')
            previewVideo.style.display = 'none'
        }

        if (currentMedia) currentMedia.style.display = 'flex'
    }

    // ---------- Preview poster ----------
    function showPosterPreview(file) {
        if (!file) return

        const key = fileKey(file)
        if (
            key === lastPosterKey &&
            posterPreview &&
            !posterPreview.classList.contains('hidden-preview')
        ) {
            return
        }

        const kind = detectMediaKind(file)
        if (kind !== 'image') {
            alert('Poster harus berupa gambar (JPG, PNG, WEBP).')
            if (inputPoster) inputPoster.value = ''
            return
        }

        if (file.size > MAX_POSTER_MB * 1024 * 1024) {
            alert(
                `Ukuran poster ${formatSize(file.size)} melebihi batas ${MAX_POSTER_MB}MB.`
            )
            if (inputPoster) inputPoster.value = ''
            return
        }

        lastPosterKey = key
        const token = ++posterToken

        setInputFile(inputPoster, file)

        if (posterEmpty) posterEmpty.style.display = 'none'
        if (posterPreview) posterPreview.classList.remove('hidden-preview')
        if (posterName) posterName.textContent = file.name
        if (currentPoster) currentPoster.style.display = 'none'

        if (posterImg) {
            posterImg.removeAttribute('src')
            posterImg.style.display = 'none'
        }

        readAsDataURL(file)
            .then(function (dataUrl) {
                if (token !== posterToken || !posterImg) return
                posterImg.onerror = function () {
                    if (token !== posterToken) return
                    posterImg.removeAttribute('src')
                    posterImg.style.display = 'none'
                    if (posterName) {
                        posterName.textContent =
                            file.name + ' (preview tidak tersedia)'
                    }
                }
                posterImg.src = dataUrl
                posterImg.style.display = 'inline-block'
            })
            .catch(function (err) {
                console.warn('FileReader poster gagal:', err)
                if (posterName) {
                    posterName.textContent =
                        file.name + ' (preview tidak tersedia)'
                }
            })
    }

    function resetPosterPreview() {
        posterToken++
        if (inputPoster) inputPoster.value = ''
        lastPosterKey = null

        if (posterEmpty) posterEmpty.style.display = 'flex'
        if (posterPreview) posterPreview.classList.add('hidden-preview')

        if (posterImg) {
            posterImg.onerror = null
            posterImg.removeAttribute('src')
            posterImg.style.display = 'none'
        }
        if (currentPoster) currentPoster.style.display = 'flex'
    }

    // ---------- Publish toggle UI ----------
    function updatePublishUI() {
        const active = uripToggle?.checked
        if (!publishCard) return

        if (active) {
            publishCard.style.borderColor = 'rgba(34,197,94,0.5)'
            publishCard.style.background = 'rgba(34,197,94,0.04)'
            publishIcon.style.background = 'rgba(34,197,94,0.15)'
            publishIcon.innerHTML =
                '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="rgb(34,197,94)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>'
            publishTitle.textContent = 'Tampil di landing page'
            publishTitle.style.color = 'rgb(21,128,61)'
            publishDesc.textContent = 'Hero akan tampil di situs publik.'
            publishLabel.textContent = 'Aktif'
            publishLabel.style.color = 'rgb(34,197,94)'
        } else {
            publishCard.style.borderColor = 'var(--line)'
            publishCard.style.background = 'var(--bg)'
            publishIcon.style.background = 'rgba(148,163,184,0.12)'
            publishIcon.innerHTML =
                '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="rgb(148,163,184)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line></svg>'
            publishTitle.textContent = 'Tidak tampil di landing page'
            publishTitle.style.color = 'var(--fg)'
            publishDesc.textContent =
                'Tersimpan, tapi tidak terlihat oleh pengunjung.'
            publishLabel.textContent = 'Nonaktif'
            publishLabel.style.color = 'var(--mut)'
        }
    }

    // ---------- Events ----------
    radios.forEach((r) => r.addEventListener('change', updateMediaUI))

    if (dropzone && inputMedia) {
        dropzone.addEventListener('click', (e) => {
            // Jangan buka file dialog saat klik tombol Hapus atau kontrol video
            if (e.target.closest('#btn_remove_media')) return
            if (e.target.closest('#preview_video')) return
            inputMedia.click()
        })
        inputMedia.addEventListener('change', (e) => {
            const file = e.target.files?.[0]
            if (file) showMediaPreview(file)
        })
    }

    if (btnRemoveMedia) {
        btnRemoveMedia.addEventListener('click', function (e) {
            e.preventDefault()
            e.stopPropagation()
            resetMediaPreview()
        })
    }

    if (posterDropzone && inputPoster) {
        posterDropzone.addEventListener('click', (e) => {
            if (e.target.closest('#btn_remove_poster')) return
            inputPoster.click()
        })
        inputPoster.addEventListener('change', (e) => {
            const file = e.target.files?.[0]
            if (file) showPosterPreview(file)
        })
    }

    if (btnRemovePoster) {
        btnRemovePoster.addEventListener('click', function (e) {
            e.preventDefault()
            e.stopPropagation()
            resetPosterPreview()
        })
    }

    // Drag & drop
    ;[dropzone, posterDropzone].forEach(function (dz) {
        if (!dz) return
        ;['dragenter', 'dragover'].forEach((ev) =>
            dz.addEventListener(ev, (e) => {
                e.preventDefault()
                e.stopPropagation()
                dz.classList.add('dragover')
            })
        )
        ;['dragleave', 'drop'].forEach((ev) =>
            dz.addEventListener(ev, (e) => {
                e.preventDefault()
                e.stopPropagation()
                dz.classList.remove('dragover')
            })
        )

        dz.addEventListener('drop', function (e) {
            const files = e.dataTransfer?.files
            if (!files?.length) return

            const file = files[0]
            if (dz === dropzone) {
                showMediaPreview(file)
            } else {
                showPosterPreview(file)
            }
        })
    })

    if (uripToggle) {
        uripToggle.addEventListener('change', updatePublishUI)
    }

    // ---------- Init ----------
    updateMediaUI()
    updatePublishUI()
})
