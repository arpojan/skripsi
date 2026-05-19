import React, { createContext, useState, useCallback } from 'react';
import api from '../services/api'; // Asumsi ada API service

export const EnclosureContext = createContext();

export const EnclosureProvider = ({ children }) => {
    const [currentEnclosure, setCurrentEnclosure] = useState(null);
    const [loading, setLoading] = useState(false);
    const [error, setError] = useState(null);

    // Fungsi untuk mengubah enclosure aktif dan otomatis rerender semua child component
    const updateEnclosure = useCallback(async (id, payload) => {
        setLoading(true);
        setError(null);
        try {
            // 1. Frontend kirim update API & Backend update database
            const response = await api.put(`/enclosures/${id}`, payload);
            
            // Validasi response sesuai backend (response.data.data jika axios)
            const updatedData = response.data.data;
            
            // 2. Frontend update global enclosure state (Reactive Update)
            setCurrentEnclosure(updatedData);
            
            return { success: true, data: updatedData };
        } catch (err) {
            console.error('Failed to update enclosure:', err);
            setError(err.response?.data?.message || 'Gagal memperbarui data enclosure.');
            return { success: false, error: err };
        } finally {
            setLoading(false);
        }
    }, []);

    const setEnclosureState = useCallback((enclosure) => {
        setCurrentEnclosure(enclosure);
    }, []);

    return (
        <EnclosureContext.Provider 
            value={{ 
                currentEnclosure, 
                setCurrentEnclosure: setEnclosureState, 
                updateEnclosure, 
                loading, 
                error 
            }}
        >
            {children}
        </EnclosureContext.Provider>
    );
};
