import "./bootstrap";
import "../css/app.css";
import {usePage} from "@inertiajs/vue3";
import {createApp, h} from "vue";
import {createInertiaApp} from "@inertiajs/vue3";
import {resolvePageComponent} from "laravel-vite-plugin/inertia-helpers";
import ui from '@nuxt/ui/vue-plugin'

const appName = document.getElementsByTagName("title")[0]?.innerText;

const page = usePage();
createInertiaApp({
    title: (title) =>
        title ? `${title} | ${page?.props?.appName}` : page?.props?.appName,
    resolve: (name) =>
        resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob("./Pages/**/*.vue"),
        ),
    setup({el, App, props, plugin}) {
        createApp({render: () => h(App, props)})
            .use(plugin)
            .use(ui)
            .mount(el);
    },
    defaults: {
        visitOptions: (href, options) => {
            return {viewTransition: true};
        },
    },
    progress: {
        color: "#0f172a",
    },
});
