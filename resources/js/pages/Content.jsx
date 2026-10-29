import React, { useState } from 'react';
import { useParams } from 'react-router-dom';
import { CONTENT } from '../data/mock';
import { Btn, Card, Field, Icon, IconBtn, Modal, PageHead, Skel, ViewToggle, inputCls } from '../components/ui';
import { useApp, useMock, useTitle } from '../context/app';
import { NotFound } from './Errors';

/* Satu halaman editor untuk semua seksi konten (form ATAU daftar), digerakkan oleh data/mock.js */
export default function Content() {
    const { slug } = useParams();
    const def = CONTENT[slug];
    useTitle(def ? def.title : 'Konten');
    const { loading } = useMock(true, 350);
    if (!def) return <NotFound inside />;
    return (
        <>
            <PageHead title={def.title} desc={def.desc} />
            {loading ? <Skel className="h-72" /> : def.type === 'form' ? <FormEditor key={slug} def={def} /> : <ListEditor key={slug} def={def} />}
        </>
    );
}

function FormEditor({ def }) {
    const { notify } = useApp();
    const [v, setV] = useState(def.values);
    return (
        <Card>
            <div className="grid gap-4 md:grid-cols-2">
                {def.fields.map((f) => (
                    <div key={f.key} className={f.type === 'area' ? 'md:col-span-2' : ''}>
                        <Field id={f.key} label={f.label} area={f.type === 'area'} value={v[f.key] ?? ''} onChange={(e) => setV({ ...v, [f.key]: e.target.value })} />
                    </div>
                ))}
            </div>
            <div className="mt-5 flex gap-2"><Btn icon="save" onClick={() => notify('Perubahan disimpan')}>Simpan perubahan</Btn><Btn v="ghost" onClick={() => setV(def.values)}>Kembalikan</Btn></div>
        </Card>
    );
}

function ListEditor({ def }) {
    const { notify } = useApp();
    const [rows, setRows] = useState(def.rows);
    const [view, setView] = useState('list');
    const [q, setQ] = useState('');
    const [edit, setEdit] = useState(null); // {i, data}
    const blank = Object.fromEntries(def.cols.map((c) => [c.key, '']));
    const shown = rows.map((r, i) => ({ r, i })).filter(({ r }) => JSON.stringify(r).toLowerCase().includes(q.toLowerCase()));

    const save = () => {
        setRows(edit.i === -1 ? [...rows, edit.data] : rows.map((r, i) => (i === edit.i ? edit.data : r)));
        notify(edit.i === -1 ? 'Data ditambahkan' : 'Data diperbarui'); setEdit(null);
    };
    const del = (i) => { if (confirm('Hapus data ini?')) { setRows(rows.filter((_, x) => x !== i)); notify('Data dihapus'); } };
    const title = (r) => r[def.cols[0].key];

    return (
        <Card>
            <div className="mb-4 flex flex-wrap items-center gap-3">
                <div className="relative min-w-[200px] flex-1"><Icon n="search" size={16} className="absolute left-3.5 top-3 text-slate-400" />
                    <input aria-label="Cari" placeholder="Cari data…" value={q} onChange={(e) => setQ(e.target.value)} className={`${inputCls} pl-10`} /></div>
                <ViewToggle view={view} set={setView} />
                <Btn icon="plus" onClick={() => setEdit({ i: -1, data: blank })}>Tambah</Btn>
            </div>
            {!shown.length && <p className="rounded-xl bg-brand-50 p-8 text-center text-sm text-slate-500 dark:bg-white/5">Belum ada data. Klik Tambah untuk membuat yang pertama.</p>}
            {view === 'list' && !!shown.length && (
                <div className="overflow-x-auto"><table className="w-full text-left text-sm">
                    <thead><tr className="border-b border-brand-100 text-slate-500 dark:border-white/10">{def.cols.map((c) => <th key={c.key} className="px-3 py-2 font-semibold">{c.label}</th>)}<th className="w-24 px-3 py-2"><span className="sr-only">Aksi</span></th></tr></thead>
                    <tbody>{shown.map(({ r, i }) => (
                        <tr key={i} className="border-b border-brand-50 hover:bg-brand-50/60 dark:border-white/5 dark:hover:bg-white/5">
                            {def.cols.map((c) => <td key={c.key} className="max-w-[320px] truncate px-3 py-3">{String(r[c.key])}</td>)}
                            <td className="px-3 py-1"><div className="flex"><IconBtn n="edit" label="Ubah" onClick={() => setEdit({ i, data: r })} /><IconBtn n="trash" label="Hapus" onClick={() => del(i)} /></div></td>
                        </tr>))}</tbody></table></div>
            )}
            {view === 'grid' && !!shown.length && (
                <div className="grid gap-3 sm:grid-cols-2 xl:grid-cols-3">{shown.map(({ r, i }) => (
                    <article key={i} className="flex flex-col rounded-2xl border border-brand-100 p-4 dark:border-white/10">
                        <h3 className="font-bold text-brand-800 dark:text-white">{title(r)}</h3>
                        <p className="mt-1 line-clamp-3 flex-1 text-sm text-slate-500">{def.cols.slice(1).map((c) => r[c.key]).filter(Boolean).join(' · ')}</p>
                        <div className="mt-3 flex gap-1"><Btn v="ghost" sm icon="edit" onClick={() => setEdit({ i, data: r })}>Ubah</Btn><Btn v="ghost" sm icon="trash" onClick={() => del(i)}>Hapus</Btn></div>
                    </article>))}</div>
            )}
            {edit && (
                <Modal title={edit.i === -1 ? 'Tambah data' : 'Ubah data'} onClose={() => setEdit(null)}>
                    <div className="space-y-4">{def.cols.map((c) => (
                        <Field key={c.key} id={`f-${c.key}`} label={c.label} type={c.type === 'number' ? 'number' : 'text'} area={c.type === 'area'}
                            value={edit.data[c.key] ?? ''} onChange={(e) => setEdit({ ...edit, data: { ...edit.data, [c.key]: e.target.value } })} />))}</div>
                    <div className="mt-5 flex justify-end gap-2"><Btn v="ghost" onClick={() => setEdit(null)}>Batal</Btn><Btn onClick={save}>Simpan</Btn></div>
                </Modal>
            )}
        </Card>
    );
}
