import React, { useEffect, useState } from 'react';
import { Link, useLocation, useNavigate, Navigate } from 'react-router-dom';
import { Btn, Field } from '../components/ui';
import { useApp, useTitle } from '../context/app';

function Shell({ title, sub, children, foot }) {
    useTitle(title);
    return (
        <div className="grid min-h-screen lg:grid-cols-2">
            <aside className="relative hidden overflow-hidden bg-gradient-to-br from-brand-700 to-brand-900 p-12 text-white lg:flex lg:flex-col lg:justify-between">
                <div className="flex items-center gap-3"><img src="/landing/assets/img/logo.webp" alt="" width="44" height="44" className="rounded-xl bg-white p-1" /><b>Universitas Tiara Bunda</b></div>
                <div className="sa-arch mx-auto h-72 w-56" aria-hidden="true" />
                <div><h2 className="text-3xl font-extrabold leading-tight">Satu tempat untuk mengelola seluruh konten website kampus.</h2><p className="mt-3 max-w-sm text-brand-200">Berita, program studi, jalur PMB, dan galeri — diperbarui tanpa menyentuh kode.</p></div>
            </aside>
            <main className="grid place-items-center p-6">
                <div className="w-full max-w-sm">
                    <h1 className="text-3xl font-extrabold text-brand-800 dark:text-white">{title}</h1><p className="mb-6 mt-1 text-sm text-slate-500">{sub}</p>
                    {children}<div className="mt-6 text-center text-sm text-slate-500">{foot}</div>
                </div>
            </main>
        </div>
    );
}
const A = ({ to, children }) => <Link to={to} className="font-bold text-brand-600 hover:underline dark:text-brand-300">{children}</Link>;

function useForm(onOk) {
    const [err, setErr] = useState('');
    return { err, submit: (check) => (e) => { e.preventDefault(); const f = Object.fromEntries(new FormData(e.target)); const m = check(f); if (m) return setErr(m); setErr(''); onOk(f); } };
}
const Err = ({ m }) => (m ? <p role="alert" className="rounded-lg bg-red-50 px-3 py-2 text-sm text-red-700">{m}</p> : null);
const mailOk = (v) => /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v || '');

export function SignIn() {
    const { login, user } = useApp(); const nav = useNavigate(); const loc = useLocation();
    const f = useForm((v) => { login(v.email); nav(loc.state?.from || '/', { replace: true }); });
    if (user) return <Navigate to="/" replace />;
    return (
        <Shell title="Masuk" sub="Gunakan akun superadmin Anda." foot={<>Belum punya akun? <A to="/daftar">Daftar</A></>}>
            <form onSubmit={f.submit((v) => (!mailOk(v.email) ? 'Format email belum benar.' : v.password.length < 6 ? 'Password minimal 6 karakter.' : ''))} className="space-y-4" noValidate>
                <Field id="email" name="email" type="email" label="Email" autoComplete="email" defaultValue="superadmin@tiarabunda.test" />
                <Field id="password" name="password" type="password" label="Password" autoComplete="current-password" defaultValue="password" />
                <div className="text-right text-sm"><A to="/lupa-password">Lupa password?</A></div>
                <Err m={f.err} /><Btn type="submit" className="w-full">Masuk</Btn>
            </form>
        </Shell>
    );
}
export function SignUp() {
    const nav = useNavigate(); const { notify } = useApp();
    const f = useForm(() => { notify('Akun dibuat. Silakan masuk.'); nav('/masuk'); });
    return (
        <Shell title="Daftar" sub="Buat akun admin baru (perlu persetujuan superadmin)." foot={<>Sudah punya akun? <A to="/masuk">Masuk</A></>}>
            <form onSubmit={f.submit((v) => (!v.name ? 'Nama wajib diisi.' : !mailOk(v.email) ? 'Format email belum benar.' : v.password.length < 6 ? 'Password minimal 6 karakter.' : v.password !== v.confirm ? 'Konfirmasi password tidak sama.' : ''))} className="space-y-4" noValidate>
                <Field id="name" name="name" label="Nama lengkap" autoComplete="name" /><Field id="email" name="email" type="email" label="Email" autoComplete="email" />
                <Field id="password" name="password" type="password" label="Password" autoComplete="new-password" /><Field id="confirm" name="confirm" type="password" label="Ulangi password" autoComplete="new-password" />
                <Err m={f.err} /><Btn type="submit" className="w-full">Buat akun</Btn>
            </form>
        </Shell>
    );
}
export function Forgot() {
    const [sent, setSent] = useState(false);
    const f = useForm(() => setSent(true));
    return (
        <Shell title="Lupa password" sub="Kami kirim tautan reset ke email Anda." foot={<A to="/masuk">Kembali ke halaman masuk</A>}>
            {sent ? <p role="status" className="rounded-xl bg-emerald-50 p-4 text-sm text-emerald-700">Jika email terdaftar, tautan reset sudah dikirim. <A to="/reset-password">Buka halaman reset (contoh)</A></p> : (
                <form onSubmit={f.submit((v) => (!mailOk(v.email) ? 'Format email belum benar.' : ''))} className="space-y-4" noValidate>
                    <Field id="email" name="email" type="email" label="Email" autoComplete="email" /><Err m={f.err} /><Btn type="submit" className="w-full">Kirim tautan reset</Btn></form>)}
        </Shell>
    );
}
export function Reset() {
    const nav = useNavigate(); const { notify } = useApp();
    const f = useForm(() => { notify('Password diperbarui'); nav('/masuk'); });
    return (
        <Shell title="Reset password" sub="Buat password baru." foot={<A to="/masuk">Kembali ke halaman masuk</A>}>
            <form onSubmit={f.submit((v) => (v.password.length < 6 ? 'Password minimal 6 karakter.' : v.password !== v.confirm ? 'Konfirmasi password tidak sama.' : ''))} className="space-y-4" noValidate>
                <Field id="password" name="password" type="password" label="Password baru" autoComplete="new-password" /><Field id="confirm" name="confirm" type="password" label="Ulangi password" autoComplete="new-password" />
                <Err m={f.err} /><Btn type="submit" className="w-full">Simpan password</Btn></form>
        </Shell>
    );
}
export function SignOut() {
    const { logout } = useApp(); const nav = useNavigate();
    useEffect(() => { logout(); nav('/masuk', { replace: true }); }, []); // eslint-disable-line
    return null;
}
