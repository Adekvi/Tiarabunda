import React, { useState } from 'react';
import { Badge, Btn, Card, Field, Modal, PageHead, Skel, inputCls } from '../components/ui';
import { useApp, useTitle } from '../context/app';

export default function Showcase() {
    useTitle('Showcase');
    const { notify, theme, setTheme, boxed, setBoxed } = useApp();
    const [modal, setModal] = useState(false);
    const [on, setOn] = useState(true);
    const sw = (v, set, l) => (<label className="flex cursor-pointer items-center gap-3 text-sm font-semibold"><button type="button" role="switch" aria-checked={v} aria-label={l} onClick={() => set(!v)} className={`relative h-6 w-11 rounded-full transition ${v ? 'bg-brand-600' : 'bg-slate-300'}`}><span className={`absolute top-0.5 h-5 w-5 rounded-full bg-white transition-all ${v ? 'left-[22px]' : 'left-0.5'}`} /></button>{l}</label>);
    return (
        <>
            <PageHead title="Showcase komponen" desc="Semua komponen siap pakai. Salin pola ini saat membuat halaman baru." />
            <div className="grid gap-4 lg:grid-cols-2">
                <Card title="Tombol"><div className="flex flex-wrap gap-2"><Btn>Utama</Btn><Btn v="accent">Aksen</Btn><Btn v="ghost">Garis</Btn><Btn v="danger">Hapus</Btn><Btn icon="plus" sm>Dengan ikon</Btn></div></Card>
                <Card title="Lencana"><div className="flex flex-wrap gap-2"><Badge>Biru</Badge><Badge tone="orange">Oranye</Badge><Badge tone="green">Aktif</Badge><Badge tone="red">Ditolak</Badge><Badge tone="gray">Draf</Badge></div></Card>
                <Card title="Formulir"><div className="space-y-3"><Field id="s1" label="Nama" placeholder="Nama lengkap" /><div><label htmlFor="s2" className="mb-1.5 block text-sm font-semibold">Jalur</label><select id="s2" className={inputCls}><option>Reguler</option><option>Prestasi</option></select></div></div></Card>
                <Card title="Pengaturan tampilan"><div className="space-y-3">{sw(theme === 'dark', (v) => setTheme(v ? 'dark' : 'light'), 'Mode gelap')}{sw(boxed, setBoxed, 'Tata letak boxed')}{sw(on, setOn, 'Contoh sakelar')}</div></Card>
                <Card title="Notifikasi & modal"><div className="flex flex-wrap gap-2"><Btn v="ghost" onClick={() => notify('Berhasil disimpan')}>Toast sukses</Btn><Btn v="ghost" onClick={() => notify('Gagal menyimpan', 'err')}>Toast galat</Btn><Btn onClick={() => setModal(true)}>Buka modal</Btn></div></Card>
                <Card title="Pemuatan"><div className="space-y-2"><Skel className="h-5 w-2/3" /><Skel className="h-5" /><Skel className="h-16" /></div></Card>
                <Card title="Peringatan" className="lg:col-span-2"><div className="grid gap-3 md:grid-cols-3">
                    {[['Info', 'bg-brand-50 text-brand-800 border-brand-300'], ['Perhatian', 'bg-accent-100 text-accent-600 border-accent-300'], ['Bahaya', 'bg-red-50 text-red-700 border-red-300']].map(([t, c]) => <div key={t} role="alert" className={`rounded-xl border-l-4 p-4 text-sm ${c}`}><b>{t}.</b> Teks pesan singkat dan jelas.</div>)}</div></Card>
            </div>
            {modal && <Modal title="Contoh modal" onClose={() => setModal(false)}><p className="text-sm">Tekan Esc atau klik di luar untuk menutup.</p></Modal>}
        </>
    );
}
