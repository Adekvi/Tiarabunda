import { a, head, arrow } from '../../core/dom.js'

export default {
    render: ({ jalur }) => `<section class="sec alt" id="jalur"><div class="wrap">${head('Jalur & beasiswa', 'Pilih jalur masuk yang sesuai')}
      <div class="cards rv">${jalur.map((j) => `<article class="card"><h3>${j[0]}</h3><p>${j[1]}</p>${a('/pmb', 'Lihat detail ' + arrow, 'more')}</article>`).join('')}</div></div></section>`
}
