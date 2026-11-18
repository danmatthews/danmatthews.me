import {defineConfig} from "vite";
import laravel from "laravel-vite-plugin";
import tailwindcss from "@tailwindcss/vite";
import vue from "@vitejs/plugin-vue";
import {watch} from "vite-plugin-watch";
import ui from '@nuxt/ui/vite'

// In an Amp orb, .amp/services.yaml sets LARAVEL_DEV_SERVER: Vite owns the single portal
// and proxies every non-Vite request to `php artisan serve`, so pages, assets, and HMR
// share one origin.
const laravelDevServer = process.env.LARAVEL_DEV_SERVER;
const orbServer = laravelDevServer ? {
    origin: process.env.PUBLIC_URL?.replace(/\/$/, ''),
    allowedHosts: true,
    hmr: {clientPort: 443},
    proxy: {
        '^/(?!@vite/|@id/|@fs/|resources/|node_modules/|__vite_ping).*': {target: laravelDevServer},
    },
} : undefined;

export default defineConfig(({command}) => ({
    server: orbServer,
    plugins: [
        laravel({
            input: ["resources/css/app.css", "resources/js/app.js"],
            refresh: true,
        }),
        vue(),
        tailwindcss(),
        watch({
            pattern: "content/**/*.md",
            command: "php artisan graphein:build-posts",
            onInit: command === 'build',
        }),
        ui({
            router: 'inertia',
            colorMode: false
        })
    ],
}));
