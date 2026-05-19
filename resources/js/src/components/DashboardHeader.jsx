import React from 'react';
import { useEnclosure } from '../hooks/useEnclosure';

const DashboardHeader = () => {
    // 1. Panggil custom hook untuk mendapatkan data enclosure
    const { currentEnclosure } = useEnclosure();

    return (
        <header className="topbar">
            <div className="header-left">
                {/* 2. Tampilkan secara reaktif */}
                <h1 id="page-title">{currentEnclosure?.name || 'Memuat...'}</h1>
            </div>
            <div className="header-right">
                <div className="status-indicator">
                    <span className="dot pulse-green"></span>
                    <span>Sistem Aktif</span>
                </div>
            </div>
        </header>
    );
};

export default DashboardHeader;
