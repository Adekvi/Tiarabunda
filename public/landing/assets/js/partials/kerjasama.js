window.SiteApp?.register("kerjasama", ({ $, $$ }) => {
    const root = $("[data-ks-root]");
    if (!root) return;

    const modal = $("[data-ks-modal]", root);
    const dirModal = $("[data-ks-dir-modal]", root);
    const modalCleanups = new WeakMap();
    let isScrollLocked = false;

    const syncPageScrollLock = () => {
        const anyOpen = Boolean(
            (modal && !modal.hidden) || (dirModal && !dirModal.hidden),
        );

        root.classList.toggle("ks-has-modal", anyOpen);

        if (anyOpen && !isScrollLocked) {
            const scrollbarWidth =
                window.innerWidth - document.documentElement.clientWidth;
            document.documentElement.style.overflow = "hidden";
            document.body.style.overflow = "hidden";
            if (scrollbarWidth > 0) {
                document.body.style.paddingRight = `${scrollbarWidth}px`;
            }
            isScrollLocked = true;
        } else if (!anyOpen && isScrollLocked) {
            document.documentElement.style.overflow = "";
            document.body.style.overflow = "";
            document.body.style.paddingRight = "";
            isScrollLocked = false;
        }
    };

    const openModal = (el) => {
        if (!el) return;
        const cleanup = modalCleanups.get(el);
        if (cleanup) {
            cleanup();
            modalCleanups.delete(el);
        }

        el.hidden = false;
        el.setAttribute("aria-hidden", "false");
        syncPageScrollLock();

        requestAnimationFrame(() => {
            requestAnimationFrame(() => {
                if (!el.hidden) {
                    el.classList.add("is-open");
                }
            });
        });
    };

    const closeModal = (el) => {
        if (!el || el.hidden) return;
        const prevCleanup = modalCleanups.get(el);
        if (prevCleanup) {
            prevCleanup();
            modalCleanups.delete(el);
        }

        el.classList.remove("is-open");
        el.setAttribute("aria-hidden", "true");

        const dialog = $(".ks-modal-dialog", el);
        const reduceMotion = matchMedia(
            "(prefers-reduced-motion: reduce)",
        ).matches;

        if (reduceMotion || !dialog) {
            el.hidden = true;
            syncPageScrollLock();
            return;
        }

        let settled = false;
        const finish = () => {
            if (settled) return;
            settled = true;
            clearTimeout(timer);
            dialog.removeEventListener("transitionend", onEnd);
            modalCleanups.delete(el);

            if (!el.classList.contains("is-open")) {
                el.hidden = true;
                syncPageScrollLock();
            }
        };

        const onEnd = (e) => {
            if (e.target === dialog && e.propertyName === "transform") {
                finish();
            }
        };

        const timer = setTimeout(finish, 280);
        dialog.addEventListener("transitionend", onEnd);

        modalCleanups.set(el, () => {
            settled = true;
            clearTimeout(timer);
            dialog.removeEventListener("transitionend", onEnd);
        });
    };

    // 1. Modal Direktori Semua Mitra
    const dirSearch = $("[data-ks-dir-search]", root);
    const dirFilterBtns = $$("[data-ks-dir-filter]", root);
    const dirItems = $$("[data-ks-dir-item]", root);
    const dirScroll = $(".ks-dir-scroll", root);
    let activeDirGroup = "all";

    const closeDirModal = () => closeModal(dirModal);

    const filterDirectory = () => {
        const q = (dirSearch ? dirSearch.value : "").trim().toLowerCase();
        dirItems.forEach((item) => {
            const grp = item.dataset.kelompok || "";
            const blob = item.dataset.search || "";
            item.hidden = !(
                (activeDirGroup === "all" || grp === activeDirGroup) &&
                (q === "" || blob.includes(q))
            );
        });
        if (dirScroll) dirScroll.scrollTop = 0;
    };

    $$("[data-ks-dir-open]", root).forEach((btn) => {
        btn.addEventListener("click", () => {
            if (!dirModal) return;
            if (dirScroll) dirScroll.scrollTop = 0;
            openModal(dirModal);
        });
    });

    $$("[data-ks-dir-close]", root).forEach((el) => {
        el.addEventListener("click", closeDirModal);
    });

    dirFilterBtns.forEach((btn) => {
        btn.addEventListener("click", () => {
            activeDirGroup = btn.dataset.ksDirFilter || "all";
            dirFilterBtns.forEach((b) =>
                b.classList.toggle("is-active", b === btn),
            );
            filterDirectory();
        });
    });

    dirSearch?.addEventListener("input", filterDirectory);

    // 2. Filter, Pencarian & Load More (Batas 6 Item) untuk Dokumen Kerjasama
    const paginatedWrap = $("[data-ks-paginated]", root);
    const stepSize = paginatedWrap
        ? parseInt(paginatedWrap.dataset.ksStep || "6", 10)
        : 6;
    const filterButtons = $$("[data-ks-filter-type]", root);
    const searchInput = $("[data-ks-search]", root);
    const cards = $$("[data-ks-item]", root);
    const loadBar = $("[data-ks-load-bar]", root);
    const loadInfo = $("[data-ks-load-info]", root);
    const loadMoreBtn = $("[data-ks-load-more]", root);
    const loadLessBtn = $("[data-ks-load-less]", root);
    const emptyFiltered = $("[data-ks-no-result]", root);
    const resetBtn = $("[data-ks-reset]", root);

    let activeType = "all";
    let activeCat = "all";
    let visibleLimit = stepSize;

    const applyFilters = () => {
        if (!cards.length) return;
        const query = (searchInput ? searchInput.value : "")
            .trim()
            .toLowerCase();

        const matched = cards.filter((card) => {
            const jenis = card.dataset.jenis || "";
            const kat = card.dataset.kategori || "";
            const blob = card.dataset.search || "";
            return (
                (activeType === "all" || jenis === activeType) &&
                (activeCat === "all" || kat === activeCat) &&
                (query === "" || blob.includes(query))
            );
        });

        const matchedSet = new Set(matched);
        let shownCount = 0;

        cards.forEach((card) => {
            if (!matchedSet.has(card)) {
                card.hidden = true;
                return;
            }
            if (shownCount < visibleLimit) {
                card.hidden = false;
                shownCount++;
            } else {
                card.hidden = true;
            }
        });

        if (emptyFiltered) {
            emptyFiltered.hidden = matched.length > 0;
        }

        if (loadBar) {
            loadBar.hidden = matched.length <= stepSize;
            if (loadInfo) {
                loadInfo.textContent = `Menampilkan ${shownCount} dari ${matched.length} data kerjasama`;
            }
            if (loadMoreBtn) {
                loadMoreBtn.hidden = shownCount >= matched.length;
            }
            if (loadLessBtn) {
                loadLessBtn.hidden = visibleLimit <= stepSize;
            }
        }
    };

    filterButtons.forEach((btn) => {
        btn.addEventListener("click", () => {
            activeType = btn.dataset.ksFilterType || "all";
            activeCat = btn.dataset.ksFilterCat || "all";
            visibleLimit = stepSize;

            filterButtons.forEach((b) =>
                b.classList.toggle("is-active", b === btn),
            );
            applyFilters();
        });
    });

    searchInput?.addEventListener("input", () => {
        visibleLimit = stepSize;
        applyFilters();
    });

    loadMoreBtn?.addEventListener("click", () => {
        visibleLimit += stepSize;
        applyFilters();
    });

    loadLessBtn?.addEventListener("click", () => {
        visibleLimit = stepSize;
        applyFilters();
        paginatedWrap?.scrollIntoView({
            behavior: "smooth",
            block: "start",
        });
    });

    resetBtn?.addEventListener("click", () => {
        activeType = "all";
        activeCat = "all";
        visibleLimit = stepSize;
        if (searchInput) searchInput.value = "";
        filterButtons.forEach((b, i) =>
            b.classList.toggle("is-active", i === 0),
        );
        applyFilters();
    });

    $("[data-ks-jump-penawaran]", root)?.addEventListener("click", () => {
        $('[data-ks-filter-type="penawaran"]', root)?.click();
    });

    // 3. Salin nomor kontak (dengan fallback HTTP lokal)
    $$("[data-ks-copy]", root).forEach((btn) => {
        btn.addEventListener("click", async () => {
            const text = btn.dataset.ksCopy || "";
            if (!text) return;

            const original = btn.textContent;
            try {
                if (navigator.clipboard && window.isSecureContext) {
                    await navigator.clipboard.writeText(text);
                } else {
                    const ta = document.createElement("textarea");
                    ta.value = text;
                    ta.style.position = "fixed";
                    ta.style.opacity = "0";
                    document.body.appendChild(ta);
                    ta.select();
                    document.execCommand("copy");
                    ta.remove();
                }

                btn.textContent = "Tersalin!";
                btn.classList.add("is-copied");
                setTimeout(() => {
                    btn.textContent = original;
                    btn.classList.remove("is-copied");
                }, 1800);
            } catch (_) {}
        });
    });

    // 4. Modal Pratinjau Cepat Dokumen
    const modalTitle = $("[data-ks-modal-title]", root);
    const modalBadges = $("[data-ks-modal-badges]", root);
    const modalMeta = $("[data-ks-modal-meta]", root);
    const modalDesc = $("[data-ks-modal-desc]", root);
    const modalFoot = $("[data-ks-modal-foot]", root);
    const modalBody = $(".ks-modal-body", root);

    const closePreviewModal = () => closeModal(modal);

    $$("[data-ks-preview]", root).forEach((btn) => {
        btn.addEventListener("click", () => {
            if (!modal) return;
            let data = null;
            try {
                data = JSON.parse(btn.dataset.ksPreview || "{}");
            } catch (_) {
                return;
            }

            modalTitle.textContent = data.nama || "";
            modalBadges.innerHTML = "";

            if (data.jenis_label) {
                const b1 = document.createElement("span");
                b1.className = "ks-badge ks-badge--gold";
                b1.textContent = data.jenis_label;
                modalBadges.appendChild(b1);
            }
            if (data.kategori) {
                const b2 = document.createElement("span");
                b2.className = "ks-badge ks-badge--navy";
                b2.textContent = data.kategori;
                modalBadges.appendChild(b2);
            }

            const metaRows = [];
            if (data.nomor_dokumen)
                metaRows.push(["Nomor Dokumen", data.nomor_dokumen]);
            if (data.periode) metaRows.push(["Periode", data.periode]);
            if (data.kontak_pic)
                metaRows.push(["Narahubung / PIC", data.kontak_pic]);

            modalMeta.innerHTML = metaRows
                .map(
                    ([label, val]) =>
                        `<div class="ks-meta-box"><small>${label}</small><strong>${val}</strong></div>`,
                )
                .join("");

            modalDesc.textContent =
                data.keterangan ||
                "Informasi detail kerjasama tersedia pada halaman lengkap.";

            modalFoot.innerHTML = "";
            if (data.detail_url) {
                const aDetail = document.createElement("a");
                aDetail.href = data.detail_url;
                aDetail.className = "ks-btn ks-btn--navy";
                aDetail.textContent = "Buka Halaman Detail";
                modalFoot.appendChild(aDetail);
            }
            if (data.has_file && data.file_url) {
                const aFile = document.createElement("a");
                aFile.href = data.file_url;
                aFile.target = "_blank";
                aFile.rel = "noopener noreferrer";
                aFile.className = "ks-btn ks-btn--gold";
                aFile.textContent = `Unduh (${data.file_ext || "FILE"})`;
                modalFoot.appendChild(aFile);
            }
            if (data.tautan) {
                const aLink = document.createElement("a");
                aLink.href = data.tautan;
                aLink.target = "_blank";
                aLink.rel = "noopener noreferrer";
                aLink.className = "ks-btn ks-btn--navy-outline";
                aLink.textContent = "Tautan Resmi ↗";
                modalFoot.appendChild(aLink);
            }

            if (modalBody) modalBody.scrollTop = 0;
            openModal(modal);
        });
    });

    $$("[data-ks-modal-close]", root).forEach((el) => {
        el.addEventListener("click", closePreviewModal);
    });

    document.addEventListener("keydown", (e) => {
        if (e.key === "Escape") {
            if (modal && modal.classList.contains("is-open"))
                closePreviewModal();
            if (dirModal && dirModal.classList.contains("is-open"))
                closeDirModal();
        }
    });
});
