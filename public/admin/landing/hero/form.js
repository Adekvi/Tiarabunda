document.addEventListener("DOMContentLoaded", function () {
    const $ = (id) => document.getElementById(id);

    const MAX_IMAGE_MB = 2;
    const MAX_VIDEO_MB = 15;
    const MAX_POSTER_MB = 2;

    // Video
    const videoDropzone = $("video_dropzone");
    const inputVideo = $("input_video");
    const videoEmpty = $("video_empty");
    const videoPreviewBox = $("video_preview_box");
    const previewVideo = $("preview_video");
    const videoPreviewName = $("video_preview_name");
    const videoPreviewSize = $("video_preview_size");
    const btnRemoveVideo = $("btn_remove_video");
    const currentVideoCard = $("current_video_card");
    const chkHapusVideo = $("chk_hapus_video");

    // Poster
    const posterDropzone = $("poster_dropzone");
    const inputPoster = $("input_poster");
    const posterEmpty = $("poster_empty");
    const posterPreviewBox = $("poster_preview_box");
    const posterImg = $("poster_img");
    const posterName = $("poster_name");
    const btnRemovePoster = $("btn_remove_poster");
    const currentPosterCard = $("current_poster_card");

    // Multi Foto
    const fotosDropzone = $("fotos_dropzone");
    const inputFotos = $("input_fotos");
    const newFotosWrap = $("new_fotos_wrap");
    const newFotosGrid = $("new_fotos_grid");
    const newFotosCount = $("new_fotos_count");
    const btnClearNewFotos = $("btn_clear_new_fotos");

    // Publish Toggle
    const uripToggle = $("urip_toggle");
    const publishCard = $("publish_card");
    const publishIcon = $("publish_icon");
    const publishTitle = $("publish_title");
    const publishDesc = $("publish_desc");
    const publishLabel = $("publish_label");

    let videoObjectURL = null;
    let selectedFotoFiles = [];

    function formatSize(bytes) {
        if (!bytes) return "";
        if (bytes < 1024) return bytes + " B";
        if (bytes < 1048576) return (bytes / 1024).toFixed(1) + " KB";
        return (bytes / 1048576).toFixed(2) + " MB";
    }

    function setSingleFile(input, file) {
        try {
            const dt = new DataTransfer();
            dt.items.add(file);
            input.files = dt.files;
        } catch (e) {}
    }

    function syncFotosInput() {
        if (!inputFotos) return;
        try {
            const dt = new DataTransfer();
            selectedFotoFiles.forEach((f) => dt.items.add(f));
            inputFotos.files = dt.files;
        } catch (e) {}
    }

    // ---------- 1. VIDEO (MAKS 1) ----------
    function showVideoPreview(file) {
        if (!file) return;
        const isVideo =
            (file.type || "").startsWith("video/") ||
            /\.(mp4|webm|mov)$/i.test(file.name);

        if (!isVideo) {
            alert("Format video harus MP4, WebM, atau MOV.");
            inputVideo.value = "";
            return;
        }

        if (file.size > MAX_VIDEO_MB * 1024 * 1024) {
            alert(
                `Ukuran video ${formatSize(file.size)} melebihi batas ${MAX_VIDEO_MB}MB.`,
            );
            inputVideo.value = "";
            return;
        }

        setSingleFile(inputVideo, file);

        if (videoObjectURL) URL.revokeObjectURL(videoObjectURL);
        videoObjectURL = URL.createObjectURL(file);

        videoEmpty.style.display = "none";
        videoPreviewBox.classList.remove("hidden-preview");
        videoPreviewName.textContent = file.name;
        videoPreviewSize.textContent = formatSize(file.size);

        previewVideo.src = videoObjectURL;
        previewVideo.style.display = "inline-block";
        previewVideo.load();

        if (currentVideoCard) currentVideoCard.style.display = "none";
        if (chkHapusVideo) chkHapusVideo.checked = false;
    }

    function resetVideoPreview() {
        if (inputVideo) inputVideo.value = "";
        if (videoObjectURL) {
            URL.revokeObjectURL(videoObjectURL);
            videoObjectURL = null;
        }
        if (previewVideo) {
            previewVideo.pause();
            previewVideo.removeAttribute("src");
            previewVideo.style.display = "none";
        }
        if (videoEmpty) videoEmpty.style.display = "block";
        if (videoPreviewBox) videoPreviewBox.classList.add("hidden-preview");
        if (currentVideoCard) currentVideoCard.style.display = "flex";
    }

    videoDropzone?.addEventListener("click", (e) => {
        if (
            e.target.closest("#btn_remove_video") ||
            e.target.closest("#preview_video")
        )
            return;
        inputVideo?.click();
    });
    inputVideo?.addEventListener("change", (e) => {
        if (e.target.files?.[0]) showVideoPreview(e.target.files[0]);
    });
    btnRemoveVideo?.addEventListener("click", (e) => {
        e.preventDefault();
        e.stopPropagation();
        resetVideoPreview();
    });

    // ---------- 2. POSTER VIDEO ----------
    function showPosterPreview(file) {
        if (!file) return;
        if (!(file.type || "").startsWith("image/")) {
            alert("Poster harus berupa gambar (JPG, PNG, WEBP).");
            inputPoster.value = "";
            return;
        }
        if (file.size > MAX_POSTER_MB * 1024 * 1024) {
            alert(`Ukuran poster melebihi batas ${MAX_POSTER_MB}MB.`);
            inputPoster.value = "";
            return;
        }

        setSingleFile(inputPoster, file);
        const reader = new FileReader();
        reader.onload = (ev) => {
            posterImg.src = ev.target.result;
            posterImg.style.display = "inline-block";
        };
        reader.readAsDataURL(file);

        posterEmpty.style.display = "none";
        posterPreviewBox.classList.remove("hidden-preview");
        posterName.textContent = file.name;
        if (currentPosterCard) currentPosterCard.style.display = "none";
    }

    function resetPosterPreview() {
        if (inputPoster) inputPoster.value = "";
        if (posterImg) {
            posterImg.removeAttribute("src");
            posterImg.style.display = "none";
        }
        if (posterEmpty) posterEmpty.style.display = "flex";
        if (posterPreviewBox) posterPreviewBox.classList.add("hidden-preview");
        if (currentPosterCard) currentPosterCard.style.display = "flex";
    }

    posterDropzone?.addEventListener("click", (e) => {
        if (e.target.closest("#btn_remove_poster")) return;
        inputPoster?.click();
    });
    inputPoster?.addEventListener("change", (e) => {
        if (e.target.files?.[0]) showPosterPreview(e.target.files[0]);
    });
    btnRemovePoster?.addEventListener("click", (e) => {
        e.preventDefault();
        e.stopPropagation();
        resetPosterPreview();
    });

    // ---------- 3. MULTI FOTO SLIDER ----------
    function renderNewFotos() {
        if (!newFotosWrap || !newFotosGrid || !newFotosCount) return;
        newFotosGrid.innerHTML = "";

        if (selectedFotoFiles.length === 0) {
            newFotosWrap.style.display = "none";
            newFotosCount.textContent = "0";
            return;
        }

        newFotosWrap.style.display = "block";
        newFotosCount.textContent = String(selectedFotoFiles.length);

        selectedFotoFiles.forEach((file, idx) => {
            const card = document.createElement("div");
            card.className = "hero-foto-item";

            const img = document.createElement("img");
            img.alt = file.name;
            const url = URL.createObjectURL(file);
            img.onload = () => URL.revokeObjectURL(url);
            img.src = url;

            const badge = document.createElement("span");
            badge.className = "hero-foto-badge";
            badge.textContent = "Baru #" + (idx + 1);

            const btn = document.createElement("button");
            btn.type = "button";
            btn.className = "hero-foto-remove-btn";
            btn.title = "Hapus foto ini";
            btn.innerHTML = "&times;";
            btn.addEventListener("click", () => {
                selectedFotoFiles.splice(idx, 1);
                syncFotosInput();
                renderNewFotos();
            });

            card.appendChild(img);
            card.appendChild(badge);
            card.appendChild(btn);
            newFotosGrid.appendChild(card);
        });
    }

    function addFotoFiles(fileList) {
        const files = Array.from(fileList || []);
        if (!files.length) return;

        for (const file of files) {
            const isImg =
                (file.type || "").startsWith("image/") ||
                /\.(jpe?g|png|webp)$/i.test(file.name);

            if (!isImg) {
                alert(`File "${file.name}" bukan gambar yang didukung.`);
                continue;
            }
            if (file.size > MAX_IMAGE_MB * 1024 * 1024) {
                alert(`Foto "${file.name}" melebihi batas ${MAX_IMAGE_MB}MB.`);
                continue;
            }

            const exists = selectedFotoFiles.some(
                (f) =>
                    f.name === file.name &&
                    f.size === file.size &&
                    f.lastModified === file.lastModified,
            );
            if (!exists) {
                selectedFotoFiles.push(file);
            }
        }

        syncFotosInput();
        renderNewFotos();
    }

    fotosDropzone?.addEventListener("click", () => inputFotos?.click());
    inputFotos?.addEventListener("change", (e) => {
        addFotoFiles(e.target.files);
    });
    btnClearNewFotos?.addEventListener("click", () => {
        selectedFotoFiles = [];
        if (inputFotos) inputFotos.value = "";
        renderNewFotos();
    });

    // Visual state untuk foto tersimpan (Hapus & Tampil)
    document.querySelectorAll(".chk-hapus-foto").forEach((chk) => {
        chk.addEventListener("change", () => {
            chk.closest("[data-existing-foto]")?.classList.toggle(
                "is-marked-delete",
                chk.checked,
            );
        });
    });

    document.querySelectorAll(".chk-urip-foto").forEach((chk) => {
        chk.addEventListener("change", () => {
            chk.closest("[data-existing-foto]")?.classList.toggle(
                "is-inactive",
                !chk.checked,
            );
        });
    });

    // Drag & Drop
    [
        { el: videoDropzone, onDrop: (files) => showVideoPreview(files[0]) },
        { el: posterDropzone, onDrop: (files) => showPosterPreview(files[0]) },
        { el: fotosDropzone, onDrop: (files) => addFotoFiles(files) },
    ].forEach(({ el, onDrop }) => {
        if (!el) return;
        ["dragenter", "dragover"].forEach((ev) =>
            el.addEventListener(ev, (e) => {
                e.preventDefault();
                e.stopPropagation();
                el.classList.add("dragover");
            }),
        );
        ["dragleave", "drop"].forEach((ev) =>
            el.addEventListener(ev, (e) => {
                e.preventDefault();
                e.stopPropagation();
                el.classList.remove("dragover");
            }),
        );
        el.addEventListener("drop", (e) => {
            if (e.dataTransfer?.files?.length) onDrop(e.dataTransfer.files);
        });
    });

    // ---------- 4. PUBLISH TOGGLE ----------
    function updatePublishUI() {
        const active = uripToggle?.checked;
        if (!publishCard) return;

        if (active) {
            publishCard.style.borderColor = "rgba(34,197,94,0.45)";
            publishCard.style.background = "rgba(34,197,94,0.08)";
            publishIcon.style.background = "rgba(34,197,94,0.16)";
            publishIcon.innerHTML =
                '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#4ade80" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>';
            publishTitle.textContent = "Tampil di landing page";
            publishTitle.style.color = "#4ade80";
            publishDesc.textContent = "Hero akan tampil di situs publik.";
            publishLabel.textContent = "Aktif";
            publishLabel.style.color = "#4ade80";
        } else {
            publishCard.style.borderColor = "var(--line)";
            publishCard.style.background = "var(--bg)";
            publishIcon.style.background = "rgba(148,163,184,0.12)";
            publishIcon.innerHTML =
                '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="rgb(148,163,184)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line></svg>';
            publishTitle.textContent = "Tidak tampil di landing page";
            publishTitle.style.color = "var(--fg)";
            publishDesc.textContent =
                "Tersimpan, tapi tidak terlihat oleh pengunjung.";
            publishLabel.textContent = "Nonaktif";
            publishLabel.style.color = "var(--mut)";
        }
    }

    uripToggle?.addEventListener("change", updatePublishUI);
    updatePublishUI();
});
