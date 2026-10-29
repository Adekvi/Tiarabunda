;(() => {
    const $ = (s, r = document) => r.querySelector(s)
    const $$ = (s, r = document) => [...r.querySelectorAll(s)]
    const body = document.body

    /* navbar */
    const nav = $('#nav'),
        burger = $('#burger')
    const onScroll = () => nav && nav.classList.toggle('stuck', scrollY > 10)
    addEventListener('scroll', onScroll, { passive: true })
    onScroll()
    burger?.addEventListener('click', () =>
        burger.setAttribute('aria-expanded', body.classList.toggle('menu-open'))
    )
    $$('.dd > button').forEach((b) =>
        b.addEventListener('click', () =>
            b.setAttribute(
                'aria-expanded',
                b.parentElement.classList.toggle('open')
            )
        )
    )
    $$('#menu a').forEach((l) =>
        l.addEventListener('click', () => body.classList.remove('menu-open'))
    )

    /* pencarian */
    ;(() => {
        const navEl = document.getElementById('nav')
        const sf = document.getElementById('srch')
        const sb = document.getElementById('srch-b')
        const sq = document.getElementById('q')
        if (!sf || !sb || !sq) return

        const set = (o) => {
            sf.classList.toggle('open', o)
            navEl?.classList.toggle('searching', o)
            sb.setAttribute('aria-expanded', o)
            sb.setAttribute(
                'aria-label',
                o ? 'Tutup pencarian' : 'Buka pencarian'
            )
            if (o) {
                sq.focus({ preventScroll: true })
            } else {
                sq.value = ''
                sq.blur()
            }
        }

        // keadaan awal (halaman hasil pencarian sudah terbuka dari server)
        if (sf.classList.contains('open')) {
            navEl?.classList.add('searching')
            sb.setAttribute('aria-expanded', 'true')
            sb.setAttribute('aria-label', 'Tutup pencarian')
        }

        sb.addEventListener('click', () => set(!sf.classList.contains('open')))

        addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && sf.classList.contains('open')) {
                set(false)
                sb.focus()
            }
        })

        document.addEventListener('click', (e) => {
            if (
                !sf.contains(e.target) &&
                sf.classList.contains('open') &&
                !sq.value.trim()
            )
                set(false)
        })
    })()

    /* alasan (tab) */
    const why = $('.why-l')
    why?.addEventListener('click', (e) => {
        const b = e.target.closest('button')
        if (!b) return
        $$('button', why).forEach((x) => {
            x.classList.toggle('on', x === b)
            x.setAttribute('aria-selected', x === b)
        })
        $$('.why-r [data-panel]').forEach(
            (p) => (p.hidden = p.dataset.panel !== b.dataset.why)
        )
    })

    /* prodi (filter) */
    const tabs = $('#tabs')
    tabs?.addEventListener('click', (e) => {
        const b = e.target.closest('button')
        if (!b) return
        $$('button', tabs).forEach((x) => x.classList.toggle('on', x === b))
        const f = +b.dataset.f
        let n = 0
        $$('#grid .pr').forEach((c) => {
            const show = f < 0 || +c.dataset.f === f
            c.hidden = !show
            if (show) c.style.setProperty('--i', n++)
        })
    })

    /* faq */
    $$('.qa > button').forEach((b) =>
        b.addEventListener('click', () => {
            const q = b.parentElement,
                o = !q.classList.contains('open')
            $$('.qa').forEach((x) => {
                x.classList.remove('open')
                x.firstElementChild.setAttribute('aria-expanded', false)
            })
            q.classList.toggle('open', o)
            b.setAttribute('aria-expanded', o)
        })
    )

    /* hero: form PMB + ticker */
    const form = $('#pmb')
    form?.addEventListener('submit', (e) => {
        e.preventDefault()
        const em = $('#em'),
            m = $('#msg'),
            ok = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(em.value)
        m.className = 'msg show ' + (ok ? 'ok' : 'err')
        m.textContent = ok
            ? 'Terima kasih! Tautan pendaftaran akan dikirim ke email Anda.'
            : 'Format email belum benar. Contoh: nama@email.com'
        if (ok) em.value = '' // TODO: kirim ke endpoint PMB
    })
    const tk = $('#tick')
    if (tk) {
        const items = JSON.parse(tk.dataset.items || '[]')
        let t = 0
        if (items.length > 1)
            setInterval(() => {
                tk.classList.add('out')
                setTimeout(() => {
                    t = (t + 1) % items.length
                    tk.textContent = items[t]
                    tk.classList.remove('out')
                }, 350)
            }, 3800)
    }

    /* reveal + counter */
    const count = (el) => {
        const to = +el.dataset.to,
            st = performance.now()
        const f = (n) => {
            const p = Math.min((n - st) / 1400, 1)
            el.textContent = Math.round(
                to * (1 - Math.pow(1 - p, 3))
            ).toLocaleString('id-ID')
            if (p < 1) requestAnimationFrame(f)
        }
        requestAnimationFrame(f)
    }
    const reveal = (el) => {
        el.classList.add('in')
        $$('[data-to]', el).forEach(count)
    }
    if ('IntersectionObserver' in window) {
        const io = new IntersectionObserver(
            (es) =>
                es.forEach((e) => {
                    if (!e.isIntersecting) return
                    reveal(e.target)
                    io.unobserve(e.target)
                }),
            { threshold: 0.15 }
        )
        $$('.rv').forEach((el) => io.observe(el))
    } else {
        $$('.rv').forEach(reveal)
    }
})()
