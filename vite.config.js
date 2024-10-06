import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from "@vitejs/plugin-vue";
import svgLoader from 'vite-svg-loader'

export default defineConfig({
    plugins: [
        vue(),
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        svgLoader(),
    ],
    resolve: {
        alias: {
            //vue: "vue/dist/vue.esm-bundler.js",
        },
    },
});
