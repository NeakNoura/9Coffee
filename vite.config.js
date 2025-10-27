import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import path from 'path';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/sass/app.scss',
                'resources/js/app.js',
            ],
            refresh: true,
        }),
    ],
    build: {
        outDir: 'dist',  // <-- this tells Vercel where the built files will be
        emptyOutDir: true,
        rollupOptions: {
            input: {
                main: path.resolve(__dirname, 'resources/js/app.js'),
                style: path.resolve(__dirname, 'resources/sass/app.scss')
            }
        }
    }
});
