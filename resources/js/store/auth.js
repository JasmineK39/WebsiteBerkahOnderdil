// resources/js/store/auth.js

import { defineStore } from 'pinia';
import axios from 'axios';

export const useAuthStore = defineStore('auth', {
    // 1. STATE: Status data saat ini
    // Kita langsung ambil dari LocalStorage agar saat refresh data tidak hilang
    state: () => ({
        token: localStorage.getItem('token') || null,
        user: JSON.parse(localStorage.getItem('user')) || null,
    }),

    // 2. GETTERS: Cara cepat mengecek status
    getters: {
        // Bernilai TRUE jika token ada, FALSE jika tidak
        isAuthenticated: (state) => !!state.token,
        
        // Cek apakah user adalah admin
        isAdmin: (state) => state.user?.role === 'admin',
    },

    // 3. ACTIONS: Fungsi pengubah data
    actions: {
        // Fungsi ini dipanggil manual dari LoginView.vue & RegisterView.vue
        // setelah sukses request ke API
        setAuth(token, user) {
            this.token = token;
            this.user = user;
            
            // Simpan ke browser (biar refresh gak ilang)
            localStorage.setItem('token', token);
            localStorage.setItem('user', JSON.stringify(user));
        },

        // Fungsi Logout
        async handleLogout() {
            try {
                // Hapus token di server Laravel
                // (Pastikan route /api/logout sudah dilindungi auth:sanctum)
                await axios.post('/logout');
            } catch (error) {
                console.error('Gagal logout di server (abaikan):', error);
            } finally {
                // WAJIB: Hapus data di browser (Client Side)
                this.token = null;
                this.user = null;
                localStorage.removeItem('token');
                localStorage.removeItem('user');
            }
        }
    }
});