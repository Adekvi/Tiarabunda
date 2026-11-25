(function () {
    const judhul = document.getElementById("judhul");
    const tandha = document.getElementById("tandha");
    const kategori = document.getElementById("kategori");
    const isi = document.getElementById("isi");
    const kodeDok = document.getElementById("meta_kode");
    const peringkat = document.getElementById("meta_peringkat");
    const tahun = document.getElementById("meta_tahun");

    const prevJudhul = document.getElementById("prevJudhul");
    const prevKategori = document.getElementById("prevKategori");
    const prevIsi = document.getElementById("prevIsi");
    const prevMeta = document.getElementById("prevMeta");

    if (!judhul || !tandha) return;

    let manualSlug = tandha.value.trim().length > 0;
    tandha.addEventListener("input", function () {
        manualSlug = tandha.value.trim().length > 0;
    });

    judhul.addEventListener("input", function () {
        const val = judhul.value.trim();
        if (prevJudhul) {
            prevJudhul.textContent = val || "Judul Dokumen / Program LPM";
        }
        if (!manualSlug) {
            tandha.value = val
                .toLowerCase()
                .replace(/[^a-z0-9\s-]/g, "")
                .replace(/\s+/g, "-")
                .replace(/-+/g, "-");
        }
    });

    if (kategori && prevKategori) {
        kategori.addEventListener("change", function () {
            const opt = kategori.options[kategori.selectedIndex];
            prevKategori.textContent = opt ? opt.text.trim() : "LPM";
            prevKategori.className = "lpm-badge " + kategori.value;
        });
    }

    if (isi && prevIsi) {
        isi.addEventListener("input", function () {
            const clean = isi.value.replace(/<[^>]*>/g, "").trim();
            prevIsi.textContent = clean
                ? clean.length > 120
                    ? clean.slice(0, 120) + "..."
                    : clean
                : "Ringkasan dokumen mutu, siklus PPEPP, atau informasi akreditasi akan tampil di sini.";
        });
    }

    function syncMeta() {
        if (!prevMeta) return;
        const parts = [
            kodeDok?.value.trim(),
            peringkat?.value.trim(),
            tahun?.value.trim(),
        ].filter(Boolean);
        prevMeta.textContent = parts.length
            ? parts.join(" • ")
            : "Kode Dokumen / Peringkat • Tahun";
    }

    kodeDok?.addEventListener("input", syncMeta);
    peringkat?.addEventListener("input", syncMeta);
    tahun?.addEventListener("input", syncMeta);
})();
