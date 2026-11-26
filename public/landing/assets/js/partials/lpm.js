window.SiteApp?.register("lpm", ({ $, $$, reveal }) => {
    const root = $("[data-lpm-root]");
    if (!root) return;

    const tabs = $$("[data-lpm-filter]", root);
    const cards = $$("[data-lpm-card]", root);
    const searchInput = $("[data-lpm-search]", root);
    const emptyBox = $("[data-lpm-empty]", root);

    if (!cards.length) return;

    let activeKat = "all";

    const applyFilter = () => {
        const q = searchInput ? searchInput.value.trim().toLowerCase() : "";
        let visibleCount = 0;

        cards.forEach((card) => {
            const cardKat = card.dataset.kategori || "";
            const blob = card.dataset.search || "";
            const matchKat = activeKat === "all" || cardKat === activeKat;
            const matchText = q === "" || blob.includes(q);
            const show = matchKat && matchText;

            card.hidden = !show;
            if (show) {
                reveal(card);
                visibleCount++;
            }
        });

        if (emptyBox) {
            emptyBox.hidden = visibleCount > 0;
        }
    };

    tabs.forEach((tab) => {
        tab.addEventListener("click", () => {
            activeKat = tab.dataset.lpmFilter || "all";
            tabs.forEach((btn) =>
                btn.classList.toggle("is-active", btn === tab),
            );
            applyFilter();
        });
    });

    searchInput?.addEventListener("input", applyFilter);
});
