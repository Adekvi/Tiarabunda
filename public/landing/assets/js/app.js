/* Komponen ala React: setiap fungsi menerima data dan mengembalikan HTML.
   State sederhana (tab prodi, panel alasan, FAQ) ditangani lewat event delegation. */
;(() => {
    const S = window.SITE,
        $ = (s, r = document) => r.querySelector(s),
        $$ = (s, r = document) => [...r.querySelectorAll(s)]
    const a = (h, t, c = '') => `<a class="${c}" href="${h}">${t}</a>`
    const arrow = `<svg class="ar" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg>`
    const head = (k, t, p = '') =>
        `<div class="head rv"><p class="kick">${k}</p><h2>${t}</h2>${p ? `<p class="lead">${p}</p>` : ''}</div>`

    const C = {
        top: () =>
            `<div class="top"><div class="wrap"><span>${S.alamat}</span><nav aria-label="Portal">${S.portal.map((p) => a(p[1], p[0])).join('')}</nav></div></div>`,

        nav: () => `<header class="nav" id="nav"><div class="wrap nav-in">
      <a class="brand" href="#top"><span class="mark">PTB</span><span><b>${S.nama}</b><small>${S.kota}</small></span></a>
      <nav class="menu" id="menu" aria-label="Menu utama">${S.menu
          .map((m) =>
              m.sub
                  ? `<div class="dd"><button type="button" aria-expanded="false">${m.t}<i></i></button><div class="dd-p">${m.sub.map((s) => a(s[1], s[0])).join('')}</div></div>`
                  : a(m.href, m.t, 'pl')
          )
          .join('')}</nav>
      <a class="btn sm" href="#pmb">Daftar PMB</a>
      <button class="burger" id="burger" type="button" aria-label="Buka menu" aria-expanded="false"><span></span></button></div></header>`,

        hero: () => `<section class="hero" id="top"><div class="wrap hero-in">
      <div class="hero-t">
        <p class="tick" aria-live="off"><span class="dot"></span><span id="tick">${S.ticker[0]}</span></p>
        <h1>Kuliah yang langsung membawamu ke dunia kerja</h1>
        <p class="lead">Politeknik Tiara Bunda menyiapkan lulusan terampil lewat praktik, magang, dan pendampingan karier di jantung Kota Depok.</p>
        <div class="row"><a class="btn" href="#pmb">Daftar sekarang</a><a class="btn ghost" href="#prodi">Lihat program studi ${arrow}</a></div>
      </div>
      <div class="hero-v" aria-hidden="true"><div class="arch"><div class="sun"></div><div class="hill"></div></div>
        <span class="chip c1">Praktik di laboratorium</span><span class="chip c2">Magang industri</span><span class="chip c3">Kelas pagi & sore</span></div>
      <form class="pmb" id="pmb" novalidate><h2>Mulai pendaftaran</h2><p>Masukkan email aktif untuk membuat akun PMB.</p>
        <label for="em" class="sr">Email</label><input id="em" type="email" placeholder="nama@email.com" autocomplete="email" required>
        <button class="btn" type="submit">Buat akun PMB</button><p class="msg" id="msg" role="status"></p></form>
    </div></section>`,

        mitra: () =>
            `<section class="mitra" aria-label="Mitra"><div class="track">${[0, 1].map(() => S.mitra.map((m) => `<span>${m}</span>`).join('')).join('')}</div></section>`,

        sambutan:
            () => `<section class="sec sam"><div class="wrap sam-in rv"><div class="ava">${S.rektor.nama[0]}</div>
      <div><p class="kick">Sambutan pimpinan</p><blockquote>“${S.rektor.kutipan}”</blockquote><p class="who"><b>${S.rektor.nama}</b><br>${S.rektor.jabatan}</p></div></div></section>`,

        alasan: () => `<section class="sec alt" id="alasan"><div class="wrap">${head('Mengapa memilih kami', 'Belajar dengan arah yang jelas')}
      <div class="why rv"><div class="why-l" role="tablist">${S.alasan.map((x, i) => `<button role="tab" class="${i ? '' : 'on'}" data-why="${i}" aria-selected="${!i}">${x.t}</button>`).join('')}</div>
      <div class="why-r" id="why-r"></div></div></div></section>`,

        stat: () =>
            `<section class="stat"><div class="wrap rv">${S.statistik.map((s) => `<div><b><span data-to="${s[0]}">0</span>${s[1]}</b><small>${s[2]}</small></div>`).join('')}</div></section>`,

        prodi: () => `<section class="sec" id="prodi"><div class="wrap">${head('Program studi', 'Temukan jurusan yang cocok untukmu', `${S.prodi.length} program studi di ${S.fakultas.length} fakultas.`)}
      <div class="tabs rv" id="tabs" role="tablist">${['Semua', ...S.fakultas].map((f, i) => `<button role="tab" class="${i ? '' : 'on'}" data-f="${i - 1}">${f}</button>`).join('')}</div>
      <div class="grid" id="grid"></div></div></section>`,

        fasil: () => {
            const set = (hide) =>
                S.fasilitas
                    .map(
                        (f) =>
                            `<figure class="tile"${hide ? ' aria-hidden="true"' : ''}><img src="${f[1]}" alt="${hide ? '' : f[0]}" loading="lazy" onerror="this.remove()"><figcaption>${f[0]}</figcaption></figure>`
                    )
                    .join('')
            return `<section class="sec alt"><div class="wrap">${head('Fasilitas', 'Ruang belajar yang mendukung praktik')}</div>
      <div class="gal rv" aria-label="Galeri fasilitas"><div class="gal-track">${set(false)}${set(true)}</div></div></section>`
        },

        berita: () => {
            const [f, ...r] = S.berita
            return `<section class="sec" id="berita"><div class="wrap">${head('Berita & kegiatan', 'Kabar terbaru dari kampus')}
      <div class="news rv"><article class="nf"><span class="tag">${f.j}</span><h3>${f.t}</h3><time>${f.tgl}</time></article>
      <div class="nl">${r.map((n) => `<article><span class="tag">${n.j}</span><h3>${n.t}</h3><time>${n.tgl}</time></article>`).join('')}</div></div></div></section>`
        },

        jalur: () => `<section class="sec alt" id="jalur"><div class="wrap">${head('Jalur & beasiswa', 'Pilih jalur masuk yang sesuai')}
      <div class="cards rv">${S.jalur.map((j) => `<article class="card"><h3>${j[0]}</h3><p>${j[1]}</p>${a('pages/pmb.html', 'Lihat detail ' + arrow, 'more')}</article>`).join('')}</div></div></section>`,

        faq: () => `<section class="sec" id="faq"><div class="wrap faq-in">${head('FAQ', 'Pertanyaan yang sering diajukan')}
      <div class="faq rv">${S.faq.map((q, i) => `<div class="qa"><button type="button" aria-expanded="false" aria-controls="qa${i}">${q[0]}<i></i></button><div class="qa-b" id="qa${i}"><p>${q[1]}</p></div></div>`).join('')}</div></div></section>`,

        cta: () => `<section class="cta"><div class="wrap rv"><h2>Siap mulai kuliah di Politeknik Tiara Bunda?</h2><p>Tim admisi kami siap membantu lewat WhatsApp.</p>
      <div class="row"><a class="btn" href="#pmb">Daftar PMB</a>${a(S.kontak.wa, 'Konsultasi via WhatsApp', 'btn ghost w')}</div></div></section>`,

        foot: () => `<footer class="foot"><div class="wrap fg"><div><div class="brand w"><span class="mark">PTB</span><span><b>${S.nama}</b><small>${S.kota}</small></span></div>
      <p>${S.kontak.alamat}<br>${S.kontak.telp} · ${S.kontak.email}</p></div>
      ${S.footer.map((c) => `<div><h4>${c[0]}</h4>${c[1].map((l) => a(l[1], l[0])).join('')}</div>`).join('')}</div>
      <div class="wrap copy">© ${new Date().getFullYear()} ${S.nama}. Hak cipta dilindungi.</div></footer>`
    }

    const whyHTML = (i) => {
        const x = S.alasan[i]
        return `<div class="fade"><h3>${x.t}</h3><p>${x.d}</p><ul>${x.p.map((p) => `<li>${p}</li>`).join('')}</ul></div>`
    }
    const gridHTML = (f) =>
        S.prodi
            .filter((p) => f < 0 || p[2] === f)
            .map(
                (p, i) =>
                    `<a class="pr" style="--i:${i}" href="pages/fakultas.html"><span class="lv">${p[1]}</span><h3>${p[0]}</h3><p>${S.fakultas[p[2]]}</p><span class="more">Pelajari ${arrow}</span></a>`
            )
            .join('')

    // ---- render ----
    $('#app').innerHTML =
        `${C.top()}${C.nav()}<main id="main">${['hero', 'mitra', 'sambutan', 'alasan', 'stat', 'prodi', 'fasil', 'berita', 'jalur', 'faq', 'cta'].map((k) => C[k]()).join('')}</main>${C.foot()}`
    $('#why-r').innerHTML = whyHTML(0)
    $('#grid').innerHTML = gridHTML(-1)

    // ---- interaksi ----
    const nav = $('#nav'),
        burger = $('#burger')
    const onScroll = () => nav.classList.toggle('stuck', scrollY > 10)
    addEventListener('scroll', onScroll, { passive: true })
    onScroll()

    burger.addEventListener('click', () => {
        const o = document.body.classList.toggle('menu-open')
        burger.setAttribute('aria-expanded', o)
    })
    $$('.dd > button').forEach((b) =>
        b.addEventListener('click', () => {
            const d = b.parentElement,
                o = d.classList.toggle('open')
            b.setAttribute('aria-expanded', o)
        })
    )
    $$("#menu a, .btn[href^='#']").forEach((l) =>
        l.addEventListener('click', () =>
            document.body.classList.remove('menu-open')
        )
    )

    $('.why-l').addEventListener('click', (e) => {
        const b = e.target.closest('button')
        if (!b) return
        $$('.why-l button').forEach((x) => {
            x.classList.toggle('on', x === b)
            x.setAttribute('aria-selected', x === b)
        })
        $('#why-r').innerHTML = whyHTML(+b.dataset.why)
    })
    $('#tabs').addEventListener('click', (e) => {
        const b = e.target.closest('button')
        if (!b) return
        $$('#tabs button').forEach((x) => x.classList.toggle('on', x === b))
        $('#grid').innerHTML = gridHTML(+b.dataset.f)
    })
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

    $('#pmb').addEventListener('submit', (e) => {
        e.preventDefault()
        const em = $('#em'),
            m = $('#msg'),
            ok = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(em.value)
        m.className = 'msg show ' + (ok ? 'ok' : 'err')
        m.textContent = ok
            ? 'Terima kasih! Tautan pendaftaran akan dikirim ke email Anda.'
            : 'Format email belum benar. Contoh: nama@email.com'
        if (ok) em.value = '' // TODO: kirim ke endpoint PMB (Laravel) di sini
    })

    let t = 0
    const tk = $('#tick')
    setInterval(() => {
        tk.classList.add('out')
        setTimeout(() => {
            t = (t + 1) % S.ticker.length
            tk.textContent = S.ticker[t]
            tk.classList.remove('out')
        }, 350)
    }, 3800)

    // ---- reveal & counter saat masuk viewport ----
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
    const io = new IntersectionObserver(
        (es) =>
            es.forEach((e) => {
                if (!e.isIntersecting) return
                e.target.classList.add('in')
                $$('[data-to]', e.target).forEach(count)
                io.unobserve(e.target)
            }),
        { threshold: 0.15 }
    )
    $$('.rv').forEach((el) => io.observe(el))
})()
