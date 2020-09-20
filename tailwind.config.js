const defaultTheme = require('tailwindcss/defaultTheme');

module.exports = {
    future: {
        removeDeprecatedGapUtilities: true,
        purgeLayersByDefault: true,
    },
    experimental: 'all',
    theme: {
        extend: {
            fontFamily: {
                sans: ['Inter var', ...defaultTheme.fontFamily.sans],
            },
        },
    },
    variants: {},
    plugins: [require('@tailwindcss/custom-forms')],
};
