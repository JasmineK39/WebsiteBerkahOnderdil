import { createApp } from 'vue';
import { createPinia } from 'pinia';
import piniaPluginPersistedstate from 'pinia-plugin-persistedstate';
import App from './app.vue';
import router from './router';
import '../css/style.css';
import './bootstrap';
import axios from 'axios';

const pinia = createPinia();
pinia.use(piniaPluginPersistedstate);

// Konfigurasi default axios
axios.defaults.baseURL = '/';
axios.defaults.headers.common['Authorization'] = `Bearer ${localStorage.getItem('token')}`;

createApp(App).use(pinia).use(router).mount('#app');
