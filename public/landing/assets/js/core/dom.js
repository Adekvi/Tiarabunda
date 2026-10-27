export const $ = (s, r = document) => r.querySelector(s)
export const $$ = (s, r = document) => [...r.querySelectorAll(s)]
export const a = (h, t, c = '') => `<a class="${c}" href="${h}">${t}</a>`
export const arrow = `<svg class="ar" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg>`
export const head = (k, t, p = '') =>
    `<div class="head rv"><p class="kick">${k}</p><h2>${t}</h2>${p ? `<p class="lead">${p}</p>` : ''}</div>`
