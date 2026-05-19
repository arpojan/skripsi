import { useContext } from 'react';
import { EnclosureContext } from '../context/EnclosureContext';

export const useEnclosure = () => {
    const context = useContext(EnclosureContext);
    
    if (!context) {
        throw new Error('useEnclosure harus digunakan di dalam EnclosureProvider');
    }
    
    return context;
};
