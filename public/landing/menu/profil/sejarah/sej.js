document.addEventListener('DOMContentLoaded', () => {
    const items = document.querySelectorAll('.sej__item')
    if (!items.length || !('IntersectionObserver' in window)) return

    const io = new IntersectionObserver(
        (entries) => {
            entries.forEach((e) => {
                if (e.isIntersecting) {
                    e.target.classList.add('rv', 'in')
                    io.unobserve(e.target)
                }
            })
        },
        { threshold: 0.15, rootMargin: '0px 0px -40px 0px' }
    )

    items.forEach((el) => {
        el.classList.add('rv')
        io.observe(el)
    })
})
