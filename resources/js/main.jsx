import React from 'react';
import { createRoot } from 'react-dom/client';
import { BrowserRouter } from 'react-router-dom';
import '../../css/superadmin.css';
import '../../scss/superadmin.scss';
import { AppProvider } from './context/app';
import App from './App';

createRoot(document.getElementById('sa-root')).render(
    <BrowserRouter basename="/superadmin">
        <AppProvider>
            <App />
        </AppProvider>
    </BrowserRouter>
);
