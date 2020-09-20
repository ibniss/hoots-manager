module.exports = {
    extends: [
        'standard',
        'plugin:vue/recommended',
        'prettier',
        'prettier/standard',
        'prettier/vue',
    ],
    plugins: ['prettier'],
    parserOptions: {
        parser: 'babel-eslint',
        ecmaVersion: '2020',
    },
    rules: {
        'prettier/prettier': 'error',
    },
    env: {
        es6: true,
    },
    globals: {
        route: 'readonly',
    },
};
