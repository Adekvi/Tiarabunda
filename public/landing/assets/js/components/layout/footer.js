import { a } from '../../core/dom.js'

export default {
    render: ({
        site
    }) => `<footer class="foot"><div class="wrap fg"><div><div class="brand w"><span class="mark">PTB</span><span><b>${site.nama}</b><small>${site.kota}</small></span></div>
      <p>${site.kontak.alamat}<br>${site.kontak.telp} · ${site.kontak.email}</p></div>
      ${site.footer.map((c) => `<div><h4>${c[0]}</h4>${c[1].map((l) => a(l[1], l[0])).join('')}</div>`).join('')}</div>
      <div class="wrap copy">© ${new Date().getFullYear()} ${site.nama}. Hak cipta dilindungi.</div></footer>`
}
