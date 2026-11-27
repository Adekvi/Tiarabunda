(function () {
    const modal = document.getElementById("tstConfirmModal");
    if (!modal) return;

    const titleEl = document.getElementById("tstModalTitle");
    const msgEl = document.getElementById("tstModalMessage");
    const formEl = document.getElementById("tstConfirmForm");
    const methodEl = document.getElementById("tstConfirmMethod");
    const submitBtn = document.getElementById("tstConfirmSubmit");

    function closeModal() {
        modal.hidden = true;
    }

    document.querySelectorAll("[data-confirm-open]").forEach((btn) => {
        btn.addEventListener("click", () => {
            const title =
                btn.getAttribute("data-confirm-title") || "Konfirmasi Aksi";
            const message =
                btn.getAttribute("data-confirm-message") ||
                "Apakah Anda yakin?";
            const action = btn.getAttribute("data-confirm-action") || "";
            const method = btn.getAttribute("data-confirm-method") || "DELETE";
            const label =
                btn.getAttribute("data-confirm-btn") || "Ya, Lanjutkan";
            const variant =
                btn.getAttribute("data-confirm-variant") || "danger";

            if (titleEl) titleEl.textContent = title;
            if (msgEl) msgEl.textContent = message;
            if (formEl) formEl.setAttribute("action", action);
            if (methodEl) methodEl.value = method;

            if (submitBtn) {
                submitBtn.textContent = label;
                submitBtn.className =
                    variant === "primary"
                        ? "lok-btn lok-btn--primary"
                        : "lok-btn lok-btn--danger";
            }

            modal.hidden = false;
        });
    });

    modal.querySelectorAll("[data-confirm-close]").forEach((el) => {
        el.addEventListener("click", closeModal);
    });

    document.addEventListener("keydown", (e) => {
        if (e.key === "Escape" && !modal.hidden) {
            closeModal();
        }
    });
})();
