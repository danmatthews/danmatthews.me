import {defineConfig} from "vite";
import laravel from "laravel-vite-plugin";
import tailwindcss from "@tailwindcss/vite";
import vue from "@vitejs/plugin-vue";
import {watch} from "vite-plugin-watch";
import ui from '@nuxt/ui/vite'

export default defineConfig({
    plugins: [
        laravel({
            input: ["resources/css/app.css", "resources/js/app.js"],
            refresh: true,
        }),
        vue(),
        tailwindcss(),
        watch({
            pattern: "content/**/*.md",
            command: "php artisan build:posts",
        }),
        ui({
            router: 'inertia',
            colorMode: false
        })
    ],
});
