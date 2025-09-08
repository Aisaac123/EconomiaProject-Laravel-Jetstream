import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/js/app.js', 'resources/js/passkey-js.js', 'resources/css/filament/app/theme.css'],
            refresh: true,
        }),
        tailwindcss(),
    ],
    input: [
        'resources/css/filament/app/theme.css',
    ]
});
