import { $$, head } from '../../core/dom.js'

export default {
    render: ({ faq }) => `<section class="sec" id="faq"><div class="wrap faq-in">${head('FAQ', 'Pertanyaan yang sering diajukan')}
      <div class="faq rv">${faq.map((q, i) => `<div class="qa"><button type="button" aria-expanded="false" aria-controls="qa${i}">${q[0]}<i></i></button><div class="qa-b" id="qa${i}"><p>${q[1]}</p></div></div>`).join('')}</div></div></section>`,

    init(root) {
        $$('.qa > button', root).forEach((b) =>
            b.addEventListener('click', () => {
                const q = b.parentElement, o = !q.classList.contains('open')
                $$('.qa', root).forEach((x) => {
                    x.classList.remove('open')
                    x.firstElementChild.setAttribute('aria-expanded', false)
                })
                q.classList.toggle('open', o)
                b.setAttribute('aria-expanded', o)
            })
        )
    }
}
