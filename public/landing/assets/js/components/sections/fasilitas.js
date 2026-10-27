import { head } from '../../core/dom.js'

export default {
    render: ({ fasilitas }) => {
        const set = (hide) =>
            fasilitas
                .map((f) => `<figure class="tile"${hide ? ' aria-hidden="true"' : ''}><img src="/landing/${f[1]}" alt="${hide ? '' : f[0]}" loading="lazy" onerror="this.remove()"><figcaption>${f[0]}</figcaption></figure>`)
                .join('')
        return `<section class="sec alt"><div class="wrap">${head('Fasilitas', 'Ruang belajar yang mendukung praktik')}</div>
      <div class="gal rv" aria-label="Galeri fasilitas"><div class="gal-track">${set(false)}${set(true)}</div></div></section>`
    }
}
