import { createApp } from 'vue';
import { createPinia } from 'pinia';
import piniaPluginPersistedstate from 'pinia-plugin-persistedstate';
import App from './app.vue';
import router from './router';


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

createApp(App).use(pinia).use(router).mount('#app');
