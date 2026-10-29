import React from 'react';
import { Link } from 'react-router-dom';
import { Card, PageHead, Skel, Badge, Icon } from '../components/ui';
import { useMock, useTitle } from '../context/app';
import { STATS, PENDAFTAR_BULANAN, JALUR_PCT, AKTIVITAS } from '../data/mock';

export default function Dashboard() {
    useTitle('Dashboard');
    const { loading } = useMock(true);
    const max = Math.max(...PENDAFTAR_BULANAN.map((m) => m[1]));
    return (
        <>
            <PageHead title="Selamat datang kembali" desc="Ringkasan konten website dan pendaftaran PMB 2026/2027." />
            <div className="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
                {loading ? [0, 1, 2, 3].map((i) => <Skel key={i} className="h-28" />) : STATS.map((s) => (
                    <Card key={s.label} className={s.tone === 'orange' ? 'border-t-4 border-t-accent-500' : 'border-t-4 border-t-brand-600'}>
                        <p className="text-sm text-slate-500 dark:text-slate-400">{s.label}</p>
                        <p className="mt-1 text-3xl font-extrabold text-brand-800 dark:text-white">{s.value.toLocaleString('id-ID')}</p>
                        <Badge tone={s.tone}>{s.delta}</Badge>
                    </Card>
                ))}
            </div>
            <div className="mt-4 grid gap-4 lg:grid-cols-3">
                <Card title="Pendaftar per bulan" className="lg:col-span-2">
                    {loading ? <Skel className="h-52" /> : (
                        <div className="flex h-52 items-end gap-3" role="img" aria-label="Grafik batang pendaftar per bulan">
                            {PENDAFTAR_BULANAN.map(([m, v], i) => (
                                <div key={m} className="flex flex-1 flex-col items-center gap-2">
                                    <span className="text-xs font-bold">{v}</span>
                                    <div className={`w-full rounded-t-xl ${i === PENDAFTAR_BULANAN.length - 3 ? 'bg-accent-500' : 'bg-brand-500'}`} style={{ height: `${(v / max) * 150}px` }} />
                                    <span className="text-xs text-slate-500">{m}</span>
                                </div>
                            ))}
                        </div>
                    )}
                </Card>
                <Card title="Pendaftar per jalur">
                    {loading ? <Skel className="h-52" /> : (
                        <ul className="space-y-4">{JALUR_PCT.map(([n, p]) => (
                            <li key={n}><div className="mb-1 flex justify-between text-sm"><span className="font-semibold">{n}</span><span>{p}%</span></div>
                                <div className="h-2.5 rounded-full bg-brand-100 dark:bg-white/10"><div className="h-full rounded-full bg-brand-600" style={{ width: `${p}%` }} /></div></li>
                        ))}</ul>
                    )}
                </Card>
            </div>
            <div className="mt-4 grid gap-4 lg:grid-cols-3">
                <Card title="Aktivitas terakhir" className="lg:col-span-2">
                    <ul className="divide-y divide-brand-100 dark:divide-white/10">{AKTIVITAS.map(([who, what, when]) => (
                        <li key={what} className="flex items-center gap-3 py-3 text-sm"><span className="grid h-9 w-9 shrink-0 place-items-center rounded-full bg-brand-100 font-bold text-brand-700">{who[0]}</span>
                            <span className="flex-1"><b>{who}</b> {what}</span><time className="text-xs text-slate-500">{when}</time></li>
                    ))}</ul>
                </Card>
                <Card title="Pintasan">
                    <div className="grid gap-2">
                        {[['/konten/berita', 'Tulis berita', 'news'], ['/galeri', 'Unggah foto', 'image'], ['/konten/jalur', 'Atur jalur masuk', 'cap'], ['/kalender', 'Tambah jadwal', 'calendar']].map(([to, t, ic]) => (
                            <Link key={to} to={to} className="flex items-center gap-3 rounded-xl bg-brand-50 px-4 py-3 text-sm font-bold text-brand-700 transition hover:bg-accent-100 dark:bg-white/5 dark:text-brand-100"><Icon n={ic} />{t}</Link>
                        ))}
                    </div>
                </Card>
            </div>
        </>
    );
}
