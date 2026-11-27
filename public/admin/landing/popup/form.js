(function () {
    const root = document.getElementById("popupFormApp");
    if (!root) return;

    const existingImg = root.dataset.existingImg || "";

    // ============================================================
    // 1. RICH TEXT EDITOR + SYNC TEXTAREA & PRATINJAU
    // ============================================================
    const rteBox = document.getElementById("deskripsiRte");
    const editor = document.getElementById("deskripsiEditor");
    const textarea = document.getElementById("deskripsi");
    const counter = document.getElementById("deskripsiCounter");
    const prevDesc = document.getElementById("prevDesc");

    function syncDeskripsi() {
        if (!editor || !textarea) return;
        const html = editor.innerHTML.trim();
        const plain = (editor.textContent || "").trim();
        const cleanHtml = plain === "" ? "" : html;

        textarea.value = cleanHtml;
        if (counter) {
            counter.textContent = `${plain.length} / 2000 karakter`;
        }
        if (prevDesc) {
            prevDesc.innerHTML =
                cleanHtml ||
                "<p>Deskripsi singkat mengenai pendaftaran mahasiswa baru, program studi, atau pengumuman kampus akan tampil rapi di sini.</p>";
        }
    }

    rteBox?.querySelectorAll("[data-cmd]").forEach((btn) => {
        btn.addEventListener("click", () => {
            const cmd = btn.getAttribute("data-cmd");
            editor.focus();

            if (cmd === "createLink") {
                const url = window.prompt(
                    "Masukkan URL tujuan (contoh: https://... atau /layanan):",
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
        const plain = (e.clipboardData || window.clipboardData).getData(
            "text/plain",
        );
        document.execCommand("insertText", false, plain);
        syncDeskripsi();
    });

    editor?.addEventListener("input", syncDeskripsi);
    editor?.addEventListener("blur", syncDeskripsi);
    syncDeskripsi();

    // ============================================================
    // 2. PALET KATEGORI (SELARAS DENGAN KATEGORI_LABELS CONTROLLER)
    // ============================================================
    const PALETTES = {
        pmb: {
            c1: "#0f2744",
            c2: "#1f5fbf",
            c3: "#f2994a",
            soft: "#dbeafe",
        },
        jurusan: {
            c1: "#1e1b4b",
            c2: "#4f46e5",
            c3: "#38bdf8",
            soft: "#e0e7ff",
        },
        beasiswa: {
            c1: "#064e3b",
            c2: "#059669",
            c3: "#fbbf24",
            soft: "#d1fae5",
        },
        info: {
            c1: "#78350f",
            c2: "#d97706",
            c3: "#fde68a",
            soft: "#fef3c7",
        },
        custom: {
            c1: "#134e4a",
            c2: "#0d9488",
            c3: "#f59e0b",
            soft: "#ccfbf1",
        },
    };

    const katSelect = document.getElementById("kategori");
    const badgeInput = document.getElementById("badge");
    const judulInput = document.getElementById("judul");
    const btn1Input = document.getElementById("teks_tombol");
    const btn2Input = document.getElementById("teks_sekunder");

    const stage = document.getElementById("popStage");
    const prevKatPill = document.getElementById("prevKatPill");
    const prevBadge = document.getElementById("prevBadge");
    const prevTitle = document.getElementById("prevTitle");
    const prevBtn1 = document.getElementById("prevBtn1");
    const prevBtn2 = document.getElementById("prevBtn2");

    function currentKey() {
        const k = katSelect?.value || "pmb";
        return PALETTES[k] ? k : "pmb";
    }

    function syncPreviewText() {
        const key = currentKey();
        if (stage) stage.setAttribute("data-theme", key);
        if (prevKatPill && katSelect) {
            prevKatPill.textContent =
                katSelect.options[katSelect.selectedIndex]?.text ||
                key.toUpperCase();
        }
        if (prevBadge)
            prevBadge.textContent =
                badgeInput?.value.trim() || "Informasi Kampus";
        if (prevTitle)
            prevTitle.textContent =
                judulInput?.value.trim() ||
                "Judul Slide Pop-up Akan Tampil di Sini";
        if (prevBtn1)
            prevBtn1.textContent = btn1Input?.value.trim() || "Selengkapnya";
        if (prevBtn2) {
            const s = btn2Input?.value.trim() || "";
            prevBtn2.textContent = s;
            prevBtn2.hidden = s === "";
        }
    }

    [badgeInput, judulInput, btn1Input, btn2Input].forEach((el) => {
        el?.addEventListener("input", syncPreviewText);
    });

    // ============================================================
    // 3. STUDIO ILUSTRASI KUSTOM + AUTO-WEBP CONVERTER
    // ============================================================
    const fileInput = document.getElementById("gambar");
    const dropzone = document.getElementById("popDropzone");
    const studioGrid = document.getElementById("popStudioGrid");
    const assetCard = document.getElementById("popAssetCard");
    const assetThumb = document.getElementById("popAssetThumb");
    const assetTag = document.getElementById("popAssetTag");
    const assetName = document.getElementById("popAssetName");
    const assetMeta = document.getElementById("popAssetMeta");
    const assetReset = document.getElementById("popAssetReset");
    const hapusCheck = document.getElementById("hapusGambarCheck");

    const prevMediaBox = document.getElementById("prevMediaBox");
    const prevStageImg = document.getElementById("prevStageImg");

    let activeArtId = null;

    function generateIllustrations(p) {
        return [
            {
                id: "gerbang-kampus",
                title: "Gerbang & Gedung Kampus",
                tag: "Arsitektur",
                svg: `<svg xmlns="http://www.w3.org/2000/svg" width="1200" height="750" viewBox="0 0 1200 750">
                                <defs>
                                    <linearGradient id="gA1" x1="0" y1="0" x2="1" y2="1"><stop offset="0%" stop-color="${p.c1}"/><stop offset="100%" stop-color="${p.c2}"/></linearGradient>
                                    <linearGradient id="gA2" x1="0" y1="0" x2="1" y2="0"><stop offset="0%" stop-color="${p.c3}"/><stop offset="100%" stop-color="#fde68a"/></linearGradient>
                                </defs>
                                <rect width="1200" height="750" fill="url(#gA1)"/>
                                <circle cx="990" cy="140" r="240" fill="#ffffff" opacity="0.06"/>
                                <circle cx="170" cy="650" r="270" fill="${p.c3}" opacity="0.13"/>
                                <rect x="290" y="235" width="620" height="385" rx="22" fill="#ffffff" fill-opacity="0.1" stroke="#ffffff" stroke-opacity="0.24" stroke-width="2"/>
                                <polygon points="255,240 600,115 945,240" fill="url(#gA2)"/>
                                <rect x="345" y="285" width="140" height="255" rx="10" fill="#ffffff" fill-opacity="0.16"/>
                                <rect x="530" y="285" width="140" height="255" rx="10" fill="#ffffff" fill-opacity="0.25"/>
                                <rect x="715" y="285" width="140" height="255" rx="10" fill="#ffffff" fill-opacity="0.16"/>
                                <rect x="545" y="425" width="110" height="195" rx="8" fill="url(#gA2)"/>
                                <rect x="190" y="615" width="820" height="22" rx="11" fill="#ffffff" fill-opacity="0.32"/>
                                <g transform="translate(865, 115)">
                                    <rect width="215" height="68" rx="16" fill="#ffffff"/>
                                    <circle cx="38" cy="34" r="16" fill="${p.c2}"/>
                                    <rect x="68" y="21" width="114" height="10" rx="5" fill="${p.c1}"/>
                                    <rect x="68" y="39" width="78" height="8" rx="4" fill="#94a3b8"/>
                                </g>
                            </svg>`,
            },
            {
                id: "beasiswa-prestasi",
                title: "Toga & Medali Beasiswa",
                tag: "Akademik",
                svg: `<svg xmlns="http://www.w3.org/2000/svg" width="1200" height="750" viewBox="0 0 1200 750">
                                <defs>
                                    <linearGradient id="gB1" x1="0" y1="1" x2="1" y2="0"><stop offset="0%" stop-color="${p.c1}"/><stop offset="100%" stop-color="${p.c2}"/></linearGradient>
                                    <linearGradient id="gB2" x1="0" y1="0" x2="1" y2="1"><stop offset="0%" stop-color="#fde68a"/><stop offset="100%" stop-color="${p.c3}"/></linearGradient>
                                </defs>
                                <rect width="1200" height="750" fill="url(#gB1)"/>
                                <circle cx="600" cy="375" r="290" fill="none" stroke="#ffffff" stroke-opacity="0.15" stroke-width="2" stroke-dasharray="10 12"/>
                                <circle cx="600" cy="375" r="215" fill="#ffffff" fill-opacity="0.07"/>
                                <polygon points="600,155 410,245 600,335 790,245" fill="url(#gB2)"/>
                                <path d="M475 285 V365 C475 405 725 405 725 365 V285 L600 345 Z" fill="#ffffff" fill-opacity="0.92"/>
                                <line x1="765" y1="255" x2="765" y2="395" stroke="${p.c3}" stroke-width="8" stroke-linecap="round"/>
                                <circle cx="765" cy="405" r="14" fill="url(#gB2)"/>
                                <path d="M360 470 Q600 420 840 470 L810 565 Q600 520 390 565 Z" fill="#ffffff" fill-opacity="0.16" stroke="#ffffff" stroke-opacity="0.3" stroke-width="2"/>
                                <polygon points="600,455 614,484 646,488 623,510 628,542 600,527 572,542 577,510 554,488 586,484" fill="url(#gB2)"/>
                            </svg>`,
            },
            {
                id: "inovasi-prodi",
                title: "Kartu Program Studi",
                tag: "Modern",
                svg: `<svg xmlns="http://www.w3.org/2000/svg" width="1200" height="750" viewBox="0 0 1200 750">
                                <defs>
                                    <linearGradient id="gC1" x1="0" y1="0" x2="1" y2="1"><stop offset="0%" stop-color="${p.c2}"/><stop offset="100%" stop-color="${p.c1}"/></linearGradient>
                                </defs>
                                <rect width="1200" height="750" fill="url(#gC1)"/>
                                <circle cx="220" cy="180" r="180" fill="${p.c3}" opacity="0.16"/>
                                <circle cx="1020" cy="580" r="220" fill="#ffffff" opacity="0.07"/>
                                <rect x="210" y="160" width="460" height="300" rx="24" fill="#ffffff" fill-opacity="0.14" stroke="#ffffff" stroke-opacity="0.28" stroke-width="2"/>
                                <circle cx="265" cy="210" r="10" fill="${p.c3}"/><circle cx="298" cy="210" r="10" fill="#ffffff" opacity="0.6"/><circle cx="331" cy="210" r="10" fill="#ffffff" opacity="0.35"/>
                                <rect x="255" y="255" width="240" height="22" rx="11" fill="#ffffff" opacity="0.85"/>
                                <rect x="255" y="298" width="350" height="14" rx="7" fill="#ffffff" opacity="0.4"/>
                                <rect x="255" y="328" width="290" height="14" rx="7" fill="#ffffff" opacity="0.4"/>
                                <rect x="255" y="378" width="150" height="42" rx="12" fill="${p.c3}"/>
                                <rect x="560" y="310" width="430" height="270" rx="24" fill="#ffffff"/>
                                <rect x="605" y="360" width="90" height="90" rx="20" fill="${p.soft}"/>
                                <circle cx="650" cy="405" r="24" fill="${p.c2}"/>
                                <rect x="720" y="372" width="210" height="18" rx="9" fill="${p.c1}"/>
                                <rect x="720" y="406" width="160" height="14" rx="7" fill="#94a3b8"/>
                                <rect x="605" y="485" width="340" height="48" rx="14" fill="${p.c1}"/>
                            </svg>`,
            },
            {
                id: "agenda-informasi",
                title: "Kalender & Pengumuman",
                tag: "Editorial",
                svg: `<svg xmlns="http://www.w3.org/2000/svg" width="1200" height="750" viewBox="0 0 1200 750">
                                <defs>
                                    <linearGradient id="gD1" x1="0" y1="0" x2="1" y2="1"><stop offset="0%" stop-color="${p.c1}"/><stop offset="100%" stop-color="${p.c2}"/></linearGradient>
                                </defs>
                                <rect width="1200" height="750" fill="url(#gD1)"/>
                                <path d="M0 560 Q360 470 720 550 T1200 500 L1200 750 L0 750 Z" fill="#ffffff" fill-opacity="0.08"/>
                                <rect x="250" y="150" width="700" height="450" rx="28" fill="#ffffff" fill-opacity="0.12" stroke="#ffffff" stroke-opacity="0.25" stroke-width="2"/>
                                <rect x="320" y="215" width="240" height="320" rx="20" fill="#ffffff"/>
                                <rect x="320" y="215" width="240" height="74" rx="20" fill="${p.c3}"/>
                                <circle cx="385" cy="350" r="18" fill="${p.soft}"/><circle cx="440" cy="350" r="18" fill="${p.soft}"/><circle cx="495" cy="350" r="18" fill="${p.c2}"/>
                                <circle cx="385" cy="410" r="18" fill="${p.soft}"/><circle cx="440" cy="410" r="18" fill="${p.soft}"/><circle cx="495" cy="410" r="18" fill="${p.soft}"/>
                                <circle cx="385" cy="470" r="18" fill="${p.soft}"/><circle cx="440" cy="470" r="18" fill="${p.soft}"/>
                                <rect x="610" y="245" width="270" height="26" rx="13" fill="#ffffff" opacity="0.9"/>
                                <rect x="610" y="295" width="220" height="16" rx="8" fill="#ffffff" opacity="0.45"/>
                                <rect x="610" y="330" width="250" height="16" rx="8" fill="#ffffff" opacity="0.45"/>
                                <rect x="610" y="395" width="180" height="52" rx="14" fill="${p.c3}"/>
                            </svg>`,
            },
        ];
    }

    function toOptimizedWebp(srcUrl, baseName) {
        return new Promise((resolve, reject) => {
            const img = new Image();
            img.crossOrigin = "anonymous";
            img.onload = () => {
                const maxW = 1200;
                let w = img.naturalWidth || 1200;
                let h = img.naturalHeight || 750;
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
                        const cleanName =
                            baseName.replace(/\.[^.]+$/, "") + ".webp";
                        const webpFile = new File([blob], cleanName, {
                            type: "image/webp",
                        });
                        resolve({
                            file: webpFile,
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

    function setSelectedFile(webpFile, tagText, metaText) {
        const dt = new DataTransfer();
        dt.items.add(webpFile);
        fileInput.files = dt.files;

        const url = URL.createObjectURL(webpFile);
        if (assetThumb) assetThumb.src = url;
        if (assetTag) assetTag.textContent = tagText;
        if (assetName) assetName.textContent = webpFile.name;
        if (assetMeta) assetMeta.textContent = metaText;
        if (assetCard) assetCard.hidden = false;

        if (prevStageImg) prevStageImg.src = url;
        if (prevMediaBox) prevMediaBox.hidden = false;
        if (hapusCheck) hapusCheck.checked = false;
    }

    function renderStudio() {
        if (!studioGrid) return;
        const palette = PALETTES[currentKey()];
        const items = generateIllustrations(palette);

        studioGrid.innerHTML = items
            .map(
                (item) => `
                        <button type="button" class="pop-art-card ${activeArtId === item.id ? "is-selected" : ""}" data-art-id="${item.id}">
                            <div class="pop-art-card__svg">${item.svg}</div>
                            <div class="pop-art-card__foot">
                                <span>${item.title}</span>
                                <span class="pop-art-card__pill">${item.tag}</span>
                            </div>
                        </button>
                    `,
            )
            .join("");

        studioGrid.querySelectorAll("[data-art-id]").forEach((btn) => {
            btn.addEventListener("click", async () => {
                const id = btn.getAttribute("data-art-id");
                const picked = items.find((x) => x.id === id);
                if (!picked) return;

                activeArtId = id;
                studioGrid
                    .querySelectorAll(".pop-art-card")
                    .forEach((c) => c.classList.remove("is-selected"));
                btn.classList.add("is-selected");

                const svgUrl =
                    "data:image/svg+xml;charset=utf-8," +
                    encodeURIComponent(picked.svg);
                const { file, width, height } = await toOptimizedWebp(
                    svgUrl,
                    `ilustrasi-${picked.id}`,
                );
                const kb = (file.size / 1024).toFixed(1);
                setSelectedFile(
                    file,
                    "Ilustrasi Kustom • WebP",
                    `${width}×${height}px • ${kb} KB`,
                );
            });
        });
    }

    async function handleFileSelect(rawFile) {
        if (!rawFile || !rawFile.type.startsWith("image/")) return;
        activeArtId = null;
        studioGrid
            ?.querySelectorAll(".pop-art-card")
            .forEach((c) => c.classList.remove("is-selected"));

        const origKb = (rawFile.size / 1024).toFixed(1);
        const rawUrl = URL.createObjectURL(rawFile);

        try {
            const { file, width, height } = await toOptimizedWebp(
                rawUrl,
                rawFile.name,
            );
            const newKb = (file.size / 1024).toFixed(1);
            setSelectedFile(
                file,
                "Banner Unggahan • Auto-WebP",
                `${width}×${height}px • ${origKb} KB → ${newKb} KB`,
            );
        } catch (_) {
            setSelectedFile(rawFile, "Banner Unggahan", `${origKb} KB`);
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
        if (f) handleFileSelect(f);
    });
    fileInput?.addEventListener("change", (e) => {
        const f = e.target.files?.[0];
        if (f) handleFileSelect(f);
    });

    document.querySelectorAll("[data-media-tab]").forEach((tab) => {
        tab.addEventListener("click", () => {
            const mode = tab.getAttribute("data-media-tab");
            document
                .querySelectorAll("[data-media-tab]")
                .forEach((t) => t.classList.remove("is-active"));
            tab.classList.add("is-active");
            document.getElementById("paneUpload").hidden = mode !== "upload";
            document.getElementById("paneStudio").hidden = mode !== "studio";
        });
    });

    assetReset?.addEventListener("click", () => {
        if (fileInput) fileInput.value = "";
        activeArtId = null;
        studioGrid
            ?.querySelectorAll(".pop-art-card")
            .forEach((c) => c.classList.remove("is-selected"));

        if (existingImg && !(hapusCheck && hapusCheck.checked)) {
            if (assetThumb) assetThumb.src = existingImg;
            if (assetTag) assetTag.textContent = "Gambar Tersimpan";
            if (prevStageImg) prevStageImg.src = existingImg;
            if (prevMediaBox) prevMediaBox.hidden = false;
        } else {
            if (assetCard) assetCard.hidden = true;
            if (prevMediaBox) prevMediaBox.hidden = true;
        }
    });

    hapusCheck?.addEventListener("change", () => {
        if (
            hapusCheck.checked &&
            (!fileInput || fileInput.files.length === 0)
        ) {
            if (prevMediaBox) prevMediaBox.hidden = true;
            if (assetCard) assetCard.hidden = true;
        } else if (!hapusCheck.checked && existingImg) {
            if (prevStageImg) prevStageImg.src = existingImg;
            if (prevMediaBox) prevMediaBox.hidden = false;
            if (assetThumb) assetThumb.src = existingImg;
            if (assetCard) assetCard.hidden = false;
        }
    });

    katSelect?.addEventListener("change", () => {
        syncPreviewText();
        renderStudio();
    });

    syncPreviewText();
    renderStudio();
})();
