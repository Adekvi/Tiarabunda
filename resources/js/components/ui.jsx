import React, { useEffect } from 'react';
import * as L from 'lucide-react';

const ICONS = { layout: L.LayoutDashboard, home: L.House, building: L.Landmark, cap: L.GraduationCap, service: L.HandHelping, news: L.Newspaper,
    award: L.Award, settings: L.Settings, image: L.Images, calendar: L.CalendarDays, users: L.Users, sparkle: L.Sparkles, help: L.CircleHelp,
    book: L.BookOpen, file: L.Files, grid: L.LayoutGrid, list: L.List, plus: L.Plus, search: L.Search, edit: L.Pencil, trash: L.Trash2,
    x: L.X, menu: L.Menu, sun: L.Sun, moon: L.Moon, out: L.LogOut, left: L.ChevronLeft, right: L.ChevronRight, down: L.ChevronDown,
    box: L.Maximize, save: L.Save, ext: L.ExternalLink, check: L.Check, panel: L.PanelLeft };
export const Icon = ({ n, size = 18, className = '' }) => { const C = ICONS[n] || L.Circle; return <C size={size} className={className} aria-hidden="true" />; };

export const Card = ({ title, action, children, className = '' }) => (
    <section className={`rounded-2xl border border-brand-100 bg-white p-5 shadow-sm shadow-brand-800/5 dark:border-white/10 dark:bg-brand-800/60 ${className}`}>
        {(title || action) && <header className="mb-4 flex items-center justify-between gap-3"><h2 className="text-base font-bold text-brand-800 dark:text-white">{title}</h2>{action}</header>}
        {children}
    </section>
);

const V = { primary: 'bg-brand-600 text-white hover:bg-brand-700', accent: 'bg-accent-500 text-brand-900 hover:bg-accent-600 hover:text-white',
    ghost: 'bg-transparent text-brand-600 ring-1 ring-inset ring-brand-300 hover:bg-brand-50 dark:text-brand-200 dark:ring-white/20 dark:hover:bg-white/10',
    danger: 'bg-red-600 text-white hover:bg-red-700' };
export const Btn = ({ v = 'primary', sm, icon, children, className = '', ...p }) => (
    <button type="button" {...p} className={`inline-flex items-center justify-center gap-2 rounded-full font-bold transition active:scale-[.98] disabled:opacity-50 ${sm ? 'px-3.5 py-1.5 text-sm' : 'px-5 py-2.5 text-sm'} ${V[v]} ${className}`}>
        {icon && <Icon n={icon} size={16} />}{children}
    </button>
);
export const IconBtn = ({ n, label, className = '', ...p }) => (
    <button type="button" aria-label={label} title={label} {...p} className={`grid h-10 w-10 place-items-center rounded-xl text-slate-600 transition hover:bg-brand-100 dark:text-slate-300 dark:hover:bg-white/10 ${className}`}><Icon n={n} /></button>
);
const T = { blue: 'bg-brand-100 text-brand-700 dark:bg-brand-500/25 dark:text-brand-100', orange: 'bg-accent-100 text-accent-600 dark:bg-accent-500/20 dark:text-accent-300',
    green: 'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-300', red: 'bg-red-100 text-red-700 dark:bg-red-500/20 dark:text-red-300', gray: 'bg-slate-100 text-slate-600 dark:bg-white/10 dark:text-slate-300' };
export const Badge = ({ tone = 'blue', children }) => <span className={`inline-block rounded-full px-2.5 py-0.5 text-xs font-bold ${T[tone]}`}>{children}</span>;

export const inputCls = 'w-full rounded-xl border border-brand-200 bg-white px-3.5 py-2.5 text-sm outline-none transition focus:border-brand-500 focus:ring-4 focus:ring-brand-100 dark:border-white/15 dark:bg-brand-900/60 dark:focus:ring-brand-500/30';
export const Field = ({ label, id, area, ...p }) => (
    <div><label htmlFor={id} className="mb-1.5 block text-sm font-semibold">{label}</label>
        {area ? <textarea id={id} rows={4} className={inputCls} {...p} /> : <input id={id} className={inputCls} {...p} />}</div>
);

export const Skel = ({ className = 'h-24' }) => <div className={`sa-skel ${className}`} />;

export function Modal({ title, onClose, children }) {
    useEffect(() => { const k = (e) => e.key === 'Escape' && onClose(); addEventListener('keydown', k); return () => removeEventListener('keydown', k); }, [onClose]);
    return (
        <div className="fixed inset-0 z-[80] grid place-items-center bg-brand-900/60 p-4 backdrop-blur-sm" onMouseDown={(e) => e.target === e.currentTarget && onClose()}>
            <div role="dialog" aria-modal="true" aria-label={title} className="sa-pop max-h-[90vh] w-full max-w-lg overflow-auto rounded-2xl bg-white p-6 shadow-2xl dark:bg-brand-800">
                <div className="mb-4 flex items-center justify-between"><h2 className="text-lg font-bold text-brand-800 dark:text-white">{title}</h2><IconBtn n="x" label="Tutup" onClick={onClose} /></div>
                {children}
            </div>
        </div>
    );
}

export const ViewToggle = ({ view, set }) => (
    <div className="inline-flex rounded-full bg-brand-100 p-1 dark:bg-white/10" role="group" aria-label="Tampilan">
        {[['grid', 'Grid'], ['list', 'Daftar']].map(([k, l]) => (
            <button key={k} type="button" aria-pressed={view === k} onClick={() => set(k)} className={`flex items-center gap-1.5 rounded-full px-3 py-1.5 text-sm font-semibold transition ${view === k ? 'bg-white text-brand-700 shadow dark:bg-brand-600 dark:text-white' : 'text-slate-500'}`}><Icon n={k} size={15} />{l}</button>
        ))}
    </div>
);

export const PageHead = ({ title, desc, children }) => (
    <div className="mb-6 flex flex-wrap items-end justify-between gap-4">
        <div><h1 className="text-2xl font-extrabold tracking-tight text-brand-800 dark:text-white sm:text-3xl">{title}</h1>{desc && <p className="mt-1 max-w-[65ch] text-sm text-slate-500 dark:text-slate-400">{desc}</p>}</div>
        <div className="flex flex-wrap items-center gap-2">{children}</div>
    </div>
);
