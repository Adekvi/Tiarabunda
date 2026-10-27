import { a } from '../../core/dom.js'

export default {
    render: ({ site }) =>
        `<div class="top"><div class="wrap"><span>${site.alamat}</span><nav aria-label="Portal">${site.portal.map((p) => a(p[1], p[0])).join('')}</nav></div></div>`
}
