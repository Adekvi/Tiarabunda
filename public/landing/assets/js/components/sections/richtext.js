export default {
    render: ({ blok }) => `<section class="sec"><div class="wrap doc">
      <aside class="toc" aria-label="Daftar isi">${blok.map((b) => `<a href="#${b.id}">${b.t}</a>`).join('')}</aside>
      <div class="prose">${blok.map((b) => `<article id="${b.id}" class="rv"><h2>${b.t}</h2>${(b.isi || []).map((p) => `<p>${p}</p>`).join('')}${b.daftar ? `<ul>${b.daftar.map((x) => `<li>${x}</li>`).join('')}</ul>` : ''}</article>`).join('')}</div></div></section>`
}
