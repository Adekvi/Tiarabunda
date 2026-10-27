import { $, $$ } from './dom.js'

const count = (el) => {
    const to = +el.dataset.to, st = performance.now()
    const f = (n) => {
        const p = Math.min((n - st) / 1400, 1)
        el.textContent = Math.round(to * (1 - Math.pow(1 - p, 3))).toLocaleString('id-ID')
        if (p < 1) requestAnimationFrame(f)
    }
    requestAnimationFrame(f)
}

export function effects(root) {
    const nav = $('#nav', root)
    const onScroll = () => nav?.classList.toggle('stuck', scrollY > 10)
    addEventListener('scroll', onScroll, { passive: true })
    onScroll()

    const io = new IntersectionObserver(
        (es) => es.forEach((e) => {
            if (!e.isIntersecting) return
            e.target.classList.add('in')
            $$('[data-to]', e.target).forEach(count)
            io.unobserve(e.target)
        }),
        { threshold: 0.15 }
    )
    $$('.rv', root).forEach((el) => io.observe(el))

    // konten dirender via JS, jadi lompat ke #hash dilakukan setelah render
    if (location.hash) document.getElementById(decodeURIComponent(location.hash.slice(1)))?.scrollIntoView()
}
