/* Dashboard superadmin: React (UMD) + htm, tanpa build.
   Data = mock; sambungkan ke endpoint Laravel pada bagian bertanda TODO. */
;(() => {
    const { useState, useEffect } = React
    const h = htm.bind(React.createElement)
    const rootEl = document.getElementById('root')
    const BRAND = rootEl.dataset.brand,
        LOGO = rootEl.dataset.logo

    const st = {
        get: (k, d) => {
            try {
                return localStorage.getItem('d-' + k) ?? d
            } catch (e) {
                return d
            }
        },
        set: (k, v) => {
            try {
                localStorage.setItem('d-' + k, v)
            } catch (e) {}
        }
    }
    const toast = (m) => {
        const t = document.createElement('div')
        t.className = 'toast'
        t.textContent = m
        document.body.append(t)
        setTimeout(() => t.remove(), 2600)
    }

    /* ---------- ikon ---------- */
    const P = {
        home: 'M3 11l9-8 9 8M5 10v10h14V10',
        bldg: 'M4 21V4h10v17M14 9h6v12M8 8h2M8 12h2M8 16h2',
        book: 'M4 4h12a4 4 0 014 4v12H8a4 4 0 01-4-4z',
        layers: 'M12 3l9 5-9 5-9-5zM3 13l9 5 9-5',
        news: 'M5 4h14v16H5zM8 8h8M8 12h8M8 16h5',
        users: 'M9 11a4 4 0 100-8 4 4 0 000 8zM2 21a7 7 0 0114 0M17 4a4 4 0 010 7M22 21a7 7 0 00-4-6',
        user: 'M12 12a4 4 0 100-8 4 4 0 000 8zM4 21a8 8 0 0116 0',
        cal: 'M4 6h16v14H4zM4 10h16M8 3v4M16 3v4',
        img: 'M3 5h18v14H3zM3 16l5-5 4 4 3-3 6 6',
        help: 'M12 3a9 9 0 100 18 9 9 0 000-18zM9.5 9a2.5 2.5 0 115 0c0 2-2.5 2-2.5 4M12 17h.01',
        star: 'M12 3l3 6 6 1-4.5 4.5 1 6.5-5.5-3-5.5 3 1-6.5L3 10l6-1z',
        warn: 'M12 3l10 18H2zM12 10v5M12 18h.01',
        grid: 'M4 4h7v7H4zM13 4h7v7h-7zM4 13h7v7H4zM13 13h7v7h-7z',
        list: 'M8 6h13M8 12h13M8 18h13M3 6h.01M3 12h.01M3 18h.01',
        sun: 'M12 8a4 4 0 100 8 4 4 0 000-8zM12 2v2M12 20v2M2 12h2M20 12h2M5 5l1.5 1.5M17.5 17.5L19 19M5 19l1.5-1.5M17.5 6.5L19 5',
        moon: 'M20 14A8 8 0 0110 4a8 8 0 1010 10z',
        menu: 'M4 6h16M4 12h16M4 18h16',
        chev: 'M6 9l6 6 6-6',
        out: 'M15 4h4v16h-4M10 8l-4 4 4 4M6 12h10',
        plus: 'M12 5v14M5 12h14',
        search: 'M11 4a7 7 0 100 14 7 7 0 000-14zM21 21l-5-5',
        chart: 'M4 20V10M10 20V4M16 20v-8M22 20H2',
        width: 'M3 5v14M21 5v14M7 12h10M10 9l-3 3 3 3M14 9l3 3-3 3',
        left: 'M15 6l-6 6 6 6',
        right: 'M9 6l6 6-6 6',
        trash: 'M4 7h16M9 7V4h6v3M6 7l1 13h10l1-13',
        bell: 'M6 8a6 6 0 1112 0c0 7 3 8 3 8H3s3-1 3-8M10 21a2 2 0 004 0'
    }
    const Ic = ({ n, s = 18, c = '' }) =>
        h`<svg class=${c} width=${s} height=${s} viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d=${P[n]}/></svg>`

    /* ---------- menu (mengikuti landing page) ---------- */
    const NAV = [
        { t: 'Dashboard', i: 'home', to: '/' },
        { sep: 'Konten Landing' },
        {
            t: 'Profil',
            i: 'bldg',
            sub: [
                { t: 'Sejarah', to: '/konten/sejarah' },
                { t: 'Visi & Misi', to: '/konten/visi' },
                { t: 'Pimpinan Universitas', to: '/konten/pimpinan' },
                { t: 'Struktur Organisasi', to: '/konten/struktur' }
            ]
        },
        {
            t: 'Pendidikan',
            i: 'book',
            sub: [
                {
                    t: 'Program Pendidikan',
                    sub: [
                        {
                            t: 'Teknologi & Informatika',
                            to: '/konten/prodi-ti'
                        },
                        { t: 'Bisnis & Manajemen', to: '/konten/prodi-bm' },
                        { t: 'Kesehatan', to: '/konten/prodi-kes' }
                    ]
                },
                { t: 'Penerimaan Mahasiswa Baru', to: '/konten/pmb' },
                { t: 'Beasiswa', to: '/konten/beasiswa' }
            ]
        },
        {
            t: 'Layanan',
            i: 'layers',
            sub: [
                { t: 'Perpustakaan', to: '/konten/pustaka' },
                { t: 'Kemahasiswaan', to: '/konten/mhs' },
                { t: 'Pusat Karier', to: '/konten/karier' },
                { t: 'Dokumen', to: '/konten/dokumen' },
                { t: 'Fasilitas', to: '/galeri' }
            ]
        },
        {
            t: 'Informasi',
            i: 'news',
            sub: [
                { t: 'Berita Terbaru', to: '/konten/berita' },
                { t: 'Kegiatan Kampus', to: '/kalender' },
                { t: 'FAQ', to: '/faq' }
            ]
        },
        { sep: 'Sistem' },
        { t: 'Pengguna', i: 'users', to: '/pengguna' },
        { t: 'Galeri', i: 'img', to: '/galeri' },
        { t: 'Kalender', i: 'cal', to: '/kalender' },
        { t: 'FAQ', i: 'help', to: '/faq' },
        { t: 'Showcase', i: 'star', to: '/showcase' },
        {
            t: 'Halaman Error',
            i: 'warn',
            sub: [
                { t: '403 Dilarang', to: '/403' },
                { t: '404 Tidak ditemukan', to: '/404' },
                { t: '500 Server error', to: '/500' }
            ]
        }
    ]

    const TITLES = {}
    ;(function walk(a) {
        a.forEach((n) => {
            if (n.to) TITLES[n.to] = n.t
            if (n.sub) walk(n.sub)
        })
    })(NAV)
    const active = (n, p) =>
        n.to ? n.to === p : (n.sub || []).some((s) => active(s, p))

    const Sep = ({ t }) => h`<p class="sep">${t}</p>`

    const NavLink = ({ n, on }) =>
        h`<a class=${'ni' + (on ? ' on' : '')} href=${'#' + n.to}>${n.i && h`<${Ic} n=${n.i}/>`}<span>${n.t}</span></a>`

    const NavGroup = ({ n, path }) => {
        const on = active(n, path)
        const [open, setOpen] = useState(on)
        useEffect(() => {
            if (on) setOpen(true)
        }, [on])
        return h`<div class=${'grp' + (open ? ' open' : '')}>
    <button class=${'ni' + (on ? ' on' : '')} aria-expanded=${open} onClick=${() => setOpen(!open)}>
      ${n.i && h`<${Ic} n=${n.i}/>`}<span>${n.t}</span><${Ic} n="chev" s=${16} c="ch"/></button>
    <div class="sub"><div>${n.sub.map((s) => h`<${NavItem} key=${s.t} n=${s} path=${path}/>`)}</div></div></div>`
    }

    const NavItem = ({ n, path }) => {
        if (n.sep) return h`<${Sep} t=${n.sep}/>`
        if (n.sub) return h`<${NavGroup} n=${n} path=${path}/>`
        return h`<${NavLink} n=${n} on=${active(n, path)}/>`
    }

    /* ---------- util + data mock ---------- */
    const wait = (v, ms = 700) => new Promise((r) => setTimeout(() => r(v), ms))
    const useLoad = (fn) => {
        const [d, setD] = useState(null)
        useEffect(() => {
            let ok = true
            fn().then((x) => ok && setD(x))
            return () => {
                ok = false
            }
        }, [])
        return d
    }
    const Sk = () =>
        h`<div class="card sk" aria-busy="true"><i style=${{ width: '40%' }}></i><i></i><i style=${{ width: '80%' }}></i><i style=${{ width: '60%' }}></i></div>`

    const USERS = [
        ['Lusy Pratiwi', 'lusy@tiarabunda.example', 'Superadmin', 'Aktif'],
        ['Rina Kusuma', 'rina@tiarabunda.example', 'Admin', 'Aktif'],
        ['Budi Santoso', 'budi@tiarabunda.example', 'Admin', 'Aktif'],
        ['Aisyah Putri', 'aisyah@mail.example', 'Customer', 'Aktif'],
        ['Dimas Pratama', 'dimas@mail.example', 'Customer', 'Menunggu'],
        ['Nabila Zahra', 'nabila@mail.example', 'Customer', 'Aktif'],
        ['Fajar Ramadhan', 'fajar@mail.example', 'Customer', 'Nonaktif'],
        ['Salsa Amelia', 'salsa@mail.example', 'Customer', 'Menunggu']
    ]
    const bd = { Aktif: 'ok', Menunggu: 'warn', Nonaktif: 'err' }

    /* ---------- halaman ---------- */
    const Dash = () => {
        const d = useLoad(() =>
            wait({
                stats: [
                    ['Pendaftar PMB', '1.284', '+12%', 'user'],
                    ['Pengguna', '48', '+3', 'users'],
                    ['Berita terbit', '24', '+2', 'news'],
                    ['Kunjungan situs', '12,4 rb', '+8%', 'chart']
                ],
                bln: [42, 55, 48, 70, 64, 82, 90, 76, 68, 85, 100, 58],
                baru: USERS.slice(3, 7)
            })
        )
        if (!d) return h`<${Sk}/>`
        const M = 'JFMAMJJASOND'.split('')
        const max = Math.max(...d.bln)
        return h`<div class="g">
    <div class="g g4">${d.stats.map(
        (s) => h`<div class="card stat" key=${s[0]}>
      <span class="ico"><${Ic} n=${s[3]} s=${22}/></span>
      <div><small>${s[0]}</small><b>${s[1]}</b><span class="up">${s[2]}</span></div></div>`
    )}</div>
    <div class="g g2">
      <div class="card"><h2>Pendaftar PMB per bulan</h2>
        <div class="bars">${d.bln.map((v, i) => h`<div key=${i} class=${v === max ? 'hi' : ''}><span class="bv">${v}</span><i style=${{ height: v + '%' }}></i>${M[i]}</div>`)}</div></div>
      <div class="card"><h2>Kelengkapan konten</h2>${[
          ['Profil', 80],
          ['Pendidikan', 55],
          ['Layanan', 40],
          ['Informasi', 70]
      ].map(
          (x) =>
              h`<div key=${x[0]} style=${{ marginBottom: 14 }}><small>${x[0]} · ${x[1]}%</small><div class="prog"><i style=${{ width: x[1] + '%' }}></i></div></div>`
      )}</div>
    </div>
    <div class="card"><h2>Pendaftar terbaru</h2><div class="tw"><table>
      <thead><tr><th>Nama</th><th>Email</th><th>Status</th></tr></thead>
      <tbody>${d.baru.map((u) => h`<tr key=${u[1]}><td>${u[0]}</td><td>${u[1]}</td><td><span class=${'badge ' + bd[u[3]]}>${u[3]}</span></td></tr>`)}</tbody>
    </table></div></div></div>`
    }

    const Users = () => {
        const rows = useLoad(() => wait(USERS))
        const [q, setQ] = useState('')
        const [r, setR] = useState('Semua')
        if (!rows) return h`<${Sk}/>`
        const list = rows.filter(
            (u) =>
                (r === 'Semua' || u[2] === r) &&
                (u[0] + u[1]).toLowerCase().includes(q.toLowerCase())
        )
        return h`<div class="card">
    <div class="tabs">${['Semua', 'Superadmin', 'Admin', 'Customer'].map((x) => h`<button key=${x} class=${r === x ? 'on' : ''} onClick=${() => setR(x)}>${x}</button>`)}
      <div class="find" style=${{ marginLeft: 'auto' }}><${Ic} n="search" s=${16}/>
        <input placeholder="Cari pengguna…" aria-label="Cari pengguna" value=${q} onInput=${(e) => setQ(e.target.value)}/></div></div>
    <div class="tw"><table>
      <thead><tr><th>Nama</th><th>Email</th><th>Peran</th><th>Status</th></tr></thead>
      <tbody>${list.map((u) => h`<tr key=${u[1]}><td>${u[0]}</td><td>${u[1]}</td><td>${u[2]}</td><td><span class=${'badge ' + bd[u[3]]}>${u[3]}</span></td></tr>`)}</tbody>
    </table></div>
    ${!list.length && h`<p style=${{ padding: 16, color: 'var(--mut)' }}>Tidak ada data.</p>`}</div>`
    }

    const Content = ({ slug }) => {
        const t = TITLES['/konten/' + slug] || 'Konten'
        const [f, setF] = useState({ judul: t, ringkas: '', isi: '' })
        useEffect(() => setF({ judul: t, ringkas: '', isi: '' }), [slug])
        const set = (k) => (e) => setF({ ...f, [k]: e.target.value })
        const save = (e) => {
            e.preventDefault()
            toast('Tersimpan (mock). Hubungkan ke endpoint Laravel.')
            /* TODO: fetch('/superadmin/konten/' + slug, { method: 'POST', headers: {...}, body: JSON.stringify(f) }) */
        }
        return h`<form class="card" onSubmit=${save}><h2>Ubah konten: ${t}</h2>
    <div class="f"><label for="j">Judul</label><input id="j" value=${f.judul} onInput=${set('judul')} required/></div>
    <div class="f"><label for="r">Ringkasan</label><textarea id="r" rows="3" value=${f.ringkas} onInput=${set('ringkas')}></textarea></div>
    <div class="f"><label for="i">Isi halaman</label><textarea id="i" rows="10" value=${f.isi} onInput=${set('isi')}></textarea></div>
    <button class="btn" type="submit">Simpan perubahan</button></form>`
    }

    const Gallery = () => {
        const items = useLoad(() =>
            wait([
                'Laboratorium Komputer',
                'Perpustakaan',
                'Ruang Kelas',
                'Studio Praktik',
                'Area Komunitas',
                'Ruang Simulasi',
                'Kantin',
                'Aula'
            ])
        )
        const [v, setV] = useState('grid')
        if (!items) return h`<${Sk}/>`
        return h`<div><div class="ch2"><h2>Galeri fasilitas</h2>
    <div class="tabs" style=${{ margin: 0 }}>
      <button class=${v === 'grid' ? 'on' : ''} onClick=${() => setV('grid')} aria-label="Tampilan grid"><${Ic} n="grid" s=${16}/></button>
      <button class=${v === 'list' ? 'on' : ''} onClick=${() => setV('list')} aria-label="Tampilan daftar"><${Ic} n="list" s=${16}/></button></div></div>
    <div class=${'gl ' + v}>${items.map((x, i) => h`<figure class="gi" key=${x}><i></i><p>${x}<small>Foto ${i + 1} · diunggah Okt 2026</small></p></figure>`)}</div></div>`
    }

    const EV = {
        '2026-10-12': 'Kunjungan industri',
        '2026-10-20': 'PMB gelombang 1',
        '2026-10-29': 'Rapat konten',
        '2026-11-5': 'Pengisian KRS',
        '2026-11-20': 'Seleksi tertulis'
    }
    const Calendar = () => {
        const [d, setD] = useState(new Date(2026, 9, 1))
        const y = d.getFullYear(),
            m = d.getMonth()
        const lead = (new Date(y, m, 1).getDay() + 6) % 7
        const n = new Date(y, m + 1, 0).getDate()
        const cells = [
            ...Array(lead).fill(0),
            ...Array.from({ length: n }, (_, i) => i + 1)
        ]
        const go = (k) => setD(new Date(y, m + k, 1))
        return h`<div class="card"><div class="ch2">
    <h2 style=${{ margin: 0, textTransform: 'capitalize' }}>${d.toLocaleDateString('id-ID', { month: 'long', year: 'numeric' })}</h2>
    <div class="tabs" style=${{ margin: 0 }}>
      <button onClick=${() => go(-1)} aria-label="Bulan lalu"><${Ic} n="left" s=${16}/></button>
      <button onClick=${() => go(1)} aria-label="Bulan depan"><${Ic} n="right" s=${16}/></button></div></div>
    <div class="cal">${['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'].map((x) => h`<b key=${x}>${x}</b>`)}
    ${cells.map((c, i) => {
        const e = c && EV[`${y}-${m + 1}-${c}`]
        const t = c && y === 2026 && m === 9 && c === 29
        return h`<div key=${i} class=${c ? 'cd' + (t ? ' t' : '') + (e ? ' e' : '') : ''}>${c || ''}${e && h`<em>${e}</em>`}</div>`
    })}</div></div>`
    }

    const Faq = () => {
        const [items, setItems] = useState([
            [
                'Bagaimana cara mendaftar?',
                'Isi email di formulir PMB, lengkapi data diri, unggah dokumen, lalu ikuti seleksi.'
            ],
            [
                'Apakah kampus sudah terakreditasi?',
                'Informasi akreditasi tersedia di menu Akreditasi.'
            ],
            [
                'Apakah ada kelas karyawan?',
                'Ada kelas sore untuk program studi tertentu.'
            ]
        ])
        const [o, setO] = useState(0),
            [a, setA] = useState(''),
            [b, setB] = useState('')
        const add = (e) => {
            e.preventDefault()
            setItems([...items, [a, b]])
            setA('')
            setB('')
            toast('FAQ ditambahkan (mock)')
            /* TODO: simpan ke config/database lewat controller Laravel */
        }
        return h`<div class="g g2">
    <div class="card"><h2>Daftar FAQ</h2>${items.map(
        (q, i) => h`<div key=${i} class=${'qa' + (o === i ? ' open' : '')}>
      <button aria-expanded=${o === i} onClick=${() => setO(o === i ? -1 : i)}>${q[0]}<${Ic} n="chev" s=${16}/></button>
      <div class="qb"><p>${q[1]} <button class="badge err" onClick=${() => setItems(items.filter((_, k) => k !== i))}>Hapus</button></p></div></div>`
    )}</div>
    <form class="card" onSubmit=${add}><h2>Tambah FAQ</h2>
      <div class="f"><label for="fq">Pertanyaan</label><input id="fq" value=${a} onInput=${(e) => setA(e.target.value)} required/></div>
      <div class="f"><label for="fa">Jawaban</label><textarea id="fa" rows="4" value=${b} onInput=${(e) => setB(e.target.value)} required></textarea></div>
      <button class="btn" type="submit"><${Ic} n="plus" s=${16}/>Tambah</button></form></div>`
    }

    const Showcase = () => h`<div class="g g3">
  <div class="card"><h3>Tombol</h3><p class="tabs"><button class="btn">Utama</button><button class="btn or">Aksen</button><button class="btn gh">Garis</button><button class="btn sm">Kecil</button></p></div>
  <div class="card"><h3>Lencana</h3><p class="tabs"><span class="badge">Info</span><span class="badge ok">Sukses</span><span class="badge warn">Peringatan</span><span class="badge err">Galat</span></p></div>
  <div class="card"><h3>Progres</h3><div class="prog"><i style=${{ width: '65%' }}></i></div></div>
  <div class="card"><h3>Peringatan</h3><div class="al">Informasi umum untuk pengguna.</div><div class="al w">Perhatian: periksa kembali data.</div></div>
  <div class="card"><h3>Formulir</h3>
    <div class="f"><label for="s1">Teks</label><input id="s1" placeholder="Ketik di sini"/></div>
    <div class="f"><label for="s2">Pilihan</label><select id="s2"><option>Satu</option><option>Dua</option></select></div></div>
  <div class="card"><h3>Notifikasi</h3><button class="btn" onClick=${() => toast('Halo dari dashboard!')}>Tampilkan toast</button></div></div>`

    /* ---------- halaman error ---------- */
    const Err = ({ c, t, d }) =>
        h`<div class="errp card"><b>${c}</b><h2>${t}</h2><p>${d}</p><a class="btn" href="#/">Kembali ke dashboard</a></div>`
    const E = {
        '/403': () =>
            h`<${Err} c="403" t="Akses dilarang" d="Anda tidak memiliki izin untuk membuka halaman ini."/>`,
        '/404': () =>
            h`<${Err} c="404" t="Halaman tidak ditemukan" d="Alamat yang Anda tuju tidak ada atau sudah dipindahkan."/>`,
        '/500': () =>
            h`<${Err} c="500" t="Terjadi kesalahan server" d="Coba muat ulang beberapa saat lagi."/>`
    }

    /* ---------- autentikasi ---------- */
    const AUTH = {
        '/masuk': {
            t: 'Masuk',
            d: 'Masuk untuk mengelola konten kampus.',
            f: [
                ['email', 'Email', 'email'],
                ['password', 'Kata sandi', 'password']
            ],
            b: 'Masuk',
            next: '/',
            ln: [
                ['Lupa kata sandi?', '/lupa-sandi'],
                ['Daftar akun', '/daftar']
            ]
        },
        '/daftar': {
            t: 'Daftar',
            d: 'Buat akun pengelola baru.',
            f: [
                ['nama', 'Nama lengkap', 'text'],
                ['email', 'Email', 'email'],
                ['password', 'Kata sandi', 'password']
            ],
            b: 'Buat akun',
            next: '/masuk',
            ln: [['Sudah punya akun? Masuk', '/masuk']]
        },
        '/lupa-sandi': {
            t: 'Lupa kata sandi',
            d: 'Kami kirim tautan reset ke email Anda.',
            f: [['email', 'Email', 'email']],
            b: 'Kirim tautan',
            next: '/atur-ulang',
            ln: [['Kembali ke masuk', '/masuk']]
        },
        '/atur-ulang': {
            t: 'Atur ulang kata sandi',
            d: 'Masukkan kata sandi baru.',
            f: [
                ['password', 'Kata sandi baru', 'password'],
                ['password2', 'Ulangi kata sandi', 'password']
            ],
            b: 'Simpan',
            next: '/masuk',
            ln: []
        }
    }
    const Auth = ({ a }) => {
        const submit = (e) => {
            e.preventDefault()
            toast(a.t + ' berhasil (mock)')
            location.hash = a.next
            /* TODO: kirim ke route auth Laravel */
        }
        return h`<main class="au">
    <section class="au-l"><div class="brand"><img src=${LOGO} alt=""/><span>${BRAND}</span></div><h2>Pusat kendali konten kampus</h2><p>Kelola seluruh isi landing page dari satu tempat.</p></section>
    <section class="au-r"><form onSubmit=${submit}><h1>${a.t}</h1><p style=${{ color: 'var(--mut)', marginBottom: 20 }}>${a.d}</p>
      ${a.f.map(
          (
              f
          ) => h`<div class="f" key=${f[0]}><label for=${'a' + f[0]}>${f[1]}</label>
        <input id=${'a' + f[0]} type=${f[2]} required autocomplete=${f[2] === 'password' ? 'current-password' : f[2] === 'email' ? 'email' : 'off'}/></div>`
      )}
      <button class="btn" style=${{ width: '100%' }} type="submit">${a.b}</button>
      <div class="ln">${a.ln.map((l) => h`<a key=${l[1]} href=${'#' + l[1]}>${l[0]}</a>`)}</div></form></section></main>`
    }

    /* ---------- aplikasi ---------- */
    const useHash = () => {
        const g = () => location.hash.slice(1) || '/'
        const [p, setP] = useState(g())
        useEffect(() => {
            const f = () => {
                setP(g())
                scrollTo(0, 0)
            }
            addEventListener('hashchange', f)
            return () => removeEventListener('hashchange', f)
        }, [])
        return p
    }
    const ROUTES = {
        '/': Dash,
        '/pengguna': Users,
        '/galeri': Gallery,
        '/kalender': Calendar,
        '/faq': Faq,
        '/showcase': Showcase,
        ...E
    }

    const App = () => {
        const path = useHash()
        const [theme, setTheme] = useState(
            document.documentElement.dataset.theme || 'light'
        )
        const [boxed, setBoxed] = useState(st.get('boxed', '0') === '1')
        const [side, setSide] = useState(false)

        useEffect(() => {
            document.documentElement.dataset.theme = theme
            st.set('theme', theme)
        }, [theme])
        useEffect(() => {
            st.set('boxed', boxed ? '1' : '0')
        }, [boxed])
        useEffect(() => {
            setSide(false)
        }, [path])

        const slug = path.startsWith('/konten/') ? path.slice(8) : null
        const title =
            TITLES[path] ||
            (AUTH[path] && AUTH[path].t) ||
            'Halaman tidak ditemukan'
        useEffect(() => {
            document.title = `${title} — ${BRAND}`
        }, [title])

        if (AUTH[path]) return h`<${Auth} a=${AUTH[path]}/>`
        const Page = ROUTES[path] || E['/404']

        return h`<div class=${'app' + (boxed ? ' boxed' : '') + (side ? ' open-s' : '')}>
    <aside class="side" aria-label="Navigasi utama">
      <a class="brand" href="#/"><img src=${LOGO} alt=""/><span>${BRAND}<small>Panel Superadmin</small></span></a>
      <nav class="nav">${NAV.map((n, i) => h`<${NavItem} key=${i} n=${n} path=${path}/>`)}</nav>
      <div class="me"><span class="av">L</span><span><b>Lusy Pratiwi</b><small>Superadmin</small></span>
        <a class="ib sm" href="#/masuk" aria-label="Keluar" title="Keluar"><${Ic} n="out" s=${16}/></a></div>
    </aside>
    <div class="dim" onClick=${() => setSide(false)}></div>
    <div class="main">
      <header class="top">
        <button class="ib burger" aria-label="Buka menu" onClick=${() => setSide(!side)}><${Ic} n="menu"/></button>
        <div class="ttl"><p class="crumb">Superadmin<span>/</span>${title}</p><h1>${title}</h1></div>
        <label class="find"><${Ic} n="search" s=${16}/><input type="search" placeholder="Cari…" aria-label="Cari"/></label>
        <button class="ib" aria-label="Fluid / boxed" title=${boxed ? 'Layout boxed' : 'Layout fluid'} onClick=${() => setBoxed(!boxed)}><${Ic} n="width"/></button>
        <button class="ib" aria-label="Ganti tema" onClick=${() => setTheme(theme === 'dark' ? 'light' : 'dark')}><${Ic} n=${theme === 'dark' ? 'sun' : 'moon'}/></button>
        <button class="ib dotb" aria-label="Notifikasi"><${Ic} n="bell"/></button>
      </header>
      <main class="page" key=${path}>${slug ? h`<${Content} slug=${slug}/>` : h`<${Page}/>`}</main>
    </div></div>`
    }

    ReactDOM.createRoot(rootEl).render(h`<${App}/>`)
})()
