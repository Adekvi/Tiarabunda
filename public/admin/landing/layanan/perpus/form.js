(function () {
    const form = document.querySelector('form[action*="perpus"]');
    if (!form) return;

    const fields = {
        judhul: form.querySelector('#judhul'),
        isi: form.querySelector('#isi'),
        url_portal: form.querySelector('#url_portal'),
        kontak: form.querySelector('#kontak'),
    };

    const checklist = document.querySelectorAll('#prpChecklist li');
    const progressBar = document.getElementById('prpProgressBar');
    const progressText = document.getElementById('prpProgressText');
    const portalUrl = document.getElementById('prpPortalUrl');
    const pubRow = document.getElementById('prpPubRow');
    const pubLabel = document.getElementById('prpPubLabel');
    const pubDesc = document.getElementById('prpPubDesc');
    const urip = form.querySelector('#urip');

    function update() {
        let done = 0;
        checklist.forEach(li => {
            const key = li.dataset.check;
            const el = fields[key];
            const filled = el && el.value.trim().length > 0;
            li.classList.toggle('done', filled);
            if (filled) done++;
        });

        const pct = Math.round((done / checklist.length) * 100);
        if (progressBar) progressBar.style.width = pct + '%';
        if (progressText) progressText.textContent = pct + '%';

        // Preview portal
        if (portalUrl) {
            const val = fields.url_portal?.value.trim();
            portalUrl.textContent = val || 'Belum diisi';
        }

        // Status publikasi
        if (pubRow && urip) {
            const on = urip.checked;
            pubRow.classList.toggle('is-off', !on);
            pubLabel.textContent = on ? 'Tampil di publik' : 'Disembunyikan';
            pubDesc.textContent = on
                ? 'Konten akan muncul di landing page perpustakaan.'
                : 'Konten tersimpan namun tidak tampil di landing page.';
        }
    }

    Object.values(fields).forEach(el => {
        if (el) el.addEventListener('input', update);
    });
    if (urip) urip.addEventListener('change', update);

    update();
})();