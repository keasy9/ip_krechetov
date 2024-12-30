/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./resources/**/*.blade.php",
        "./modules/**/resources/**/*.blade.php",
        "./resources/**/*.ts",
    ],
    theme: {
        extend: {
            colors: {
                bg: '#242427',
            },
        },
    },
    plugins: [],
}

