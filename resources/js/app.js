import "./bootstrap";
import "../css/app.css";
import 'bootstrap/dist/css/bootstrap.min.css';
import 'bootstrap-icons/font/bootstrap-icons.css'
import 'bootstrap';
import { createApp, h } from "vue";
import { createInertiaApp } from "@inertiajs/vue3";


createInertiaApp({
    resolve: (name) => {
        const pages = import.meta.glob("./Pages/**/*.vue", { eager: true });
        return pages[`./Pages/${name}.vue`];
    },
    title: (title) => (title ? `${title} - Meus Contatos` : "Meus Contatos"),
    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
            .use(plugin)
            .mount(el);
    },
});
