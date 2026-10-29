import React from 'react';
import { Card, PageHead } from '../components/ui';
import { useTitle } from '../context/app';

const C = ({ children }) => <pre className="overflow-x-auto rounded-xl bg-brand-900 p-4 text-xs leading-6 text-brand-100"><code>{children}</code></pre>;
export default function Docs() {
    useTitle('Dokumentasi');
    return (
        <>
            <PageHead title="Dokumentasi" desc="Cara memasang, menyesuaikan, dan menyambungkan dashboard ke Laravel." />
            <div className="max-w-3xl space-y-4">
                <Card title="1. Pasang">
                    <C>{`npm i react react-dom react-router-dom lucide-react\nnpm i -D @vitejs/plugin-react sass\nnpm run dev   # atau: npm run build`}</C>
                    <p className="mt-3 text-sm">Buka <code>/superadmin</code>. Masuk dengan email apa saja dan password minimal 6 karakter (autentikasi masih tiruan).</p></Card>
                <Card title="2. Struktur berkas"><C>{`resources/views/superadmin/index.blade.php   ← halaman dasar SPA\nresources/css/superadmin.css                 ← Tailwind + token warna\nresources/scss/superadmin.scss               ← Sass: sidebar, motif lengkung\nresources/js/superadmin/\n  main.jsx · App.jsx                          ← pintu masuk & rute\n  context/app.jsx                             ← tema, layout, auth, toast\n  data/menu.js                                ← menu bertingkat\n  data/mock.js                                ← data tiruan\n  components/ · pages/`}</C></Card>
                <Card title="3. Menambah halaman">
                    <p className="mb-2 text-sm">Tambah seksi konten baru cukup dengan satu entri di <code>data/mock.js → CONTENT</code> dan satu baris di <code>data/menu.js</code>. Halaman khusus: buat berkas di <code>pages/</code>, daftarkan di <code>App.jsx</code>.</p></Card>
                <Card title="4. Menyambung ke Laravel">
                    <p className="mb-2 text-sm">Ganti <code>useMock()</code> dan <code>login()</code> dengan <code>fetch('/api/superadmin/...')</code>. Agar landing page ikut berubah, simpan konten ke tabel, lalu baca di <code>config/landing.php</code>.</p>
                    <C>{`// routes/api.php\nRoute::middleware(['auth:sanctum','can:superadmin'])\n  ->prefix('superadmin')->group(fn () => /* ... */);`}</C></Card>
                <Card title="5. Tema">
                    <p className="text-sm">Warna diambil dari brosur PMB: biru jas almamater (<code>brand-*</code>) dan oranye (<code>accent-*</code>). Ubah di <code>@theme</code> pada <code>superadmin.css</code>. Mode gelap memakai class <code>dark</code> pada <code>&lt;html&gt;</code>.</p></Card>
            </div>
        </>
    );
}
