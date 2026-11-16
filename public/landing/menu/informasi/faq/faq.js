document.addEventListener('DOMContentLoaded', function () {
    const faqItems = document.querySelectorAll('.faq__item')

    faqItems.forEach((item) => {
        const trigger = item.querySelector('.faq__trigger')
        const answer = item.querySelector('.faq__answer')

        trigger.addEventListener('click', () => {
            const isExpanded = trigger.getAttribute('aria-expanded') === 'true'

            // Opsional: Tutup item lain jika ingin mode accordion murni (hanya 1 terbuka)
            // faqItems.forEach(otherItem => {
            //     if (otherItem !== item) {
            //         otherItem.querySelector('.faq__trigger').setAttribute('aria-expanded', 'false');
            //         otherItem.querySelector('.faq__answer').hidden = true;
            //         otherItem.classList.remove('faq__item--active');
            //     }
            // });

            // Toggle current
            trigger.setAttribute('aria-expanded', !isExpanded)
            answer.hidden = isExpanded
            item.classList.toggle('faq__item--active', !isExpanded)
        })
    })
})
