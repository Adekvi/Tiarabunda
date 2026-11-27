(function () {
    // ============================================================
    // 1. MODAL KONFIRMASI HAPUS / PULIHKAN
    // ============================================================
    const confirmModal = document.getElementById("tstConfirmModal");
    if (confirmModal) {
        const titleEl = document.getElementById("tstModalTitle");
        const msgEl = document.getElementById("tstModalMessage");
        const formEl = document.getElementById("tstConfirmForm");
        const methodEl = document.getElementById("tstConfirmMethod");
        const submitBtn = document.getElementById("tstConfirmSubmit");

        const closeConfirm = () => {
            confirmModal.hidden = true;
        };

        document.querySelectorAll("[data-confirm-open]").forEach((btn) => {
            btn.addEventListener("click", () => {
                if (titleEl)
                    titleEl.textContent =
                        btn.getAttribute("data-confirm-title") ||
                        "Konfirmasi Aksi";
                if (msgEl)
                    msgEl.textContent =
                        btn.getAttribute("data-confirm-message") ||
                        "Apakah Anda yakin?";
                if (formEl)
                    formEl.setAttribute(
                        "action",
                        btn.getAttribute("data-confirm-action") || "",
                    );
                if (methodEl)
                    methodEl.value =
                        btn.getAttribute("data-confirm-method") || "DELETE";

                if (submitBtn) {
                    const variant =
                        btn.getAttribute("data-confirm-variant") || "danger";
                    submitBtn.textContent =
                        btn.getAttribute("data-confirm-btn") || "Ya, Lanjutkan";
                    submitBtn.className =
                        variant === "primary"
                            ? "lok-btn lok-btn--primary"
                            : "lok-btn lok-btn--danger";
                }

                confirmModal.hidden = false;
            });
        });

        confirmModal.querySelectorAll("[data-confirm-close]").forEach((el) => {
            el.addEventListener("click", closeConfirm);
        });
    }

    // ============================================================
    // 2. MODAL PRATINJAU KARTU SPOTLIGHT BERANDA
    // ============================================================
    const previewModal = document.getElementById("tstPreviewModal");
    if (previewModal) {
        const pvKategori = document.getElementById("pvKategori");
        const pvStars = document.getElementById("pvStars");
        const pvSorotan = document.getElementById("pvSorotan");
        const pvKutipan = document.getElementById("pvKutipan");
        const pvFoto = document.getElementById("pvFoto");
        const pvInisial = document.getElementById("pvInisial");
        const pvNama = document.getElementById("pvNama");
        const pvAngkatan = document.getElementById("pvAngkatan");
        const pvPeran = document.getElementById("pvPeran");
        const pvInstansi = document.getElementById("pvInstansi");
        const pvEditLink = document.getElementById("pvEditLink");

        const closePreview = () => {
            previewModal.hidden = true;
        };

        document.querySelectorAll("[data-preview-open]").forEach((btn) => {
            btn.addEventListener("click", () => {
                const rating = Math.max(
                    1,
                    Math.min(
                        5,
                        parseInt(btn.getAttribute("data-rating") || "5", 10),
                    ),
                );
                const foto = btn.getAttribute("data-foto") || "";
                const inisial = btn.getAttribute("data-inisial") || "TB";

                if (pvKategori)
                    pvKategori.textContent =
                        btn.getAttribute("data-kategori") || "Alumni Berkarier";
                if (pvStars)
                    pvStars.textContent =
                        "★".repeat(rating) + "☆".repeat(5 - rating);
                if (pvSorotan)
                    pvSorotan.textContent =
                        btn.getAttribute("data-sorotan") || "";
                if (pvKutipan)
                    pvKutipan.textContent = `“${btn.getAttribute("data-kutipan") || ""}”`;
                if (pvNama)
                    pvNama.textContent = btn.getAttribute("data-nama") || "";
                if (pvAngkatan)
                    pvAngkatan.textContent =
                        btn.getAttribute("data-angkatan") || "";
                if (pvPeran)
                    pvPeran.textContent = btn.getAttribute("data-peran") || "";
                if (pvInstansi)
                    pvInstansi.textContent =
                        btn.getAttribute("data-instansi") || "";
                if (pvEditLink)
                    pvEditLink.setAttribute(
                        "href",
                        btn.getAttribute("data-edit-url") || "#",
                    );

                if (foto) {
                    if (pvFoto) {
                        pvFoto.src = foto;
                        pvFoto.hidden = false;
                    }
                    if (pvInisial) pvInisial.hidden = true;
                } else {
                    if (pvFoto) pvFoto.hidden = true;
                    if (pvInisial) {
                        pvInisial.textContent = inisial;
                        pvInisial.hidden = false;
                    }
                }

                previewModal.hidden = false;
            });
        });

        previewModal.querySelectorAll("[data-preview-close]").forEach((el) => {
            el.addEventListener("click", closePreview);
        });
    }

    document.addEventListener("keydown", (e) => {
        if (e.key === "Escape") {
            if (confirmModal && !confirmModal.hidden)
                confirmModal.hidden = true;
            if (previewModal && !previewModal.hidden)
                previewModal.hidden = true;
        }
    });
})();
