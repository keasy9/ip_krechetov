import "./bootstrap";
import { createApp } from "vue";

import App from "./App.vue";
import Icon from './components/Icon.vue';

createApp(App).mount("#app");

document.querySelectorAll<HTMLElement>('icon').forEach(icon => {
    createApp(Icon, {
        file: icon.getAttribute('file')
    }).mount(icon);
});
