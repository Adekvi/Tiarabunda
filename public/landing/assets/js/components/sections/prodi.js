import { $, $$, head, arrow } from '../../core/dom.js'

/* Markup identik dengan resources/views/components/landing/section/prodi.blade.php */
export default {
    render: ({ fakultas, prodi }) => `<section class="sec" id="prodi"><div class="wrap">${head('Program studi', 'Temukan jurusan yang cocok untukmu', `${prodi.length} program studi di ${fakultas.length} fakultas.`)}
      <div class="tabs rv" id="tabs" role="tablist">${['Semua', ...fakultas].map((f, i) => `<button role="tab" class="${i ? '' : 'on'}" data-f="${i - 1}">${f}</button>`).join('')}</div>
      <div class="grid" id="grid">${prodi.map((p, i) => `<a class="pr" data-f="${p[2]}" style="--i:${i}" href="/fakultas"><span class="lv">${p[1]}</span><h3>${p[0]}</h3><p>${fakultas[p[2]]}</p><span class="more">Pelajari ${arrow}</span></a>`).join('')}</div></div></section>`,

    init(root) {
        const tabs = $('#tabs', root)
        if (!tabs) return
        tabs.addEventListener('click', (e) => {
            const b = e.target.closest('button')
            if (!b) return
            $$('button', tabs).forEach((x) => x.classList.toggle('on', x === b))
            const f = +b.dataset.f
            let n = 0
            $$('#grid .pr', root).forEach((c) => {
                const show = f < 0 || +c.dataset.f === f
                c.hidden = !show
                if (show) c.style.setProperty('--i', n++) // ulangi animasi pop berurutan
            })
        })
    }
}
