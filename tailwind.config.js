const defaultTheme = require('tailwindcss/defaultTheme');

module.exports = {
    purge: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './vendor/laravel/jetstream/**/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    mode: "jit",

    theme: {
        extend: {
            fontFamily: {
                sans: ['Nunito', ...defaultTheme.fontFamily.sans],
            },
        },
        colors: {
            red: {
                DEFAULT: '#ff3e5e',
            },
            gray: {
                darkest: '#2d303e',
                darker: '#3f4457',
                dark: '#696e80',
                DEFAULT: '#979caf',
                light: '#b5b8c7',
                lighter: '#dee0ea',
                lightest: '#f7f8fc',
            }
        }

    },

    variants: {
        extend: {
            opacity: ['disabled'],
        },
    },

    plugins: [
        require('@tailwindcss/forms'),
        require('@tailwindcss/typography'),
        require('@tailwindcss/aspect-ratio'),
    ],
};
