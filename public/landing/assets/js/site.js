(() => {
    const $ = (s, r = document) => r.querySelector(s);
    const $$ = (s, r = document) => [...r.querySelectorAll(s)];
    const body = document.body;

    /* ===================== NAVBAR ===================== */
    const nav = $("#nav"),
        burger = $("#burger");
    const onScroll = () => nav && nav.classList.toggle("stuck", scrollY > 10);
    addEventListener("scroll", onScroll, { passive: true });
    onScroll();
    burger?.addEventListener("click", () =>
        burger.setAttribute(
            "aria-expanded",
            body.classList.toggle("menu-open"),
        ),
    );

    // Dropdown induk (Profil, Pendidikan, Layanan, ...)
    $$(".dd > button").forEach((b) =>
        b.addEventListener("click", () =>
            b.setAttribute(
                "aria-expanded",
                b.parentElement.classList.toggle("open"),
            ),
        ),
    );
    $$("#menu a").forEach((l) =>
        l.addEventListener("click", () => body.classList.remove("menu-open")),
    );

    /* ===================== PENCARIAN ===================== */
    (() => {
        const navEl = document.getElementById("nav");
        const sf = document.getElementById("srch");
        const sb = document.getElementById("srch-b");
        const sq = document.getElementById("q");
        if (!sf || !sb || !sq) return;

        const set = (o) => {
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
                    // re-trigger animation
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

        tabs.addEventListener("click", (e) => {
            const btn = e.target.closest(".prodi-tab");
            if (!btn) return;

            tabs.querySelectorAll(".prodi-tab").forEach((t) => {
                const isOn = t === btn;
                t.classList.toggle("on", isOn);
                t.setAttribute("aria-selected", isOn ? "true" : "false");
            });

            filter(+btn.dataset.f);

            // Auto scroll tab di mobile
            if (window.innerWidth <= 960) {
                const left =
                    btn.offsetLeft - tabs.offsetWidth / 2 + btn.offsetWidth / 2;
                tabs.scrollTo({ left, behavior: "smooth" });
            }
        });
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
        el.classList.add("in");
        $$("[data-to]", el).forEach(count);
    };
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

    /* ===================== NESTED DROPDOWN (Program Pendidikan) ===================== */
    const DESKTOP_MIN = 961;
    const PANEL_W = 360;
    const NEED = PANEL_W + 8 + 16;

    const clearPanel = (panel) => {
        panel.classList.remove("flyout-left", "flyout-stack");
        panel.style.maxHeight = "";
    };

    const closeNested = (el) => {
        el.classList.remove("open");
        el.querySelector(".dd-nested-toggle")?.setAttribute(
            "aria-expanded",
            "false",
        );
        const pnl = el.querySelector(".dd-nested-panel");
        if (pnl) clearPanel(pnl);
    };

    const closeAllNested = (except) => {
        $$(".dd-nested.open").forEach((el) => {
            if (el !== except) closeNested(el);
        });
    };

    const place = (parent) => {
        const panel = parent.querySelector(".dd-nested-panel");
        if (!panel) return;
        clearPanel(panel);
        if (window.innerWidth < DESKTOP_MIN) return;

        const vw = document.documentElement.clientWidth;
        const vh = document.documentElement.clientHeight;
        const outer = parent.closest(".dd-p") || parent;
        const a = outer.getBoundingClientRect();

        if (vw - a.right >= NEED) {
            /* muat di kanan: default CSS */
        } else if (a.left >= NEED) {
            panel.classList.add("flyout-left");
        } else {
            panel.classList.add("flyout-stack");
            return;
        }

        const top = parent.getBoundingClientRect().top;
        panel.style.maxHeight = Math.max(vh - top - 16, 200) + "px";
    };

    // Satu handler capture untuk toggle nested + fakultas accordion
    document.addEventListener(
        "click",
        (e) => {
            // --- Nested toggle (Program Pendidikan) ---
            const nbtn = e.target.closest(".dd-nested-toggle");
            if (nbtn) {
                e.preventDefault();
                e.stopPropagation();

                const parent = nbtn.closest(".dd-nested");
                const willOpen = !parent.classList.contains("open");

                closeAllNested(parent);
                parent.classList.toggle("open", willOpen);
                nbtn.setAttribute("aria-expanded", String(willOpen));

                const topDd = parent.parentElement.closest(".dd");
                if (topDd && willOpen) topDd.classList.add("open");

                const panel = parent.querySelector(".dd-nested-panel");
                if (willOpen && panel) {
                    panel.scrollTop = 0;
                    place(parent);
                } else if (panel) {
                    clearPanel(panel);
                }
                return;
            }

            // --- Fakultas accordion ---
            const fbtn = e.target.closest(".dd-fak-toggle");
            if (fbtn) {
                e.preventDefault();
                e.stopPropagation();

                const group = fbtn.closest(".dd-fakultas-group");
                const panel = group?.querySelector(".dd-fak-panel");
                if (!group || !panel) return;

                const willOpen = fbtn.getAttribute("aria-expanded") !== "true";

                // Tutup fakultas lain di container yang sama
                const container = group.closest(
                    ".dd-fakultas-list, .dd-nested-panel",
                );
                container
                    ?.querySelectorAll('.dd-fak-toggle[aria-expanded="true"]')
                    .forEach((other) => {
                        if (other === fbtn) return;
                        other.setAttribute("aria-expanded", "false");
                        const p = other
                            .closest(".dd-fakultas-group")
                            ?.querySelector(".dd-fak-panel");
                        if (p) p.hidden = true;
                    });

                fbtn.setAttribute("aria-expanded", String(willOpen));
                panel.hidden = !willOpen;

                // Jaga dropdown induk tetap terbuka
                fbtn.closest(".dd")?.classList.add("open");
            }
        },
        true,
    );

    let rt;
    window.addEventListener("resize", () => {
        clearTimeout(rt);
        rt = setTimeout(() => {
            $$(".dd-nested.open").forEach(place);
        }, 100);
    });

    document.addEventListener("click", (e) => {
        if (!e.target.closest(".dd-nested")) closeAllNested();
    });

    document.addEventListener("keydown", (e) => {
        if (e.key === "Escape") closeAllNested();
    });
})();
