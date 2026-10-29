import React from 'react';
import { Routes, Route, Navigate } from 'react-router-dom';
import Layout from './components/Layout';
import Dashboard from './pages/Dashboard';
import Content from './pages/Content';
import Users from './pages/Users';
import Calendar from './pages/Calendar';
import Gallery from './pages/Gallery';
import Help from './pages/Help';
import Showcase from './pages/Showcase';
import Docs from './pages/Docs';
import { SignIn, SignUp, Forgot, Reset, SignOut } from './pages/Auth';
import { NotFound, Forbidden, ServerError } from './pages/Errors';

export default function App() {
    return (
        <Routes>
            {/* publik */}
            <Route path="masuk" element={<SignIn />} />
            <Route path="daftar" element={<SignUp />} />
            <Route path="lupa-password" element={<Forgot />} />
            <Route path="reset-password" element={<Reset />} />
            <Route path="keluar" element={<SignOut />} />
            <Route path="403" element={<Forbidden />} />
            <Route path="500" element={<ServerError />} />
            {/* dilindungi: perlu login */}
            <Route element={<Layout />}>
                <Route index element={<Dashboard />} />
                <Route path="konten/:slug" element={<Content />} />
                <Route path="pengguna/:role" element={<Users />} />
                <Route path="pendaftar" element={<Navigate to="/pengguna/customer" replace />} />
                <Route path="galeri" element={<Gallery />} />
                <Route path="kalender" element={<Calendar />} />
                <Route path="bantuan" element={<Help />} />
                <Route path="showcase" element={<Showcase />} />
                <Route path="dokumentasi" element={<Docs />} />
                <Route path="*" element={<NotFound inside />} />
            </Route>
        </Routes>
    );
}
