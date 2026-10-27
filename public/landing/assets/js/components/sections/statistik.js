export default {
    render: ({ statistik }) =>
        `<section class="stat"><div class="wrap rv">${statistik.map((s) => `<div><b><span data-to="${s[0]}">0</span>${s[1]}</b><small>${s[2]}</small></div>`).join('')}</div></section>`
}
