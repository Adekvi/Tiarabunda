import React from 'react';
import { Link, Outlet, Navigate, useLocation, useNavigate } from 'react-router-dom';
import Sidebar from './Sidebar';
import { IconBtn, Icon } from './ui';
import { useApp } from '../context/app';

export default function Layout() {
    const { user, theme, setTheme, boxed, setBoxed, mini, setMini, setOpen } = useApp();
    const nav = useNavigate();
    const loc = useLocation();
    if (!user) return <Navigate to="/masuk" replace state={{ from: loc.pathname }} />;

    return (
        <>
            <a href="#sa-main" className="sr-only focus:not-sr-only focus:fixed focus:left-3 focus:top-3 focus:z-[100] focus:rounded-lg focus:bg-white focus:px-4 focus:py-2">Lewati ke konten</a>
            <Sidebar />
            <div className={`sa-main ${mini ? 'is-mini' : ''}`}>
                <header className="sticky top-0 z-30 flex h-[72px] items-center gap-2 border-b border-brand-100 bg-white/85 px-4 backdrop-blur-md dark:border-white/10 dark:bg-brand-900/85 sm:px-6">
                    <IconBtn n="menu" label="Buka menu" className="lg:hidden" onClick={() => setOpen(true)} />
                    <IconBtn n="panel" label={mini ? 'Lebarkan sidebar' : 'Ciutkan sidebar'} className="hidden lg:grid" onClick={() => setMini(!mini)} />
                    <a href="/" target="_blank" rel="noopener" className="ml-1 hidden items-center gap-1.5 rounded-full bg-accent-100 px-3.5 py-1.5 text-sm font-bold text-accent-600 sm:inline-flex dark:bg-accent-500/20 dark:text-accent-300">
                        Lihat website <Icon n="ext" size={14} /></a>
                    <div className="ml-auto flex items-center gap-1">
                        <IconBtn n="box" label={boxed ? 'Tata letak penuh (fluid)' : 'Tata letak kotak (boxed)'} className={`hidden md:grid ${boxed ? 'bg-brand-100 dark:bg-white/10' : ''}`} onClick={() => setBoxed(!boxed)} />
                        <IconBtn n={theme === 'dark' ? 'sun' : 'moon'} label={theme === 'dark' ? 'Mode terang' : 'Mode gelap'} onClick={() => setTheme(theme === 'dark' ? 'light' : 'dark')} />
                        <div className="ml-2 flex items-center gap-3 border-l border-brand-100 pl-4 dark:border-white/10">
                            <span className="grid h-9 w-9 place-items-center rounded-full bg-brand-600 text-sm font-extrabold text-white">{user.name[0]}</span>
                            <div className="hidden text-left leading-tight sm:block"><b className="block text-sm">{user.name}</b><small className="text-xs text-slate-500">{user.email}</small></div>
                            <IconBtn n="out" label="Keluar" onClick={() => nav('/keluar')} />
                        </div>
                    </div>
                </header>
                <main id="sa-main" className={`px-4 py-6 sm:px-6 lg:py-8 ${boxed ? 'mx-auto max-w-6xl' : ''}`}>
                    <Outlet />
                </main>
                <footer className="px-6 pb-6 text-xs text-slate-500">© {new Date().getFullYear()} Universitas Tiara Bunda · <Link to="/dokumentasi" className="font-semibold text-brand-600 dark:text-brand-300">Dokumentasi</Link></footer>
            </div>
        </>
    );
}
