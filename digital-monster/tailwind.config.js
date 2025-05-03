import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                primary: '#262626',
                secondary: '#333333',
                accent: '#e47e00',
                neutral: '#545454',
                error: '#dc2626',
                info: '#F8EDAD',
                success: '#ADF8C7', 
                text: '#FAFAFA'
            }                       
        },
    },
    plugins: [forms],
};
