(() => {
    const $ = (s, r = document) => r.querySelector(s);
    const $$ = (s, r = document) => [...r.querySelectorAll(s)];
    const body = document.body;

    /* ===================== CORE HELPER & MODULE REGISTRY ===================== */
    const count = (el) => {
        const to = +el.dataset.to,
            st = performance.now();
        const f = (n) => {
            const p = Math.min((n - st) / 1400, 1);
            el.textContent = Math.round(
                to * (1 - Math.pow(1 - p, 3)),
            ).toLocaleString("id-ID");
            if (p < 1) requestAnimationFrame(f);
        };
        requestAnimationFrame(f);
    };

    const reveal = (el) => {
        if (!el) return;
        el.classList.add("in");
        $$("[data-to]", el).forEach(count);
    };

    const registry = new Map();
    let booted = false;

    const runModule = (name, initFn) => {
        try {
            initFn({ $, $$, reveal });
        } catch (err) {
            console.error(`[SiteApp:${name}]`, err);
        }
    };

    window.SiteApp = {
        $,
        $$,
        reveal,
        register(name, initFn) {
            if (typeof initFn !== "function") return;
            registry.set(name, initFn);
            if (booted) {
                runModule(name, initFn);
            }
        },
        boot() {
            if (booted) return;
            booted = true;
            registry.forEach((initFn, name) => runModule(name, initFn));
        },
    };

    /* ===================== NAVBAR & DRAWER ===================== */
    const nav = $("#nav"),
        burger = $("#burger"),
        menuBody = $("#menu-body");

    const DESKTOP_MIN = 1100;

    const closeAllDropdowns = (exceptDd = null) => {
        $$("#nav .dd.open").forEach((dd) => {
            if (dd === exceptDd) return;
            dd.classList.remove("open");
            dd.querySelector(":scope > button")?.setAttribute(
                "aria-expanded",
                "false",
            );
        });
    };

    const setMenuOpen = (open) => {
        body.classList.toggle("menu-open", open);
        document.documentElement.classList.toggle("menu-open", open);
        burger?.setAttribute("aria-expanded", String(open));
        burger?.setAttribute("aria-label", open ? "Tutup menu" : "Buka menu");

        if (!open) {
            closeAllDropdowns();
            if (menuBody) menuBody.scrollTop = 0;
        }
    };

    const onScroll = () => nav && nav.classList.toggle("stuck", scrollY > 10);
    addEventListener("scroll", onScroll, { passive: true });
    onScroll();

    document.addEventListener(
        "click",
        (e) => {
            // 1. Toggle Hamburger
            if (e.target.closest("#burger")) {
                e.preventDefault();
                setMenuOpen(!body.classList.contains("menu-open"));
                return;
            }

            // 2. Tutup Drawer via tombol X atau Backdrop
            if (
                e.target.closest("#menu-close") ||
                e.target.closest("#nav-backdrop")
            ) {
                e.preventDefault();
                setMenuOpen(false);
                return;
            }

            // 3. Dropdown Induk (Tentang Kami, Lembaga, Pendidikan, dll.)
            const ddBtn = e.target.closest("#nav .dd > button");
            if (ddBtn) {
                e.preventDefault();
                e.stopPropagation();

                const parent = ddBtn.parentElement;
                const willOpen = !parent.classList.contains("open");

                closeAllDropdowns(parent);

                parent.classList.toggle("open", willOpen);
                ddBtn.setAttribute("aria-expanded", String(willOpen));

                if (willOpen && window.innerWidth < DESKTOP_MIN) {
                    requestAnimationFrame(() => {
                        parent.scrollIntoView({
                            block: "nearest",
                            behavior: "smooth",
                        });
                    });
                }
                return;
            }

            // 4. Klik Link di dalam Menu -> tutup drawer
            if (e.target.closest("#menu a[href]")) {
                setMenuOpen(false);
                return;
            }

            // 5. Klik di luar navbar -> tutup semua dropdown
            if (!e.target.closest("#nav")) {
                closeAllDropdowns();
            }
        },
        true,
    );

    let rt;
    window.addEventListener("resize", () => {
        clearTimeout(rt);
        rt = setTimeout(() => {
            if (
                window.innerWidth >= DESKTOP_MIN &&
                body.classList.contains("menu-open")
            ) {
                setMenuOpen(false);
            }
        }, 100);
    });

    document.addEventListener("keydown", (e) => {
        if (e.key === "Escape") {
            closeAllDropdowns();
            if (body.classList.contains("menu-open")) {
                setMenuOpen(false);
                burger?.focus();
            }
        }
    });

    /* ===================== PENCARIAN ===================== */
    (() => {
        const navEl = document.getElementById("nav");
        const sf = document.getElementById("srch");
        const sb = document.getElementById("srch-b");
        const sq = document.getElementById("q");
        if (!sf || !sb || !sq) return;

        const set = (o) => {
            if (o && body.classList.contains("menu-open")) {
                setMenuOpen(false);
            }
            sf.classList.toggle("open", o);
            navEl?.classList.toggle("searching", o);
            sb.setAttribute("aria-expanded", o);
            sb.setAttribute(
                "aria-label",
                o ? "Tutup pencarian" : "Buka pencarian",
            );
            if (o) sq.focus({ preventScroll: true });
            else {
                sq.value = "";
                sq.blur();
            }
        };

        if (sf.classList.contains("open")) {
            navEl?.classList.add("searching");
            sb.setAttribute("aria-expanded", "true");
            sb.setAttribute("aria-label", "Tutup pencarian");
        }

        sb.addEventListener("click", () => set(!sf.classList.contains("open")));

        addEventListener("keydown", (e) => {
            if (e.key === "Escape" && sf.classList.contains("open")) {
                set(false);
                sb.focus();
            }
        });

        document.addEventListener("click", (e) => {
            if (
                !sf.contains(e.target) &&
                sf.classList.contains("open") &&
                !sq.value.trim()
            )
                set(false);
        });
    })();

    /* ===================== ALASAN (TAB) ===================== */
    const why = $(".why-l");
    why?.addEventListener("click", (e) => {
        const b = e.target.closest("button");
        if (!b) return;
        $$("button", why).forEach((x) => {
            x.classList.toggle("on", x === b);
            x.setAttribute("aria-selected", x === b);
        });
        $$(".why-r [data-panel]").forEach(
            (p) => (p.hidden = p.dataset.panel !== b.dataset.why),
        );
    });

    /* ===================== PRODI FILTER ===================== */
    (() => {
        const tabs = document.getElementById("tabs");
        const grid = document.getElementById("grid");
        const empty = document.getElementById("prodi-empty");
        if (!tabs || !grid) return;

        const cards = [...grid.querySelectorAll("[data-prodi-card]")];

        const filter = (f) => {
            let visible = 0;

            cards.forEach((card) => {
                const show = f < 0 || +card.dataset.f === f;

                if (show) {
                    card.hidden = false;
                    card.style.setProperty("--i", visible);
                    card.style.animation = "none";
                    void card.offsetWidth;
                    card.style.animation = "";
                    visible++;
                } else {
                    card.hidden = true;
                }
            });

            if (empty) empty.hidden = visible > 0;
        };

        const activateTab = (btn) => {
            if (!btn) return;
            tabs.querySelectorAll(".prodi-tab").forEach((t) => {
                const isOn = t === btn;
                t.classList.toggle("on", isOn);
                t.setAttribute("aria-selected", isOn ? "true" : "false");
            });

            filter(+btn.dataset.f);

            if (window.innerWidth <= 960) {
                const left =
                    btn.offsetLeft - tabs.offsetWidth / 2 + btn.offsetWidth / 2;
                tabs.scrollTo({ left, behavior: "smooth" });
            }
        };

        tabs.addEventListener("click", (e) => {
            const btn = e.target.closest(".prodi-tab");
            if (btn) activateTab(btn);
        });

        // Aktifkan tab fakultas otomatis jika dibuka dari menu navbar (?f=0, ?f=1, dst.)
        const paramF = new URLSearchParams(window.location.search).get("f");
        if (paramF !== null && paramF !== "") {
            const targetTab = tabs.querySelector(
                `.prodi-tab[data-f="${paramF}"]`,
            );
            if (targetTab) activateTab(targetTab);
        }
    })();

    /* ===================== FAQ ===================== */
    $$(".qa > button").forEach((b) =>
        b.addEventListener("click", () => {
            const q = b.parentElement,
                o = !q.classList.contains("open");
            $$(".qa").forEach((x) => {
                x.classList.remove("open");
                x.firstElementChild.setAttribute("aria-expanded", false);
            });
            q.classList.toggle("open", o);
            b.setAttribute("aria-expanded", o);
        }),
    );

    /* ===================== FORM PMB + TICKER ===================== */
    const form = $("#pmb");
    form?.addEventListener("submit", (e) => {
        e.preventDefault();
        const em = $("#em"),
            m = $("#msg"),
            ok = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(em.value);
        m.className = "hero-pmb__msg show " + (ok ? "ok" : "err");
        m.textContent = ok
            ? "Terima kasih! Tautan pendaftaran akan dikirim ke email Anda."
            : "Format email belum benar. Contoh: nama@email.com";
        if (ok) em.value = "";
    });
    const tk = $("#tick");
    if (tk) {
        const items = JSON.parse(tk.dataset.items || "[]");
        let t = 0;
        if (items.length > 1)
            setInterval(() => {
                tk.classList.add("out");
                setTimeout(() => {
                    t = (t + 1) % items.length;
                    tk.textContent = items[t];
                    tk.classList.remove("out");
                }, 350);
            }, 3800);
    }

    /* ===================== HERO SLIDER ===================== */
    (() => {
        const root = $("[data-hero-slider]");
        if (!root) return;

        const slides = $$("[data-hero-slide]", root);
        const dots = $$("[data-hero-dot]", root);
        const n = slides.length;
        if (n < 2) return;

        const interval = Math.max(5000, +(root.dataset.interval || 6000));
        const reduce = matchMedia("(prefers-reduced-motion: reduce)").matches;

        let index = 0;
        let timer = null;
        let visible = true;
        let paused = false;

        const handleVideo = (slide, shouldPlay) => {
            const video = slide?.querySelector("video");
            if (!video) return;
            if (shouldPlay) {
                video.currentTime = 0;
                video.play().catch(() => {});
            } else {
                setTimeout(() => {
                    if (!slide.classList.contains("is-active")) {
                        video.pause();
                    }
                }, 900);
            }
        };

        const restartDotAnim = (dot) => {
            if (!dot) return;
            const bar = dot.querySelector("span");
            if (!bar) return;
            bar.style.animation = "none";
            void bar.offsetWidth;
            bar.style.animation = "";
        };

        const stopTimer = () => {
            if (timer) {
                clearInterval(timer);
                timer = null;
            }
        };

        const startTimer = () => {
            stopTimer();
            if (reduce || !visible || document.hidden) return;
            paused = false;
            root.classList.remove("is-paused");
            restartDotAnim(dots[index]);
            timer = setInterval(() => go(index + 1), interval);
        };

        const pauseTimer = () => {
            stopTimer();
            paused = true;
            root.classList.add("is-paused");
        };

        const go = (to) => {
            const next = (to + n) % n;
            if (next === index) return;

            const prevSlide = slides[index];
            const nextSlide = slides[next];

            prevSlide.classList.remove("is-active");
            nextSlide.classList.add("is-active");

            handleVideo(prevSlide, false);
            handleVideo(nextSlide, true);

            dots[index]?.classList.remove("is-active");
            dots[index]?.removeAttribute("aria-current");
            dots[next]?.classList.add("is-active");
            dots[next]?.setAttribute("aria-current", "true");

            index = next;

            if (!paused) startTimer();
        };

        $("[data-hero-next]", root)?.addEventListener("click", () =>
            go(index + 1),
        );
        $("[data-hero-prev]", root)?.addEventListener("click", () =>
            go(index - 1),
        );
        dots.forEach((dot, k) => dot.addEventListener("click", () => go(k)));

        document.addEventListener("visibilitychange", () => {
            document.hidden ? pauseTimer() : startTimer();
        });

        if ("IntersectionObserver" in window) {
            new IntersectionObserver(
                ([entry]) => {
                    visible = entry.isIntersecting;
                    visible ? startTimer() : pauseTimer();
                },
                { threshold: 0.25 },
            ).observe(root);
        }

        let startX = null;
        root.addEventListener("pointerdown", (e) => {
            if (!e.target.closest("a, button")) startX = e.clientX;
        });
        root.addEventListener("pointerup", (e) => {
            if (startX === null) return;
            const dx = e.clientX - startX;
            startX = null;
            if (Math.abs(dx) > 50) go(index + (dx < 0 ? 1 : -1));
        });
        root.addEventListener("pointercancel", () => (startX = null));

        handleVideo(slides[0], true);
        startTimer();
    })();

    /* ===================== INFO SLIDER ===================== */
    (() => {
        const root = $("#info");
        if (!root) return;
        const slides = $$(".info-slide", root);
        const dots = $$(".info-dots button", root);
        const n = slides.length;
        if (n < 2) return;

        const cur = $("#info-cur"),
            tog = $("#info-toggle");
        const reduce = matchMedia("(prefers-reduced-motion: reduce)").matches;
        let i = 0,
            busy = false,
            playing = !reduce,
            hold = false,
            visible = true;

        const sync = () =>
            root.classList.toggle(
                "paused",
                !playing || hold || !visible || document.hidden,
            );

        const go = (to, dir) => {
            to = (to + n) % n;
            if (to === i || busy) return;
            dir = dir ?? (to > i ? 1 : -1);
            busy = true;

            root.style.setProperty("--dx", dir * 70 + "px");
            void root.offsetWidth;

            const old = slides[i],
                nw = slides[to];
            old.classList.replace("is-active", "is-leaving");
            old.setAttribute("aria-hidden", "true");
            old.inert = true;
            nw.classList.add("is-active");
            nw.removeAttribute("aria-hidden");
            nw.inert = false;

            dots[i].classList.remove("on");
            dots[i].removeAttribute("aria-current");
            dots[to].classList.add("on");
            dots[to].setAttribute("aria-current", "true");

            i = to;
            cur.textContent = String(i + 1).padStart(2, "0");
            setTimeout(() => {
                old.classList.remove("is-leaving");
                busy = false;
            }, 950);
        };

        $(".info-dots", root).addEventListener("animationend", (e) => {
            if (e.animationName === "info-fill" && playing) go(i + 1, 1);
        });

        $("#info-next")?.addEventListener("click", () => go(i + 1, 1));
        $("#info-prev")?.addEventListener("click", () => go(i - 1, -1));
        dots.forEach((d, k) => d.addEventListener("click", () => go(k)));

        tog?.addEventListener("click", () => {
            playing = !playing;
            tog.classList.toggle("is-paused", !playing);
            tog.setAttribute(
                "aria-label",
                playing ? "Jeda slide otomatis" : "Putar slide otomatis",
            );
            sync();
        });

        root.addEventListener("pointerenter", () => ((hold = true), sync()));
        root.addEventListener("pointerleave", () => ((hold = false), sync()));
        root.addEventListener("focusin", () => ((hold = true), sync()));
        root.addEventListener("focusout", () => ((hold = false), sync()));
        document.addEventListener("visibilitychange", sync);
        if ("IntersectionObserver" in window)
            new IntersectionObserver(
                ([e]) => ((visible = e.isIntersecting), sync()),
                { threshold: 0.35 },
            ).observe(root);

        root.addEventListener("keydown", (e) => {
            if (e.key === "ArrowRight") go(i + 1, 1);
            else if (e.key === "ArrowLeft") go(i - 1, -1);
        });

        let x0 = null,
            moved = false;
        root.addEventListener("pointerdown", (e) => {
            if (!e.target.closest("button")) x0 = e.clientX;
        });
        root.addEventListener("pointerup", (e) => {
            if (x0 === null) return;
            const d = e.clientX - x0;
            x0 = null;
            if (Math.abs(d) > 50) {
                moved = true;
                go(i + (d < 0 ? 1 : -1), d < 0 ? 1 : -1);
            }
        });
        root.addEventListener("pointercancel", () => (x0 = null));
        root.addEventListener(
            "click",
            (e) => {
                if (moved) (e.preventDefault(), (moved = false));
            },
            true,
        );

        sync();
    })();

    /* ===================== REVEAL + COUNTER ===================== */
    if ("IntersectionObserver" in window) {
        const io = new IntersectionObserver(
            (es) =>
                es.forEach((e) => {
                    if (!e.isIntersecting) return;
                    reveal(e.target);
                    io.unobserve(e.target);
                }),
            { threshold: 0.15 },
        );
        $$(".rv").forEach((el) => io.observe(el));
    } else {
        $$(".rv").forEach(reveal);
    }

    /* ===================== TOC ===================== */
    (() => {
        const links = $$('.toc a[href^="#"]');
        if (!links.length) return;
        const map = links
            .map((a) => {
                const id = decodeURIComponent(a.getAttribute("href").slice(1));
                const el = document.getElementById(id);
                return el ? { a, el } : null;
            })
            .filter(Boolean);
        if (!map.length) return;

        const setActive = (id) => {
            links.forEach((a) =>
                a.classList.toggle(
                    "is-active",
                    a.getAttribute("href") === "#" + id,
                ),
            );
        };

        if ("IntersectionObserver" in window) {
            const io = new IntersectionObserver(
                (entries) => {
                    const visible = entries
                        .filter((e) => e.isIntersecting)
                        .sort(
                            (a, b) => b.intersectionRatio - a.intersectionRatio,
                        );
                    if (visible[0]) setActive(visible[0].target.id);
                },
                {
                    rootMargin: "-20% 0px -55% 0px",
                    threshold: [0, 0.25, 0.5, 1],
                },
            );
            map.forEach(({ el }) => io.observe(el));
        }
    })();

    /* ===================== LOKASI KAMPUS INTERACTIVE ===================== */
    (() => {
        const root = $("[data-lokasi-root]");
        if (!root) return;

        const dataScript = $("[data-lokasi-json]", root);
        if (!dataScript) return;

        let kampusData = [];
        try {
            kampusData = JSON.parse(dataScript.textContent || "[]");
        } catch (_) {
            return;
        }
        if (!kampusData.length) return;

        const tabs = $$("[data-lokasi-tab]", root);
        const viewBtns = $$("[data-view-mode]", root);
        const detailBox = $(".lokasi-detail", root);

        const photoPane = $("[data-pane-photo]", root);
        const mapPane = $("[data-pane-map]", root);
        const elImg = $("[data-stage-img]", root);
        const elIframe = $("[data-stage-iframe]", root);
        const elMapName = $("[data-map-name]", root);
        const elMapExt = $("[data-map-ext]", root);

        const elBadge = $("[data-stage-badge]", root);
        const elCoord = $("[data-stage-coord]", root);
        const elNama = $("[data-stage-nama]", root);
        const elDesc = $("[data-stage-desc]", root);
        const elChips = $("[data-stage-chips]", root);
        const elAlamat = $("[data-stage-alamat]", root);
        const elJam = $("[data-stage-jam]", root);
        const elTelp = $("[data-stage-telp]", root);
        const elUrl = $("[data-stage-url]", root);
        const elPill = $("[data-stage-pill]", root);
        const elAkses = $("[data-stage-akses]", root);
        const copyBtn = $("[data-copy-alamat]", root);
        const copyLabel = $("[data-copy-label]", root);

        let currentMode = "photo";

        elImg?.addEventListener("error", () => {
            const fb = elImg.dataset.fallback;
            if (fb && elImg.src !== fb) {
                elImg.src = fb;
            }
        });

        const setViewMode = (mode) => {
            currentMode = mode;
            const isMap = mode === "map";

            if (photoPane) photoPane.hidden = isMap;
            if (mapPane) mapPane.hidden = !isMap;

            if (
                isMap &&
                elIframe &&
                elIframe.dataset.src &&
                elIframe.src !== elIframe.dataset.src
            ) {
                elIframe.src = elIframe.dataset.src;
            }

            viewBtns.forEach((b) => {
                b.classList.toggle("is-active", b.dataset.viewMode === mode);
            });
        };

        const selectKampus = (idx) => {
            const item = kampusData[idx];
            if (!item) return;

            detailBox?.classList.add("is-switching");

            setTimeout(() => {
                if (elBadge) elBadge.textContent = item.badge || "";
                if (elCoord) elCoord.textContent = item.koordinat || "";
                if (elNama) elNama.textContent = item.nama || "";
                if (elDesc) elDesc.textContent = item.deskripsi || "";
                if (elAlamat) elAlamat.textContent = item.alamat || "";
                if (elJam) elJam.textContent = item.jam || "";
                if (elTelp) elTelp.textContent = item.telepon || "";
                if (elPill) elPill.textContent = `Kampus ${item.kode || ""}`;
                if (elAkses) elAkses.textContent = item.akses || "";
                if (elUrl && item.map_url) elUrl.href = item.map_url;
                if (elMapName) elMapName.textContent = item.nama || "";
                if (elMapExt && item.map_url) elMapExt.href = item.map_url;

                if (elImg && item.img) {
                    elImg.dataset.fallback = item.fallback || item.img;
                    elImg.src = item.img;
                    elImg.alt = `Foto ${item.nama || ""}`;
                }

                if (elIframe && item.map_embed) {
                    elIframe.dataset.src = item.map_embed;
                    if (currentMode === "map") {
                        elIframe.src = item.map_embed;
                    }
                }

                if (elChips && Array.isArray(item.unggulan)) {
                    elChips.innerHTML = item.unggulan
                        .map(
                            (chip) =>
                                `<span class="lokasi-chip">${chip}</span>`,
                        )
                        .join("");
                }

                if (copyBtn) {
                    copyBtn.dataset.copyAlamat = item.alamat || "";
                    copyBtn.classList.remove("is-copied");
                    if (copyLabel) copyLabel.textContent = "Salin Alamat";
                }

                tabs.forEach((t, i) => {
                    const active = i === idx;
                    t.classList.toggle("is-active", active);
                    t.setAttribute("aria-selected", String(active));
                });

                detailBox?.classList.remove("is-switching");
            }, 150);
        };

        tabs.forEach((btn) => {
            btn.addEventListener("click", () =>
                selectKampus(+btn.dataset.lokasiTab),
            );
        });

        viewBtns.forEach((btn) => {
            btn.addEventListener("click", () =>
                setViewMode(btn.dataset.viewMode),
            );
        });

        copyBtn?.addEventListener("click", async () => {
            const text = copyBtn.dataset.copyAlamat || "";
            if (!text) return;

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

                copyBtn.classList.add("is-copied");
                if (copyLabel) copyLabel.textContent = "Alamat Tersalin!";
                setTimeout(() => {
                    copyBtn.classList.remove("is-copied");
                    if (copyLabel) copyLabel.textContent = "Salin Alamat";
                }, 2200);
            } catch (_) {}
        });
    })();

    /* ===================== TESTIMONI INTERACTIVE ===================== */
    (() => {
        const root = $("[data-testimoni-root]");
        if (!root) return;

        const cards = $$("[data-testi-card]", root);
        const triggers = $$("[data-spot-trigger]", root);
        const filterBtns = $$("[data-testi-filter]", root);
        const spotContent = $(".testi-spot-content", root);
        const spotBox = $("[data-testi-spotlight]", root);

        if (!cards.length) return;

        const elImg = $("[data-spot-img]", root);
        const elInit = $("[data-spot-initials]", root);
        const elAngkatan = $("[data-spot-angkatan]", root);
        const elName = $("[data-spot-name]", root);
        const elRole = $("[data-spot-role]", root);
        const elInstansi = $("[data-spot-instansi]", root);
        const elSorotan = $("[data-spot-sorotan]", root);
        const elQuote = $("[data-spot-quote]", root);
        const elIndex = $("[data-spot-index]", root);

        let current = 0;
        let autoTimer = null;
        const reduceMotion = matchMedia(
            "(prefers-reduced-motion: reduce)",
        ).matches;

        const showSpotlight = (idx, scrollToSpot = false) => {
            const total = cards.length;
            current = (idx + total) % total;
            const card = cards[current];
            if (!card) return;

            spotContent?.classList.add("is-switching");

            setTimeout(() => {
                const {
                    nama,
                    peran,
                    angkatan,
                    instansi,
                    sorotan,
                    kutipan,
                    foto,
                    initials,
                } = card.dataset;

                if (elName) elName.textContent = nama || "";
                if (elRole) elRole.textContent = peran || "";
                if (elAngkatan) elAngkatan.textContent = angkatan || "";
                if (elInstansi) elInstansi.textContent = instansi || "";
                if (elSorotan) elSorotan.textContent = sorotan || "";
                if (elQuote) elQuote.textContent = `“${kutipan || ""}”`;
                if (elIndex)
                    elIndex.textContent = String(current + 1).padStart(2, "0");

                if (elImg && elInit) {
                    if (foto) {
                        elImg.src = foto;
                        elImg.alt = `Foto ${nama || ""}`;
                        elImg.style.display = "block";
                        elInit.style.display = "none";
                    } else {
                        elImg.style.display = "none";
                        elInit.textContent = initials || "TB";
                        elInit.style.display = "grid";
                    }
                }

                triggers.forEach((btn, i) => {
                    const active = i === current;
                    btn.classList.toggle("is-active", active);
                    btn.setAttribute("aria-selected", String(active));
                });

                cards.forEach((c, i) =>
                    c.classList.toggle("is-spotlighted", i === current),
                );
                spotContent?.classList.remove("is-switching");
            }, 160);

            if (scrollToSpot && spotBox) {
                spotBox.scrollIntoView({
                    behavior: "smooth",
                    block: "nearest",
                });
            }
        };

        const stopAuto = () => {
            if (autoTimer) {
                clearInterval(autoTimer);
                autoTimer = null;
            }
        };

        const startAuto = () => {
            stopAuto();
            if (reduceMotion || cards.length < 2) return;
            autoTimer = setInterval(() => showSpotlight(current + 1), 6500);
        };

        $("[data-spot-prev]", root)?.addEventListener("click", () => {
            showSpotlight(current - 1);
            startAuto();
        });
        $("[data-spot-next]", root)?.addEventListener("click", () => {
            showSpotlight(current + 1);
            startAuto();
        });
        triggers.forEach((btn) => {
            btn.addEventListener("click", () => {
                showSpotlight(+btn.dataset.spotTrigger);
                startAuto();
            });
        });

        cards.forEach((card, idx) => {
            card.addEventListener("click", () => {
                showSpotlight(idx, true);
                startAuto();
            });
        });

        filterBtns.forEach((btn) => {
            btn.addEventListener("click", () => {
                const cat = btn.dataset.testiFilter;
                filterBtns.forEach((b) => {
                    const on = b === btn;
                    b.classList.toggle("is-active", on);
                    b.setAttribute("aria-selected", String(on));
                });

                let firstMatch = -1;
                cards.forEach((card, idx) => {
                    const match =
                        cat === "all" || card.dataset.category === cat;
                    card.hidden = !match;
                    if (match && firstMatch === -1) firstMatch = idx;
                });

                if (firstMatch !== -1) showSpotlight(firstMatch);
            });
        });

        spotBox?.addEventListener("pointerenter", stopAuto);
        spotBox?.addEventListener("pointerleave", startAuto);
        startAuto();
    })();

    /* ===================== WELCOME POP-UP SLIDER ===================== */
    const wpop = $("#welcome-popup");
    if (wpop) {
        const track = $("[data-wpop-track]", wpop);
        const slides = $$("[data-wpop-slide]", wpop);
        const dots = $$("[data-wpop-go]", wpop);
        const prevBtn = $("[data-wpop-prev]", wpop);
        const nextBtn = $("[data-wpop-next]", wpop);
        const counter = $("[data-wpop-counter]", wpop);
        const total = slides.length;
        let currentIndex = 0;

        const goToSlide = (idx) => {
            if (!total) return;
            currentIndex = ((idx % total) + total) % total;

            if (track) {
                track.style.transform = `translate3d(-${currentIndex * 100}%, 0, 0)`;
            }

            slides.forEach((s, i) => {
                const active = i === currentIndex;
                s.classList.toggle("is-active", active);
                s.setAttribute("aria-hidden", String(!active));
            });

            dots.forEach((d, i) => {
                const active = i === currentIndex;
                d.classList.toggle("is-active", active);
                d.setAttribute("aria-selected", String(active));
            });

            if (counter) {
                counter.textContent = `${currentIndex + 1} / ${total}`;
            }
        };

        const closeWelcomePopup = () => {
            wpop.hidden = true;
            document.documentElement.style.overflow = "";
        };

        const openWelcomePopup = () => {
            goToSlide(0);
            wpop.hidden = false;
            document.documentElement.style.overflow = "hidden";
        };

        requestAnimationFrame(() => {
            setTimeout(openWelcomePopup, 400);
        });

        wpop.addEventListener("click", (e) => {
            if (e.target.closest("[data-wpop-close]")) {
                e.preventDefault();
                closeWelcomePopup();
                return;
            }
            if (e.target.closest("[data-wpop-prev]")) {
                e.preventDefault();
                goToSlide(currentIndex - 1);
                return;
            }
            if (e.target.closest("[data-wpop-next]")) {
                e.preventDefault();
                goToSlide(currentIndex + 1);
                return;
            }
            const dot = e.target.closest("[data-wpop-go]");
            if (dot) {
                e.preventDefault();
                goToSlide(+dot.dataset.wpopGo);
            }
        });

        document.addEventListener("keydown", (e) => {
            if (wpop.hidden) return;
            if (e.key === "Escape") closeWelcomePopup();
            else if (e.key === "ArrowLeft") goToSlide(currentIndex - 1);
            else if (e.key === "ArrowRight") goToSlide(currentIndex + 1);
        });
    }

    /* ===================== JALANKAN SEMUA PARTIAL TERDAFTAR ===================== */
    window.SiteApp.boot();
})();
