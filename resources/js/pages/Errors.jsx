import React from 'react';
import { Link } from 'react-router-dom';
import { Btn } from '../components/ui';
import { useTitle } from '../context/app';

const COPY = {
    403: ['Akses ditolak', 'Akun Anda tidak punya izin membuka halaman ini. Hubungi superadmin untuk meminta akses.'],
    404: ['Halaman tidak ditemukan', 'Alamat yang Anda buka tidak ada atau sudah dipindahkan. Periksa ejaan atau kembali ke dashboard.'],
    500: ['Server sedang bermasalah', 'Terjadi kesalahan di sisi server. Coba muat ulang; jika tetap gagal, periksa storage/logs/laravel.log.'],
};
export function ErrorPage({ code, inside }) {
    const [t, d] = COPY[code];
    useTitle(`${code} ${t}`);
    return (
        <div className={`grid place-items-center px-4 text-center ${inside ? 'py-16' : 'min-h-screen'}`}>
            <div className="max-w-md">
                <p className="bg-gradient-to-br from-brand-600 to-accent-500 bg-clip-text text-8xl font-extrabold text-transparent">{code}</p>
                <h1 className="mt-2 text-2xl font-extrabold text-brand-800 dark:text-white">{t}</h1>
                <p className="mt-2 text-sm text-slate-500 dark:text-slate-400">{d}</p>
                <div className="mt-6 flex justify-center gap-2"><Link to="/"><Btn>Kembali ke dashboard</Btn></Link>{code === 500 && <Btn v="ghost" onClick={() => location.reload()}>Muat ulang</Btn>}</div>
            </div>
        </div>
    );
}
export const NotFound = ({ inside }) => <ErrorPage code={404} inside={inside} />;
export const Forbidden = () => <ErrorPage code={403} />;
export const ServerError = () => <ErrorPage code={500} />;
