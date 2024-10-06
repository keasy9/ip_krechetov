import "./bootstrap";
import { createApp } from "vue";

import Icon from './components/Icon.vue';

document.querySelectorAll<HTMLElement>('icon').forEach(icon => {
    createApp(Icon, {
        file: icon.getAttribute('file')
    }).mount(icon);
});
