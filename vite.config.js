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
                'resources/css/dashboardAdmin.css',
                'resources/css/sidebarAdmin.css',
                'resources/css/adminUsers.css',
                'resources/css/productManagement.css',
                'resources/css/createProduct.css',
                'resources/js/sidebarAdmin.js',
                'resources/js/adminUsers.js',
                'resources/js/productManagement.js',
                'resources/js/app.js',
            ],

            refresh: true,
        }),
    ],
});