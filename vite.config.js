import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/css/login.css',
                'resources/css/cadastro.css',
                'resources/css/forgotPassword.css',
                'resources/css/landing.css',
                'resources/css/navLanding.css',
                'resources/css/footer.css',
                'resources/css/dashboard.css',
                'resources/js/app.js',
            ],

            refresh: true,
        }),
    ],
});