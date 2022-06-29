import laravel from 'laravel-vite-plugin'
import {defineConfig} from 'vite'

export default defineConfig({
    plugins: [
        laravel([
            'resources/css/app.css',
            'resources/js/app.js',
        ]),
    ],
});
