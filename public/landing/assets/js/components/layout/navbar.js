import { $, $$, a } from '../../core/dom.js'

export default {
    render: ({
        site
    }) => `<header class="nav" id="nav"><div class="wrap nav-in">
      <a class="brand" href="/">
      <img src="{{ asset('landing/assets/img/logo.webp') }}" alt="" width="48" height="48" />
                    <span><b>${site.nama}</b><small>${site.kota}</small></span></a>
      <nav class="menu" id="menu" aria-label="Menu utama">${site.menu
          .map((m) =>
              m.sub
                  ? `<div class="dd"><button type="button" aria-expanded="false">${m.t}<i></i></button><div class="dd-p">${m.sub.map((s) => a(s[1], s[0])).join('')}</div></div>`
                  : a(m.href, m.t, 'pl')
          )
          .join('')}</nav>
      <a class="btn sm" href="${site.pmb}">Daftar PMB</a>
      <button class="burger" id="burger" type="button" aria-label="Buka menu" aria-expanded="false"><span></span></button></div></header>`,

    init(root) {
        const burger = $('#burger', root),
            body = document.body
        burger.addEventListener('click', () => {
            const o = body.classList.toggle('menu-open')
            burger.setAttribute('aria-expanded', o)
        })
        $$('.dd > button', root).forEach((b) =>
            b.addEventListener('click', () => {
                const o = b.parentElement.classList.toggle('open')
                b.setAttribute('aria-expanded', o)
            })
        )
        $$('#menu a, #nav .btn', root).forEach((l) =>
            l.addEventListener('click', () =>
                body.classList.remove('menu-open')
            )
        )
        // tandai menu aktif sesuai halaman
        const here = location.pathname.replace(/\/$/, '') || '/'
        $$('#menu a', root).forEach((l) => {
            if (l.getAttribute('href') !== '#' && l.pathname === here) {
                l.classList.add('active')
                l.closest('.dd')?.classList.add('active')
            }
        })
    }
}
