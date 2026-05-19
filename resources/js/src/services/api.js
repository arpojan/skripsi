import axios from 'axios';

// Konfigurasi standar Axios untuk Laravel backend
const api = axios.create({
    baseURL: '/api',
    headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json'
    }
});

// Contoh fungsi helper jika ingin memisahkan method API
export const updateEnclosureApi = (id, payload) => {
    return api.put(`/enclosures/${id}`, payload);
};

export default api;
