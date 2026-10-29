import React, { useState } from 'react';
import { GALLERY } from '../data/mock';
import { Btn, Card, Modal, PageHead, Skel, ViewToggle, Badge } from '../components/ui';
import { useApp, useMock, useTitle } from '../context/app';

const TONES = ['from-brand-500 to-brand-300', 'from-accent-500 to-accent-300', 'from-brand-700 to-brand-400', 'from-accent-600 to-brand-500'];
const Thumb = ({ g, className = '' }) => (
    <div className={`grid place-items-center bg-gradient-to-br ${TONES[g.id % 4]} text-3xl font-extrabold text-white/90 ${className}`} role="img" aria-label={g.title}>{g.title[0]}</div>
);

export default function Gallery() {
    useTitle('Galeri');
    const { notify } = useApp();
    const { loading } = useMock(true);
    const [view, setView] = useState('grid');
    const [cat, setCat] = useState('Semua');
    const [light, setLight] = useState(null);
    const items = GALLERY.filter((g) => cat === 'Semua' || g.cat === cat);
    return (
        <>
            <PageHead title="Galeri" desc="Foto fasilitas, kegiatan, dan prestasi untuk website.">
                <ViewToggle view={view} set={setView} /><Btn icon="plus" onClick={() => notify('Unggah foto aktif setelah storage Laravel terpasang')}>Unggah foto</Btn>
            </PageHead>
            <div className="mb-4 flex flex-wrap gap-2">{['Semua', 'Fasilitas', 'Kegiatan', 'Prestasi'].map((c) => (
                <button key={c} type="button" aria-pressed={cat === c} onClick={() => setCat(c)} className={`rounded-full px-4 py-1.5 text-sm font-semibold ${cat === c ? 'bg-brand-600 text-white' : 'bg-white dark:bg-white/10'}`}>{c}</button>))}</div>
            {loading ? <div className="grid gap-4 sm:grid-cols-3 lg:grid-cols-4">{Array.from({ length: 8 }, (_, i) => <Skel key={i} className="h-44" />)}</div>
                : view === 'grid' ? (
                    <div className="grid gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">{items.map((g) => (
                        <button key={g.id} type="button" onClick={() => setLight(g)} className="group overflow-hidden rounded-2xl border border-brand-100 bg-white text-left dark:border-white/10 dark:bg-brand-800/60">
                            <Thumb g={g} className="h-40 transition group-hover:scale-105" /><div className="p-3"><b className="block text-sm">{g.title}</b><small className="text-slate-500">{g.cat} · {g.date}</small></div></button>))}</div>
                ) : (
                    <Card><ul className="divide-y divide-brand-100 dark:divide-white/10">{items.map((g) => (
                        <li key={g.id}><button type="button" onClick={() => setLight(g)} className="flex w-full items-center gap-4 py-3 text-left"><Thumb g={g} className="h-14 w-20 shrink-0 rounded-xl text-xl" />
                            <span className="flex-1"><b className="block text-sm">{g.title}</b><small className="text-slate-500">{g.date} · {g.size}</small></span><Badge tone={g.cat === 'Prestasi' ? 'orange' : 'blue'}>{g.cat}</Badge></button></li>))}</ul></Card>
                )}
            {light && <Modal title={light.title} onClose={() => setLight(null)}><Thumb g={light} className="h-64 rounded-xl text-6xl" /><p className="mt-3 text-sm text-slate-500">{light.cat} · {light.date} · {light.size}</p></Modal>}
        </>
    );
}
