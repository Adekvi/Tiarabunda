import { SITE } from '../data/site.js'
import topbar from '../components/layout/topbar.js'
import navbar from '../components/layout/navbar.js'
import footer from '../components/layout/footer.js'
import { effects } from './effects.js'

/* page = { data: 'nama-file-data' | null, sections: [komponen, ...] }
   Komponen = { render(data) => html, init?(root, data) }
   Dua mode:
   - SSR  (#app[data-ssr]): HTML sudah dari Blade -> hanya init() interaksi.
   - CSR  (default)       : render() semua komponen dari data/*.js, lalu init(). */
export async function mount(root, page) {
    const ssr = root.hasAttribute('data-ssr')
    const extra = !ssr && page.data ? (await import(`../data/${page.data}.js`)).default : {}
    const d = { site: SITE, ...extra }
    if (!ssr) {
        root.innerHTML =
            topbar.render(d) +
            navbar.render(d) +
            `<main id="main">${page.sections.map((s) => s.render(d)).join('')}</main>` +
            footer.render(d)
    }
    ;[topbar, navbar, ...page.sections, footer].forEach((c) => c.init?.(root, d))
    effects(root)
}
