import React, { useState, useEffect } from 'react';
import { useEnclosure } from '../hooks/useEnclosure';

const SettingsModal = ({ isOpen, onClose }) => {
    const { currentEnclosure, updateEnclosure, loading } = useEnclosure();
    const [name, setName] = useState('');
    const [toastMessage, setToastMessage] = useState('');

    // Sinkronisasi local state saat modal dibuka
    useEffect(() => {
        if (currentEnclosure) {
            setName(currentEnclosure.name);
        }
    }, [currentEnclosure]);

    const handleSubmit = async (e) => {
        e.preventDefault();
        if (!currentEnclosure) return;

        // Panggil fungsi updateEnclosure dari context
        const response = await updateEnclosure(currentEnclosure.id, { name });

        if (response.success) {
            setToastMessage('Nama enclosure berhasil diperbarui!');
            setTimeout(() => {
                setToastMessage('');
                onClose();
            }, 1500);
        } else {
            setToastMessage('Gagal memperbarui enclosure: ' + response.error);
        }
    };

    if (!isOpen) return null;

    return (
        <div className="modal-overlay">
            <div className="modal-content glass-card">
                <h2>Pengaturan Kandang</h2>
                {toastMessage && <div className="toast-notification">{toastMessage}</div>}
                
                <form onSubmit={handleSubmit}>
                    <div className="input-group">
                        <label>Nama Kandang</label>
                        <input 
                            type="text" 
                            value={name} 
                            onChange={(e) => setName(e.target.value)} 
                            disabled={loading}
                        />
                    </div>
                    <button type="submit" className="btn-primary" disabled={loading}>
                        {loading ? 'Menyimpan...' : 'Simpan Perubahan'}
                    </button>
                    <button type="button" onClick={onClose} className="btn-secondary" disabled={loading}>
                        Batal
                    </button>
                </form>
            </div>
        </div>
    );
};

export default SettingsModal;
