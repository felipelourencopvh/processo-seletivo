/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'false',
    content: [
        // You will probably also need these lines
        './resources/**/**/*.blade.php',
        './resources/**/**/**/*.blade.php',
        './resources/**/**/*.js',
        './app/View/Components/**/**/*.php',
        './app/Livewire/**/**/*.php',

        // Add mary
        './vendor/robsontenorio/mary/src/View/Components/**/*.php',
    ],
    theme: {
        extend: {},
    },
    safelist: [
        {
            pattern: /badge-|(bg-primary|bg-success|bg-info|bg-error|bg-warning|bg-neutral|bg-purple|bg-yellow)/,
        },
    ],
    // Add daisyUI
    plugins: [require('daisyui')],
    daisyui: {
        themes: [
            {
                light: {
                    ...require('daisyui/src/theming/themes')['light'],
                    primary: '#0270CE',
                    'primary-content': '#FFF',
                    secondary: '#3B3E99',
                    'secondary-content': '#FFF',
                    accent: '#00ca67',
                    'accent-content': '#000f04',
                    neutral: '#1e1406',
                    'neutral-content': '#cdcac6',
                    // "base-100": "#F9F9F9",
                    // "base-200": "#2C5DB6",
                    // "base-300": "#045AA4",
                    'base-content': '#000616',
                    info: '#7239EA',
                    'info-content': '#FFF',
                    success: '#17C653',
                    'success-content': '#FFF',
                    warning: '#F6B100',
                    'warning-content': '#FFF',
                    error: '#F8285A',
                    'error-content': '#FFF',
                },
            },
        ],
    },
};
