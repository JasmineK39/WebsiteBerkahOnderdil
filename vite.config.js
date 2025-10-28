import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
  plugins: [
    laravel({
      input: ['resources/css/style.css', 'resources/js/main.js'],
      refresh: true,
    }),
    vue(),
    tailwindcss(),
  ],
});
