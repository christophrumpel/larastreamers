const defaultTheme = require('tailwindcss/defaultTheme');

module.exports = {
    purge: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './vendor/laravel/jetstream/**/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],
    theme: {
        extend: {
            fontFamily: {
                sans: ['Nunito', ...defaultTheme.fontFamily.sans],
            },
        },
        colors: {
            white: {
                DEFAULT: '#F8F8F8',
            },
            red: {
                dark: '#c42e47',
                DEFAULT: '#ff3e5e',
            },
            green: {
                DEFAULT: '#69db9e',
            },
            gray: {
                darkest: '#2d303e',
                darker: '#3f4457',
                dark: '#696e80',
                DEFAULT: '#979caf',
                light: '#b5b8c7',
                lighter: '#dee0ea',
                lightest: '#f7f8fc',
            },
            black: {
                DEFAULT: '#17181e'
            }
        }

    },

    variants: {
        extend: {
            opacity: ['disabled'],
            animation: ['hover', 'group-hover'],
            translate: ['group-hover'],
            responsive: ['group-hover']
        },
    },

    plugins: [
        require('@tailwindcss/forms'),
        require('@tailwindcss/typography'),
        require('@tailwindcss/aspect-ratio'),
    ],
};
