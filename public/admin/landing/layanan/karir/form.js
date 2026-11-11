;(function () {
    'use strict'

    /* ============================================================
       LIVE PREVIEW FORM
       Hanya aktif jika elemen preview & input ada (halaman form).
       ============================================================ */

    const titleInput = document.getElementById('judhul')
    const descInput = document.getElementById('isi')
    const orderInput = document.getElementById('urutan')
    const uripInput = document.getElementById('urip')

    const previewTitle = document.getElementById('krrPreviewTitle')
    const previewDesc = document.getElementById('krrPreviewDesc')
    const previewOrder = document.getElementById('krrPreviewOrder')
    const previewStatus = document.getElementById('krrPreviewStatus')

    const hasPreview =
        previewTitle || previewDesc || previewOrder || previewStatus
    if (!hasPreview) return

    function truncate(str, max) {
        if (!str) return ''
        return str.length > max ? str.slice(0, max) + '…' : str
    }

    function updatePreview() {
        // Judul
        if (previewTitle && titleInput) {
            previewTitle.textContent = titleInput.value.trim() || 'Judul konten'
        }

        // Deskripsi
        if (previewDesc && descInput) {
            const raw =
                descInput.value.trim() || 'Deskripsi akan tampil di sini.'
            previewDesc.textContent = truncate(raw, 90)
        }

        // Urutan
        if (previewOrder && orderInput) {
            previewOrder.textContent = 'Urutan: ' + (orderInput.value || '0')
        }

        // Status aktif/nonaktif
        if (previewStatus && uripInput) {
            const on = uripInput.checked
            previewStatus.classList.toggle('is-on', on)
            previewStatus.classList.toggle('is-off', !on)
            previewStatus.innerHTML = '<i></i> ' + (on ? 'Aktif' : 'Nonaktif')
        }
    }

    ;[titleInput, descInput, orderInput, uripInput].forEach(function (el) {
        if (!el) return
        el.addEventListener('input', updatePreview)
        el.addEventListener('change', updatePreview)
    })

    // Sinkronisasi awal (mis. saat validasi gagal → form refill)
    updatePreview()

    /* ============================================================
       Auto-resize textarea deskripsi
       ============================================================ */
    if (descInput && descInput.tagName === 'TEXTAREA') {
        const autoGrow = function () {
            descInput.style.height = 'auto'
            descInput.style.height =
                Math.min(descInput.scrollHeight, 400) + 'px'
        }
        descInput.addEventListener('input', autoGrow)
        // Tunggu frame berikutnya supaya scrollHeight akurat setelah layout
        requestAnimationFrame(autoGrow)
    }
})()
