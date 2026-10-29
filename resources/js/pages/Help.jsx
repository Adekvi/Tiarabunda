import React, { useState } from 'react';
import { HELP } from '../data/mock';
import { Card, Icon, PageHead } from '../components/ui';
import { useTitle } from '../context/app';

export default function Help() {
    useTitle('Pusat Bantuan');
    const [open, setOpen] = useState(0);
    return (
        <>
            <PageHead title="Pusat bantuan" desc="Jawaban singkat untuk pengelola konten." />
            <Card className="max-w-3xl"><div className="divide-y divide-brand-100 dark:divide-white/10">{HELP.map(([q, a], i) => (
                <div key={q}><button type="button" aria-expanded={open === i} onClick={() => setOpen(open === i ? -1 : i)} className="flex w-full items-center justify-between gap-4 py-4 text-left font-bold">
                    {q}<Icon n="down" className={`shrink-0 transition ${open === i ? 'rotate-180 text-accent-500' : ''}`} /></button>
                    {open === i && <p className="sa-pop pb-4 text-sm text-slate-600 dark:text-slate-300">{a}</p>}</div>))}</div></Card>
        </>
    );
}
