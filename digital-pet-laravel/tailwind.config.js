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
                primary: '#1c1c1c',  
                secondary: '#333333',
                neutral: '#2d2d2d',
                accent: '#FA8A00',  
                dark_accent: '#C76E00',
                text: '#FAFAFA',
                green: '#00FA8A',
                red: '#FA0D00',

                light_accent: '#0070FA',
              },
        },
    },

    plugins: [forms],
};
