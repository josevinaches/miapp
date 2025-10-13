import { defineConfig } from 'vite'
import laravel from 'laravel-vite-plugin'

export default defineConfig({
    server: {
        host: true,   // accesible desde fuera del contenedor
        port: 5173,
        strictPort: true,
        hmr: {
            host: 'localhost', port: 5173,
            hmr: { host: 'localhost', port: 5173} // o la IP/host que usas en el navegador
        },
    },
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
})

