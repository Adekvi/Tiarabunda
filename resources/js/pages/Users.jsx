import React, { useState } from 'react';
import { NavLink, Navigate, useParams } from 'react-router-dom';
import { USERS } from '../data/mock';
import { Badge, Btn, Card, PageHead, Skel, inputCls } from '../components/ui';
import { useMock, useTitle } from '../context/app';

const TONE = { Aktif: 'green', Nonaktif: 'gray', 'Menunggu verifikasi': 'orange' };

/* Panel terpisah untuk Admin dan Customer (role) */
export default function Users() {
    const { role } = useParams();
    useTitle(`Pengguna ${role}`);
    const { loading } = useMock(true);
    const [q, setQ] = useState('');
    if (!['admin', 'customer'].includes(role)) return <Navigate to="/pengguna/admin" replace />;
    const list = USERS.filter((u) => (role === 'admin' ? ['admin', 'superadmin'].includes(u.role) : u.role === 'customer') && (u.name + u.email).toLowerCase().includes(q.toLowerCase()));
    return (
        <>
            <PageHead title={role === 'admin' ? 'Panel Admin' : 'Panel Customer'} desc={role === 'admin' ? 'Pengelola konten website dan hak aksesnya.' : 'Calon mahasiswa yang membuat akun lewat formulir PMB.'}>
                <Btn icon="plus">{role === 'admin' ? 'Undang admin' : 'Tambah customer'}</Btn>
            </PageHead>
            <div className="mb-4 flex gap-2" role="tablist">{['admin', 'customer'].map((r) => (
                <NavLink key={r} to={`/pengguna/${r}`} role="tab" className={({ isActive }) => `rounded-full px-4 py-2 text-sm font-bold ${isActive ? 'bg-brand-600 text-white' : 'bg-white text-brand-700 dark:bg-white/10 dark:text-brand-100'}`}>{r === 'admin' ? 'Admin' : 'Customer'}</NavLink>))}</div>
            <Card>
                <input aria-label="Cari pengguna" placeholder="Cari nama atau email…" value={q} onChange={(e) => setQ(e.target.value)} className={`${inputCls} mb-4 max-w-sm`} />
                {loading ? <Skel className="h-60" /> : (
                    <div className="overflow-x-auto"><table className="w-full text-left text-sm">
                        <thead><tr className="border-b border-brand-100 text-slate-500 dark:border-white/10"><th className="px-3 py-2">Nama</th><th className="px-3 py-2">Email</th><th className="px-3 py-2">Peran</th><th className="px-3 py-2">Status</th></tr></thead>
                        <tbody>{list.map((u) => (
                            <tr key={u.id} className="border-b border-brand-50 dark:border-white/5">
                                <td className="px-3 py-3"><span className="flex items-center gap-3"><span className="grid h-8 w-8 place-items-center rounded-full bg-brand-100 font-bold text-brand-700">{u.name[0]}</span><b>{u.name}</b></span></td>
                                <td className="px-3 py-3 text-slate-500">{u.email}</td><td className="px-3 py-3 capitalize">{u.role}</td><td className="px-3 py-3"><Badge tone={TONE[u.status]}>{u.status}</Badge></td></tr>))}
                            {!list.length && <tr><td colSpan="4" className="px-3 py-8 text-center text-slate-500">Tidak ada pengguna yang cocok.</td></tr>}</tbody></table></div>
                )}
            </Card>
        </>
    );
}
