import React, { useState } from 'react';
import { NavLink, useLocation } from 'react-router-dom';
import { MENU } from '../data/menu';
import { Icon } from './ui';
import { useApp } from '../context/app';

const hasActive = (item, path) => (item.children || []).some((c) => (c.to ? c.to === path : hasActive(c, path)));

/* Item menu rekursif → mendukung dropdown bertingkat tanpa batas kedalaman */
function Item({ item, depth, path }) {
    const [open, setOpen] = useState(() => hasActive(item, path));
    const { mini, setMini, setOpen: closeMobile } = useApp();
    const pad = { paddingLeft: depth ? 16 + depth * 12 : undefined };
    const base = 'sa-link flex w-full items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-semibold text-brand-100/80 transition hover:bg-white/10 hover:text-white';

    if (item.children) {
        const act = hasActive(item, path);
        return (
            <li>
                <button type="button" aria-expanded={open} style={pad}
                    onClick={() => { if (mini) setMini(false); setOpen(!open); }}
                    className={`${base} ${act ? 'text-white' : ''}`}>
                    {item.icon ? <Icon n={item.icon} /> : <span className="h-1.5 w-1.5 shrink-0 rounded-full bg-accent-500/80" />}
                    <span className="sa-label flex-1 text-left">{item.t}</span>
                    <Icon n="down" size={15} className={`sa-caret transition-transform ${open ? 'rotate-180' : ''}`} />
                </button>
                <div className={`sa-sub grid transition-[grid-template-rows] duration-300 ${open ? 'grid-rows-[1fr]' : 'grid-rows-[0fr]'}`}>
                    <ul className="overflow-hidden">{item.children.map((c) => <Item key={c.t} item={c} depth={depth + 1} path={path} />)}</ul>
                </div>
            </li>
        );
    }
    return (
        <li>
            <NavLink to={item.to} end style={pad} onClick={() => closeMobile(false)} title={item.t} className={({ isActive }) => `${base} ${isActive ? 'active' : ''}`}>
                {item.icon ? <Icon n={item.icon} /> : <span className="h-1.5 w-1.5 shrink-0 rounded-full bg-white/40" />}
                <span className="sa-label">{item.t}</span>
            </NavLink>
        </li>
    );
}

export default function Sidebar() {
    const { pathname } = useLocation();
    const { mini, open, setOpen } = useApp();
    return (
        <>
            {open && <div className="fixed inset-0 z-40 bg-brand-900/60 lg:hidden" onClick={() => setOpen(false)} />}
            <aside aria-label="Navigasi utama" className={`sa-sidebar fixed inset-y-0 left-0 z-50 flex flex-col text-white ${mini ? 'is-mini' : ''} ${open ? 'is-open' : ''}`}>
                <div className="flex h-[72px] shrink-0 items-center gap-3 px-5">
                    <img src="/landing/assets/img/logo.webp" alt="" width="40" height="40" className="h-10 w-10 rounded-xl bg-white p-1" />
                    <div className="sa-label leading-tight"><b className="block text-sm">Tiara Bunda</b><small className="text-xs text-brand-200">Superadmin</small></div>
                </div>
                <nav className="flex-1 overflow-y-auto px-3 pb-6">
                    <ul className="space-y-1">
                        {MENU.map((m, i) => m.group
                            ? <li key={i} className="sa-label px-3 pb-1 pt-5 text-xs font-bold text-accent-300">{m.group}</li>
                            : <Item key={m.t} item={m} depth={0} path={pathname} />)}
                    </ul>
                </nav>
            </aside>
        </>
    );
}
