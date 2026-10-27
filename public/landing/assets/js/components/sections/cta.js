import { a } from '../../core/dom.js'

export default {
    render: ({ site }) => `<section class="cta"><div class="wrap rv"><h2>Siap mulai kuliah di ${site.nama}?</h2><p>Tim admisi kami siap membantu lewat WhatsApp.</p>
      <div class="row"><a class="btn" href="${site.pmb}">Daftar PMB</a>${a(site.kontak.wa, 'Konsultasi via WhatsApp', 'btn ghost w')}</div></div></section>`
}
