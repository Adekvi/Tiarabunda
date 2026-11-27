(function () {
    const app = document.getElementById("lokasiFormApp");
    if (!app) return;

    const existingFoto = app.dataset.existingFoto || "";

    // ============================================================
    // 1. RICH TEXT EDITOR DESKRIPSI + VALIDASI SUBMIT
    // ============================================================
    const form = document.getElementById("lokasiMainForm");
    const rteWrap = document.getElementById("lokRteWrap");
    const editor = document.getElementById("deskripsiEditor");
    const textarea = document.getElementById("deskripsi");
    const counter = document.getElementById("lokDeskripsiCounter");
    const errHint = document.getElementById("lokDeskripsiError");

    function syncDeskripsi() {
        if (!editor || !textarea) return false;
        const html = editor.innerHTML.trim();
        const plain = (editor.textContent || "").trim();
        const cleanHtml = plain === "" ? "" : html;

        textarea.value = cleanHtml;
        if (counter) {
            counter.textContent = `${plain.length} / 2000 karakter`;
        }
        if (plain !== "") {
            rteWrap?.classList.remove("is-invalid");
            if (errHint) errHint.hidden = true;
        }
        return plain !== "";
    }

    rteWrap?.querySelectorAll("[data-cmd]").forEach((btn) => {
        btn.addEventListener("click", () => {
            const cmd = btn.getAttribute("data-cmd");
            editor.focus();

            if (cmd === "createLink") {
                const url = window.prompt(
                    "Masukkan URL tautan (contoh: https://... atau /profil):",
                    "https://",
                );
                if (url && url.trim() !== "" && url !== "https://") {
                    document.execCommand("createLink", false, url.trim());
                }
            } else {
                document.execCommand(cmd, false, null);
            }
            syncDeskripsi();
        });
    });

    editor?.addEventListener("paste", (e) => {
        e.preventDefault();
        const text = (e.clipboardData || window.clipboardData).getData(
            "text/plain",
        );
        document.execCommand("insertText", false, text);
        syncDeskripsi();
    });

    editor?.addEventListener("input", syncDeskripsi);
    editor?.addEventListener("blur", syncDeskripsi);
    syncDeskripsi();

    form?.addEventListener("submit", (e) => {
        if (!syncDeskripsi()) {
            e.preventDefault();
            rteWrap?.classList.add("is-invalid");
            if (errHint) errHint.hidden = false;
            editor?.focus();
        }
    });

    // ============================================================
    // 2. LIVE CHIPS FASILITAS & OVERLAY PRATINJAU GEDUNG
    // ============================================================
    const unggulanInput = document.getElementById("unggulan");
    const chipsBox = document.getElementById("unggulanPreviewChips");
    const badgeInput = document.getElementById("badge");
    const aksesInput = document.getElementById("akses");
    const prevBadgeGedung = document.getElementById("prevBadgeGedung");
    const prevAksesGedung = document.getElementById("prevAksesGedung");

    function renderChipsPreview() {
        if (!unggulanInput || !chipsBox) return;
        const items = unggulanInput.value
            .split(",")
            .map((s) => s.trim())
            .filter((s) => s.length > 0);

        chipsBox.innerHTML = items
            .map(
                (label) =>
                    `<span class="lok-chip-pill">&#10003; ${label.replace(/</g, "&lt;")}</span>`,
            )
            .join("");
    }

    unggulanInput?.addEventListener("input", renderChipsPreview);
    badgeInput?.addEventListener("input", () => {
        if (prevBadgeGedung)
            prevBadgeGedung.textContent =
                badgeInput.value.trim() || "Kampus Utama";
    });
    aksesInput?.addEventListener("input", () => {
        if (prevAksesGedung)
            prevAksesGedung.textContent =
                aksesInput.value.trim() || "Akses lokasi gedung kampus";
    });
    renderChipsPreview();

    // ============================================================
    // 3. MEDIA UPLOAD AUTO-WEBP & STUDIO ILUSTRASI GEDUNG KUSTOM
    // ============================================================
    const fileInput = document.getElementById("inputFotoLokasi");
    const dropzone = document.getElementById("lokDropzone");
    const studioGrid = document.getElementById("lokStudioGrid");
    const previewImg = document.getElementById("previewFotoLokasi");
    const placeholderEl = document.getElementById("placeholderFotoLokasi");
    const fileMetaBox = document.getElementById("lokFileMetaBox");
    const sourceBadge = document.getElementById("lokFileSourceBadge");
    const fileNameText = document.getElementById("lokFileNameText");
    const fileSizeText = document.getElementById("lokFileSizeText");
    const clearBtn = document.getElementById("lokClearFotoBtn");
    const hapusCheck = document.getElementById("hapusFotoCheck");

    const CAMPUS_PALETTES = {
        navy: {
            c1: "#0f2744",
            c2: "#1f5fbf",
            accent: "#f2994a",
            sky: "#dbeafe",
            leaf: "#10b981",
        },
        emerald: {
            c1: "#064e3b",
            c2: "#059669",
            accent: "#fbbf24",
            sky: "#d1fae5",
            leaf: "#34d399",
        },
        amber: {
            c1: "#78350f",
            c2: "#d97706",
            accent: "#38bdf8",
            sky: "#fef3c7",
            leaf: "#10b981",
        },
        indigo: {
            c1: "#1e1b4b",
            c2: "#4f46e5",
            accent: "#f59e0b",
            sky: "#e0e7ff",
            leaf: "#10b981",
        },
    };

    let currentPaletteKey = "navy";
    let selectedArtId = null;

    function buildCampusIllustrations(p) {
        return [
            {
                id: "gedung-utama",
                title: "Gedung Utama Kampus",
                svg: `<svg xmlns="http://www.w3.org/2000/svg" width="1200" height="800" viewBox="0 0 1200 800">
                                <defs>
                                    <linearGradient id="sky1" x1="0" y1="0" x2="0" y2="1"><stop offset="0%" stop-color="${p.c1}"/><stop offset="100%" stop-color="${p.c2}"/></linearGradient>
                                </defs>
                                <rect width="1200" height="800" fill="url(#sky1)"/>
                                <circle cx="960" cy="160" r="140" fill="#ffffff" opacity="0.08"/>
                                <path d="M0 640 Q600 595 1200 640 L1200 800 L0 800 Z" fill="#ffffff" fill-opacity="0.14"/>
                                <rect x="240" y="250" width="720" height="410" rx="20" fill="#ffffff"/>
                                <rect x="215" y="225" width="770" height="36" rx="12" fill="${p.accent}"/>
                                <rect x="470" y="165" width="260" height="65" rx="14" fill="#ffffff"/>
                                <rect x="515" y="188" width="170" height="18" rx="9" fill="${p.c2}"/>
                                <g fill="${p.sky}">
                                    <rect x="290" y="300" width="95" height="75" rx="8"/><rect x="415" y="300" width="95" height="75" rx="8"/>
                                    <rect x="690" y="300" width="95" height="75" rx="8"/><rect x="815" y="300" width="95" height="75" rx="8"/>
                                    <rect x="290" y="405" width="95" height="75" rx="8"/><rect x="415" y="405" width="95" height="75" rx="8"/>
                                    <rect x="690" y="405" width="95" height="75" rx="8"/><rect x="815" y="405" width="95" height="75" rx="8"/>
                                </g>
                                <rect x="535" y="305" width="130" height="355" rx="12" fill="${p.c1}"/>
                                <rect x="560" y="515" width="80" height="145" rx="8" fill="${p.accent}"/>
                                <circle cx="155" cy="590" r="65" fill="${p.leaf}" opacity="0.9"/>
                                <circle cx="1045" cy="590" r="65" fill="${p.leaf}" opacity="0.9"/>
                                <rect x="140" y="655" width="920" height="22" rx="11" fill="#ffffff" opacity="0.55"/>
                            </svg>`,
            },
            {
                id: "kampus-modern",
                title: "Sayap Akademik Modern",
                svg: `<svg xmlns="http://www.w3.org/2000/svg" width="1200" height="800" viewBox="0 0 1200 800">
                                <defs>
                                    <linearGradient id="sky2" x1="0" y1="0" x2="1" y2="1"><stop offset="0%" stop-color="${p.c2}"/><stop offset="100%" stop-color="${p.c1}"/></linearGradient>
                                </defs>
                                <rect width="1200" height="800" fill="url(#sky2)"/>
                                <circle cx="220" cy="180" r="190" fill="${p.accent}" opacity="0.15"/>
                                <rect x="190" y="310" width="380" height="350" rx="20" fill="#ffffff" fill-opacity="0.92"/>
                                <rect x="530" y="200" width="480" height="460" rx="24" fill="#ffffff"/>
                                <rect x="530" y="200" width="480" height="28" rx="14" fill="${p.accent}"/>
                                <g fill="${p.sky}">
                                    <rect x="235" y="360" width="120" height="55" rx="8"/><rect x="385" y="360" width="120" height="55" rx="8"/>
                                    <rect x="235" y="445" width="120" height="55" rx="8"/><rect x="385" y="445" width="120" height="55" rx="8"/>
                                    <rect x="580" y="265" width="175" height="75" rx="10"/><rect x="785" y="265" width="175" height="75" rx="10"/>
                                    <rect x="580" y="370" width="175" height="75" rx="10"/><rect x="785" y="370" width="175" height="75" rx="10"/>
                                </g>
                                <rect x="695" y="495" width="150" height="165" rx="12" fill="${p.c1}"/>
                                <rect x="130" y="655" width="940" height="24" rx="12" fill="#ffffff" opacity="0.45"/>
                            </svg>`,
            },
            {
                id: "plaza-hijau",
                title: "Plaza & Taman Kampus",
                svg: `<svg xmlns="http://www.w3.org/2000/svg" width="1200" height="800" viewBox="0 0 1200 800">
                                <defs>
                                    <linearGradient id="sky3" x1="0" y1="1" x2="1" y2="0"><stop offset="0%" stop-color="${p.c1}"/><stop offset="100%" stop-color="${p.c2}"/></linearGradient>
                                </defs>
                                <rect width="1200" height="800" fill="url(#sky3)"/>
                                <polygon points="600,145 250,295 950,295" fill="${p.accent}"/>
                                <rect x="290" y="295" width="620" height="355" rx="16" fill="#ffffff"/>
                                <rect x="355" y="335" width="52" height="315" rx="8" fill="${p.sky}"/>
                                <rect x="465" y="335" width="52" height="315" rx="8" fill="${p.sky}"/>
                                <rect x="683" y="335" width="52" height="315" rx="8" fill="${p.sky}"/>
                                <rect x="793" y="335" width="52" height="315" rx="8" fill="${p.sky}"/>
                                <rect x="550" y="455" width="100" height="195" rx="10" fill="${p.c1}"/>
                                <ellipse cx="600" cy="685" rx="460" ry="38" fill="${p.leaf}" opacity="0.85"/>
                            </svg>`,
            },
            {
                id: "lokasi-pin",
                title: "Peta & Landmark Wilayah",
                svg: `<svg xmlns="http://www.w3.org/2000/svg" width="1200" height="800" viewBox="0 0 1200 800">
                                <defs>
                                    <linearGradient id="sky4" x1="0" y1="0" x2="1" y2="1"><stop offset="0%" stop-color="${p.c1}"/><stop offset="100%" stop-color="${p.c2}"/></linearGradient>
                                </defs>
                                <rect width="1200" height="800" fill="url(#sky4)"/>
                                <g stroke="#ffffff" stroke-opacity="0.12" stroke-width="3">
                                    <line x1="0" y1="200" x2="1200" y2="200"/><line x1="0" y1="400" x2="1200" y2="400"/><line x1="0" y1="600" x2="1200" y2="600"/>
                                    <line x1="300" y1="0" x2="300" y2="800"/><line x1="600" y1="0" x2="600" y2="800"/><line x1="900" y1="0" x2="900" y2="800"/>
                                </g>
                                <circle cx="600" cy="400" r="230" fill="#ffffff" fill-opacity="0.1"/>
                                <path d="M600 175 C495 175 415 255 415 355 C415 485 600 645 600 645 C600 645 785 485 785 355 C785 255 705 175 600 175 Z" fill="#ffffff"/>
                                <circle cx="600" cy="350" r="78" fill="${p.accent}"/>
                                <rect x="565" y="320" width="70" height="60" rx="8" fill="${p.c1}"/>
                            </svg>`,
            },
        ];
    }

    function convertToWebp(srcUrl, baseName) {
        return new Promise((resolve, reject) => {
            const img = new Image();
            img.crossOrigin = "anonymous";
            img.onload = () => {
                const maxW = 1200;
                let w = img.naturalWidth || 1200;
                let h = img.naturalHeight || 800;
                if (w > maxW) {
                    h = Math.round((h * maxW) / w);
                    w = maxW;
                }
                const canvas = document.createElement("canvas");
                canvas.width = w;
                canvas.height = h;
                canvas.getContext("2d").drawImage(img, 0, 0, w, h);

                canvas.toBlob(
                    (blob) => {
                        if (!blob)
                            return reject(new Error("Konversi WebP gagal"));
                        const webpName =
                            baseName.replace(/\.[^.]+$/, "") + ".webp";
                        const file = new File([blob], webpName, {
                            type: "image/webp",
                        });
                        resolve({
                            file,
                            width: w,
                            height: h,
                        });
                    },
                    "image/webp",
                    0.86,
                );
            };
            img.onerror = reject;
            img.src = srcUrl;
        });
    }

    function applySelectedFoto(file, badgeLabel, sizeLabel) {
        const dt = new DataTransfer();
        dt.items.add(file);
        fileInput.files = dt.files;

        const url = URL.createObjectURL(file);
        if (previewImg) {
            previewImg.src = url;
            previewImg.hidden = false;
        }
        if (placeholderEl) placeholderEl.hidden = true;

        if (sourceBadge) sourceBadge.textContent = badgeLabel;
        if (fileNameText) fileNameText.textContent = file.name;
        if (fileSizeText) fileSizeText.textContent = sizeLabel;
        if (fileMetaBox) fileMetaBox.hidden = false;
        if (hapusCheck) hapusCheck.checked = false;
    }

    function renderStudioGrid() {
        if (!studioGrid) return;
        const palette =
            CAMPUS_PALETTES[currentPaletteKey] || CAMPUS_PALETTES.navy;
        const list = buildCampusIllustrations(palette);

        studioGrid.innerHTML = list
            .map(
                (item) => `
                        <button type="button" class="lok-art-item ${selectedArtId === item.id ? "is-selected" : ""}" data-art-id="${item.id}">
                            <div class="lok-art-item__svg">${item.svg}</div>
                            <div class="lok-art-item__label">${item.title}</div>
                        </button>
                    `,
            )
            .join("");

        studioGrid.querySelectorAll("[data-art-id]").forEach((btn) => {
            btn.addEventListener("click", async () => {
                const id = btn.getAttribute("data-art-id");
                const chosen = list.find((x) => x.id === id);
                if (!chosen) return;

                selectedArtId = id;
                studioGrid
                    .querySelectorAll(".lok-art-item")
                    .forEach((el) => el.classList.remove("is-selected"));
                btn.classList.add("is-selected");

                const svgUrl =
                    "data:image/svg+xml;charset=utf-8," +
                    encodeURIComponent(chosen.svg);
                const { file, width, height } = await convertToWebp(
                    svgUrl,
                    `gedung-${chosen.id}`,
                );
                const kb = (file.size / 1024).toFixed(1);
                applySelectedFoto(
                    file,
                    "Ilustrasi Kustom • WebP",
                    `${width}×${height}px • ${kb} KB`,
                );
            });
        });
    }

    async function handleRawFile(rawFile) {
        if (!rawFile || !rawFile.type.startsWith("image/")) return;
        selectedArtId = null;
        studioGrid
            ?.querySelectorAll(".lok-art-item")
            .forEach((el) => el.classList.remove("is-selected"));

        const origKb = (rawFile.size / 1024).toFixed(1);
        const rawUrl = URL.createObjectURL(rawFile);

        try {
            const { file, width, height } = await convertToWebp(
                rawUrl,
                rawFile.name,
            );
            const newKb = (file.size / 1024).toFixed(1);
            applySelectedFoto(
                file,
                "Foto Unggahan • Auto-WebP",
                `${width}×${height}px • ${origKb} KB → ${newKb} KB`,
            );
        } catch (_) {
            applySelectedFoto(rawFile, "Foto Unggahan", `${origKb} KB`);
        }
    }

    dropzone?.addEventListener("click", () => fileInput?.click());
    dropzone?.addEventListener("keydown", (e) => {
        if (e.key === "Enter" || e.key === " ") {
            e.preventDefault();
            fileInput?.click();
        }
    });
    dropzone?.addEventListener("dragover", (e) => {
        e.preventDefault();
        dropzone.classList.add("is-dragover");
    });
    dropzone?.addEventListener("dragleave", () =>
        dropzone.classList.remove("is-dragover"),
    );
    dropzone?.addEventListener("drop", (e) => {
        e.preventDefault();
        dropzone.classList.remove("is-dragover");
        const f = e.dataTransfer?.files?.[0];
        if (f) handleRawFile(f);
    });
    fileInput?.addEventListener("change", (e) => {
        const f = e.target.files?.[0];
        if (f) handleRawFile(f);
    });

    // Switch Tab Upload vs Ilustrasi
    document.querySelectorAll("[data-lok-tab]").forEach((tab) => {
        tab.addEventListener("click", () => {
            const target = tab.getAttribute("data-lok-tab");
            document
                .querySelectorAll("[data-lok-tab]")
                .forEach((t) => t.classList.remove("is-active"));
            tab.classList.add("is-active");
            document.getElementById("lokPaneUpload").hidden =
                target !== "upload";
            document.getElementById("lokPaneStudio").hidden =
                target !== "studio";
        });
    });

    // Swatch Tema Warna Ilustrasi
    document.querySelectorAll("[data-palette]").forEach((sw) => {
        sw.addEventListener("click", () => {
            currentPaletteKey = sw.getAttribute("data-palette") || "navy";
            document
                .querySelectorAll("[data-palette]")
                .forEach((s) => s.classList.remove("is-active"));
            sw.classList.add("is-active");
            renderStudioGrid();
        });
    });

    // Reset Pilihan Foto Baru
    clearBtn?.addEventListener("click", () => {
        if (fileInput) {
            fileInput.value = "";
            fileInput.files = new DataTransfer().files;
        }
        selectedArtId = null;
        studioGrid
            ?.querySelectorAll(".lok-art-item")
            .forEach((el) => el.classList.remove("is-selected"));
        if (fileMetaBox) fileMetaBox.hidden = true;

        if (existingFoto && !(hapusCheck && hapusCheck.checked)) {
            if (previewImg) {
                previewImg.src = existingFoto;
                previewImg.hidden = false;
            }
            if (placeholderEl) placeholderEl.hidden = true;
        } else {
            if (previewImg) previewImg.hidden = true;
            if (placeholderEl) placeholderEl.hidden = false;
        }
    });

    hapusCheck?.addEventListener("change", () => {
        if (
            hapusCheck.checked &&
            (!fileInput || fileInput.files.length === 0)
        ) {
            if (previewImg) previewImg.hidden = true;
            if (placeholderEl) placeholderEl.hidden = false;
        } else if (!hapusCheck.checked && existingFoto) {
            if (previewImg) {
                previewImg.src = existingFoto;
                previewImg.hidden = false;
            }
            if (placeholderEl) placeholderEl.hidden = true;
        }
    });

    renderStudioGrid();
})();
