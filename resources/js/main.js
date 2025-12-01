import { createApp } from 'vue';
import { createPinia } from 'pinia';
import piniaPluginPersistedstate from 'pinia-plugin-persistedstate';
import App from './app.vue';
import router from './router';
import { useAuthStore } from './store/auth';

import '@fortawesome/fontawesome-free/css/all.min.css';
import '../css/style.css';
import './bootstrap';
import axios from 'axios';

const pinia = createPinia();
pinia.use(piniaPluginPersistedstate);

// Konfigurasi default axios
axios.defaults.baseURL = '/';
axios.defaults.headers.common['Accept'] = 'application/json';

// Interceptor: Setiap request keluar, tempelkan Token otomatis
axios.interceptors.request.use(config => {
    const token = localStorage.getItem('token');
    if (token) {
        config.headers.Authorization = `Bearer ${token}`;
    }
    return config;
});

// Capture OAuth token from URL fragment when Google redirects back.
// Example redirect URL: https://your-app/#token=xxx
(() => {
    try {
        const hash = window.location.hash; // e.g. "#token=abc&foo=bar"
        if (hash) {
            const params = new URLSearchParams(hash.replace(/^#/, ''));
            const tokenFromHash = params.get('token');
            if (tokenFromHash) {
                localStorage.setItem('token', tokenFromHash);
                // Remove token fragment from URL for cleanliness
                const cleanUrl = window.location.pathname + window.location.search;
                history.replaceState(null, '', cleanUrl);
                
                // Fetch user data from backend and populate auth store
                (async () => {
                    try {
                        const response = await axios.get('/api/user', {
                            headers: { Authorization: `Bearer ${tokenFromHash}` }
                        });
                        const authStore = useAuthStore();
                        authStore.setAuth(tokenFromHash, response.data);
                    } catch (error) {
                        console.error('Failed to fetch user data:', error);
                    }
                })();
            }
        }
    } catch (e) {
        console.error('OAuth token capture failed:', e);
    }
})();

const app = createApp(App).use(pinia).use(router);
app.mount('#app');
