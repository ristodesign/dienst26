import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                // Add more entry points if needed, e.g.:
                // 'resources/js/admin.js',
            ],
            refresh: true,
        }),
        tailwindcss(),  // ← Can be before or after laravel(); try before if issues arise
    ],
    // Optional: server config for better hot-reloading in some setups
    server: {
        host: 'localhost',
        port: 5173,
        hmr: {
            host: 'localhost',
        },
    },
});
