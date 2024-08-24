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
                primary: '#1A1A1A',  
                secondary: '#848484',
                neutral: '#2d2d2d',
                accent: '#4FA8C0',  
                light_accent: '#63b2c7',
                dark_accent: '#3A8CA2',
                text: '#FAFAFA',
              },
        },
    },

    plugins: [forms],
};
