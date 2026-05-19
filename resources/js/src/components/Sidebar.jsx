import React from 'react';
import { useEnclosure } from '../hooks/useEnclosure';

const Sidebar = () => {
    // 1. Panggil custom hook untuk mendapatkan data realtime enclosure
    const { currentEnclosure } = useEnclosure();

    return (
        <nav className="sidebar glass-card">
            <div className="sidebar-header">
                <i className="ph ph-leaf text-green"></i>
                <span className="brand">RAP Enclosure</span>
            </div>
            <ul className="nav-links">
                <li className="active">
                    <i className="ph ph-squares-four"></i>
                    {/* Tampilkan data dari context secara reactive */}
                    <span>{currentEnclosure?.name || 'Memuat...'}</span>
                </li>
                <li>
                    <i className="ph ph-chart-line-up"></i>
                    <span>Analitik</span>
                </li>
            </ul>
        </nav>
    );
};

export default Sidebar;
