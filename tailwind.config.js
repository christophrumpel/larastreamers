const defaultTheme = require('tailwindcss/defaultTheme');

module.exports = {

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
