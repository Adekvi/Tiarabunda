document.addEventListener("DOMContentLoaded", () => {
    const items = document.querySelectorAll(".sej__item");
    if (items.length && "IntersectionObserver" in window) {
        const io = new IntersectionObserver(
            (entries) => {
                entries.forEach((e) => {
                    if (e.isIntersecting) {
                        e.target.classList.add("rv", "in");
                        io.unobserve(e.target);
                    }
                });
            },
            { threshold: 0.15, rootMargin: "0px 0px -40px 0px" },
        );

        items.forEach((el) => {
            el.classList.add("rv");
            io.observe(el);
        });
    }

    const section = document.querySelector("[data-market-section]");
    if (!section) return;

    section.querySelectorAll("[data-accordion]").forEach((list) => {
        list.addEventListener("click", (e) => {
            const btn = e.target.closest(".sej__mkt-item-head");
            if (!btn) return;
            const item = btn.closest(".sej__mkt-item");
            const body = item.querySelector(".sej__mkt-item-body");
            const isOpen = item.classList.contains("is-open");

            list.querySelectorAll(".sej__mkt-item").forEach((it) => {
                it.classList.remove("is-open");
                it.querySelector(".sej__mkt-item-head")?.setAttribute(
                    "aria-expanded",
                    "false",
                );
                const b = it.querySelector(".sej__mkt-item-body");
                if (b) b.hidden = true;
            });

            if (!isOpen) {
                item.classList.add("is-open");
                btn.setAttribute("aria-expanded", "true");
                if (body) body.hidden = false;
            }
        });
    });

    const counters = section.querySelectorAll(".sej__mkt-counter");
    const animateCounter = (el) => {
        const target = parseInt(el.dataset.target, 10) || 0;
        const suffix = el.dataset.suffix || "";
        const duration = 1200;
        const start = performance.now();

        const step = (now) => {
            const p = Math.min((now - start) / duration, 1);
            const eased = 1 - Math.pow(1 - p, 3);
            el.textContent = Math.round(eased * target) + suffix;
            if (p < 1) requestAnimationFrame(step);
        };
        requestAnimationFrame(step);
    };

    if ("IntersectionObserver" in window) {
        const cIo = new IntersectionObserver(
            (entries) => {
                entries.forEach((entry) => {
                    if (!entry.isIntersecting) return;
                    animateCounter(entry.target);
                    cIo.unobserve(entry.target);
                });
            },
            { threshold: 0.35 },
        );
        counters.forEach((c) => cIo.observe(c));
    } else {
        counters.forEach(animateCounter);
    }

    const visual = section.querySelector("[data-visual]");
    if (visual) {
        const frame = visual.querySelector(".sej__mkt-frame");
        visual.addEventListener("mouseenter", () =>
            frame?.classList.add("is-hovered"),
        );
        visual.addEventListener("mouseleave", () =>
            frame?.classList.remove("is-hovered"),
        );
    }
});
