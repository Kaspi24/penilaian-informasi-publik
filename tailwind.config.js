import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        "./node_modules/flowbite/**/*.js",
        './app/Livewire/**/*Table.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                'primary'     : '#2f3086',
                'primary-10'  : '#dcdceb',
                'primary-20'  : '#babad7',
                'primary-30'  : '#9798c2',
                'primary-40'  : '#7475ae',
                'primary-50'  : '#52529a',
                'primary-60'  : '#2f3086',
                'primary-70'  : '#272870',
                'primary-80'  : '#1f2059',
                'primary-90'  : '#181843',
                'primary-100' : '#10102d',
                'primary-110' : '#080816',
                
                'success'     : '#76C41D',
                'success-10'  : '#E4F3D2',
                'success-20'  : '#D1EBB4',
                'success-30'  : '#BAE18E',
                'success-40'  : '#A4D868',
                'success-50'  : '#8DCE43',
                'success-60'  : '#76C41D',
                'success-70'  : '#62A318',
                'success-80'  : '#4F8313',
                'success-90'  : '#3B620E',
                'success-100' : '#27410A',
                'success-110' : '#182706',

                'warning'     : '#f6d347',
                'warning-10'  : '#fef8e0',
                'warning-20'  : '#fcf0c2',
                'warning-30'  : '#fbe9a3',
                'warning-40'  : '#f9e284',
                'warning-50'  : '#f8da66',
                'warning-60'  : '#f6d347',
                'warning-70'  : '#cdb03b',
                'warning-80'  : '#a48d2f',
                'warning-90'  : '#7b6a24',
                'warning-100' : '#524618',
                'warning-110' : '#29230c',
                
                'danger'     : '#FF4248',
                'danger-10'  : '#FFD9DA',
                'danger-20'  : '#FFC0C2',
                'danger-30'  : '#FFA0A3',
                'danger-40'  : '#FF8185',
                'danger-50'  : '#FF6166',
                'danger-60'  : '#FF4248',
                'danger-70'  : '#D4373C',
                'danger-80'  : '#AA2C30',
                'danger-90'  : '#802124',
                'danger-100' : '#551618',
                'danger-110' : '#330D0E',
            },
        },
    },

    plugins: [forms,require('flowbite/plugin')],
};
