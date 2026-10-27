import { head } from '../../core/dom.js'

export default {
    render: ({ berita }) => {
        const [f, ...r] = berita
        return `<section class="sec" id="berita"><div class="wrap">${head('Berita & kegiatan', 'Kabar terbaru dari kampus')}
      <div class="news rv"><article class="nf"><span class="tag">${f.j}</span><h3>${f.t}</h3><time>${f.tgl}</time></article>
      <div class="nl">${r.map((n) => `<article><span class="tag">${n.j}</span><h3>${n.t}</h3><time>${n.tgl}</time></article>`).join('')}</div></div></div></section>`
    }
}
