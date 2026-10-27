import { $, $$, head } from '../../core/dom.js'

/* Markup identik dengan resources/views/components/landing/section/alasan.blade.php */
export default {
    render: ({ alasan }) => `<section class="sec alt" id="alasan"><div class="wrap">${head('Mengapa memilih kami', 'Belajar dengan arah yang jelas')}
      <div class="why rv"><div class="why-l" role="tablist">${alasan.map((x, i) => `<button role="tab" class="${i ? '' : 'on'}" data-why="${i}" aria-selected="${!i}">${x.t}</button>`).join('')}</div>
      <div class="why-r">${alasan.map((x, i) => `<div class="fade" data-panel="${i}"${i ? ' hidden' : ''}><h3>${x.t}</h3><p>${x.d}</p><ul>${x.p.map((p) => `<li>${p}</li>`).join('')}</ul></div>`).join('')}</div></div></div></section>`,

    init(root) {
        const l = $('.why-l', root)
        if (!l) return
        l.addEventListener('click', (e) => {
            const b = e.target.closest('button')
            if (!b) return
            $$('button', l).forEach((x) => {
                x.classList.toggle('on', x === b)
                x.setAttribute('aria-selected', x === b)
            })
            $$('.why-r [data-panel]', root).forEach((p) => (p.hidden = p.dataset.panel !== b.dataset.why))
        })
    }
}
