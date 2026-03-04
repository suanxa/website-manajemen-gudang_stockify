import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    // 1. Tambahkan baris ini agar mode gelap dikontrol lewat class 'dark' di tag <html>
    darkMode: 'class',

    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.js', 
        './node_modules/flowbite/**/*.js' 
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                primary: {
                    50:  "#fff8f1",
                    100: "#feecdc",
                    200: "#fcd9bd",
                    300: "#fdba8c",
                    400: "#ff9f43",
                    500: "#ff7a00",
                    600: "#e56700",
                    700: "#cc5800",
                    800: "#a84300",
                    900: "#7a2f00",
                },
                cream: {
                    50:"#fdfaf6",
                    100:"#f8efe4",
                    200:"#f1dfc9",
                    300:"#e8cba8",
                    400:"#ddb88c",
                }
            }
        }
    },

    plugins: [
        forms,
        require('flowbite/plugin') 
    ],
};