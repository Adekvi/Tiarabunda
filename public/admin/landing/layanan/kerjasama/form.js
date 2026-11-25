(function () {
    const jeneng = document.getElementById("jeneng");
    const tandha = document.getElementById("tandha");
    const kategori = document.getElementById("kategori");
    const katrangan = document.getElementById("katrangan");
    const lblJeneng = document.getElementById("lblJeneng");

    const prevJeneng = document.getElementById("prevJeneng");
    const prevKategori = document.getElementById("prevKategori");
    const prevKatrangan = document.getElementById("prevKatrangan");
    const prevJenis = document.getElementById("prevJenis");

    if (!jeneng || !tandha) return;

    let manualSlug = tandha.value.trim().length > 0;
    tandha.addEventListener("input", function () {
        manualSlug = tandha.value.trim().length > 0;
    });

    jeneng.addEventListener("input", function () {
        const val = jeneng.value.trim();
        if (prevJeneng)
            prevJeneng.textContent = val || "Nama Mitra / Judul Penawaran";
        if (!manualSlug) {
            tandha.value = val
                .toLowerCase()
                .replace(/[^a-z0-9\s-]/g, "")
                .replace(/\s+/g, "-")
                .replace(/-+/g, "-");
        }
    });

    document.querySelectorAll('input[name="jenis"]').forEach(function (radio) {
        radio.addEventListener("change", function () {
            const isPenawaran = radio.value === "penawaran";
            if (lblJeneng) {
                lblJeneng.innerHTML =
                    (isPenawaran
                        ? "Judul Program / Penawaran Kerjasama"
                        : "Nama Instansi / Perusahaan Mitra") +
                    ' <span style="color: var(--err);">*</span>';
            }
            if (prevJenis) {
                prevJenis.textContent = isPenawaran
                    ? "Penawaran Kerjasama"
                    : "Mitra Instansi";
                prevJenis.className =
                    "kjs-badge " + (isPenawaran ? "penawaran" : "mitra");
            }
        });
    });

    if (kategori && prevKategori) {
        kategori.addEventListener("input", function () {
            prevKategori.textContent =
                kategori.value.trim() || "Industri & Perusahaan";
        });
    }

    if (katrangan && prevKatrangan) {
        katrangan.addEventListener("input", function () {
            prevKatrangan.textContent =
                katrangan.value.trim() ||
                "Deskripsi ruang lingkup kerjasama akan tampil di sini.";
        });
    }
})();
