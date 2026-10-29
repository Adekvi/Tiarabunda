import React, { createContext, useContext, useEffect, useState, useCallback } from 'react';

const Ctx = createContext(null);
export const useApp = () => useContext(Ctx);

const read = (k, d) => { try { return localStorage.getItem(k) ?? d; } catch { return d; } };
const write = (k, v) => { try { localStorage.setItem(k, v); } catch {} };

export function AppProvider({ children }) {
    const [theme, setTheme] = useState(() => (document.documentElement.classList.contains('dark') ? 'dark' : 'light'));
    const [boxed, setBoxed] = useState(() => read('sa.boxed', '0') === '1');
    const [mini, setMini] = useState(() => read('sa.mini', '0') === '1');
    const [open, setOpen] = useState(false); // sidebar mobile
    const [user, setUser] = useState(() => { try { return JSON.parse(read('sa.user', 'null')); } catch { return null; } });
    const [toast, setToast] = useState(null);

    useEffect(() => { document.documentElement.classList.toggle('dark', theme === 'dark'); write('sa.theme', theme); }, [theme]);
    useEffect(() => write('sa.boxed', boxed ? '1' : '0'), [boxed]);
    useEffect(() => write('sa.mini', mini ? '1' : '0'), [mini]);

    const notify = useCallback((msg, tone = 'ok') => { setToast({ msg, tone }); setTimeout(() => setToast(null), 2600); }, []);
    // Autentikasi tiruan. Ganti dengan panggilan ke Laravel (Sanctum / session) saat backend siap.
    const login = (email) => { const u = { name: 'Super Admin', email, role: 'superadmin' }; setUser(u); write('sa.user', JSON.stringify(u)); };
    const logout = () => { setUser(null); try { localStorage.removeItem('sa.user'); } catch {} };

    return (
        <Ctx.Provider value={{ theme, setTheme, boxed, setBoxed, mini, setMini, open, setOpen, user, login, logout, notify }}>
            {children}
            {toast && (
                <div role="status" className={`sa-pop fixed bottom-5 right-5 z-[90] rounded-xl px-4 py-3 text-sm font-semibold text-white shadow-xl ${toast.tone === 'ok' ? 'bg-brand-600' : 'bg-red-600'}`}>
                    {toast.msg}
                </div>
            )}
        </Ctx.Provider>
    );
}

// Simulasi pemuatan data dari server (mock). Ganti dengan fetch('/api/...') nanti.
export function useMock(data, ms = 450) {
    const [state, set] = useState({ loading: true, data: null });
    useEffect(() => { const t = setTimeout(() => set({ loading: false, data }), ms); return () => clearTimeout(t); }, []); // eslint-disable-line
    return state;
}

export function useTitle(t) {
    useEffect(() => { document.title = `${t} — Superadmin Tiara Bunda`; }, [t]);
}
