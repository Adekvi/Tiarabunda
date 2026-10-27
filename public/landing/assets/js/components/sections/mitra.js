export default {
    render: ({ mitra }) =>
        `<section class="mitra" aria-label="Mitra"><div class="track">${[0, 1].map(() => mitra.map((m) => `<span>${m}</span>`).join('')).join('')}</div></section>`
}
