import React, { useState } from 'react';
import { EVENTS } from '../data/mock';
import { Card, IconBtn, PageHead, Badge } from '../components/ui';
import { useTitle } from '../context/app';

const BULAN = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
const pad = (n) => String(n).padStart(2, '0');

export default function Calendar() {
    useTitle('Kalender');
    const [cur, setCur] = useState({ y: 2026, m: 9 });
    const [sel, setSel] = useState(null);
    const first = new Date(cur.y, cur.m, 1).getDay();
    const days = new Date(cur.y, cur.m + 1, 0).getDate();
    const key = (d) => `${cur.y}-${pad(cur.m + 1)}-${pad(d)}`;
    const ev = (d) => EVENTS.filter((e) => e.d === key(d));
    const go = (n) => { const d = new Date(cur.y, cur.m + n, 1); setCur({ y: d.getFullYear(), m: d.getMonth() }); setSel(null); };
    const list = sel ? EVENTS.filter((e) => e.d === key(sel)) : EVENTS.filter((e) => e.d.startsWith(`${cur.y}-${pad(cur.m + 1)}`));

    return (
        <>
            <PageHead title="Kalender kegiatan" desc="Jadwal PMB, seleksi, dan kegiatan kampus yang tampil di website." />
            <div className="grid gap-4 xl:grid-cols-3">
                <Card className="xl:col-span-2" title={`${BULAN[cur.m]} ${cur.y}`} action={<div className="flex"><IconBtn n="left" label="Bulan sebelumnya" onClick={() => go(-1)} /><IconBtn n="right" label="Bulan berikutnya" onClick={() => go(1)} /></div>}>
                    <div className="grid grid-cols-7 gap-1 text-center text-xs font-bold text-slate-500">{['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'].map((d) => <div key={d} className="py-2">{d}</div>)}</div>
                    <div className="grid grid-cols-7 gap-1">
                        {Array.from({ length: first }).map((_, i) => <div key={`b${i}`} />)}
                        {Array.from({ length: days }, (_, i) => i + 1).map((d) => (
                            <button key={d} type="button" onClick={() => setSel(d === sel ? null : d)} aria-pressed={sel === d}
                                className={`flex aspect-square flex-col items-center justify-start gap-1 rounded-xl p-1.5 text-sm font-semibold transition sm:aspect-[4/3] ${sel === d ? 'bg-brand-600 text-white' : 'hover:bg-brand-50 dark:hover:bg-white/10'}`}>
                                {d}<span className="flex gap-0.5">{ev(d).map((e) => <i key={e.t} className={`h-1.5 w-1.5 rounded-full ${e.c === 'orange' ? 'bg-accent-500' : sel === d ? 'bg-white' : 'bg-brand-500'}`} />)}</span>
                            </button>))}
                    </div>
                </Card>
                <Card title={sel ? `${sel} ${BULAN[cur.m]}` : `Agenda ${BULAN[cur.m]}`}>
                    {list.length ? <ul className="space-y-3">{list.map((e) => (
                        <li key={e.d + e.t} className="rounded-xl bg-brand-50 p-3 dark:bg-white/5"><Badge tone={e.c}>{e.d.slice(8)} {BULAN[+e.d.slice(5, 7) - 1].slice(0, 3)}</Badge><p className="mt-1 font-semibold">{e.t}</p></li>))}</ul>
                        : <p className="text-sm text-slate-500">Tidak ada agenda pada waktu ini.</p>}
                </Card>
            </div>
        </>
    );
}
