import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    safelist: [
        'bg-indigo-800',
        'bg-opacity-25',
        'bg-amber-300',
        'bg-opacity-10'
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                primary: '#262626',
                secondary: '#333333',
                tertiary: '#292929',
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
