(function () {
    const judhul = document.getElementById("judhul");
    const tandha = document.getElementById("tandha");
    const kategori = document.getElementById("kategori");
    const isi = document.getElementById("isi");
    const pelaksana = document.getElementById("meta_pelaksana");
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
        if (prevJudhul)
            prevJudhul.textContent = val || "Judul Program / Layanan LPPM";
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
            prevKategori.textContent = opt ? opt.text : "LPPM";
            prevKategori.className = "lppm-badge " + kategori.value;
        });
    }

    if (isi && prevIsi) {
        isi.addEventListener("input", function () {
            const clean = isi.value.replace(/<[^>]*>/g, "").trim();
            prevIsi.textContent = clean
                ? clean.length > 120
                    ? clean.slice(0, 120) + "..."
                    : clean
                : "Ringkasan konten penelitian atau pengabdian masyarakat akan tampil di sini.";
        });
    }

    function syncMeta() {
        if (!prevMeta) return;
        const parts = [pelaksana?.value.trim(), tahun?.value.trim()].filter(
            Boolean,
        );
        prevMeta.textContent = parts.length
            ? parts.join(" • ")
            : "Tim Pelaksana • Tahun";
    }

    pelaksana?.addEventListener("input", syncMeta);
    tahun?.addEventListener("input", syncMeta);
})();
