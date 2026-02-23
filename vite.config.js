import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
    server: {
        cors: true,  // Tambah ini lagi
        hmr: {
            host: '127.0.0.1',
        },
        watch: {
            usePolling: true,
        },
        host: '127.0.0.1',
    },
});