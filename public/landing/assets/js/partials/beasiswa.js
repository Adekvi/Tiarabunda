window.SiteApp?.register("beasiswa", ({ $, $$, reveal }) => {
    const root = $("[data-bsw-root]");
    if (!root) return;

    const searchInput = $("[data-bsw-search]", root);
    const cards = $$("[data-bsw-item]", root);
    const emptyBox = $("[data-bsw-empty-search]", root);

    if (!searchInput || !cards.length) return;

    searchInput.addEventListener("input", () => {
        const q = searchInput.value.trim().toLowerCase();
        let visible = 0;

        cards.forEach((card) => {
            const blob = card.dataset.search || "";
            const match = q === "" || blob.includes(q);
            card.hidden = !match;

            if (match) {
                reveal(card);
                visible++;
            }
        });

        if (emptyBox) {
            emptyBox.hidden = visible > 0;
        }
    });
});
