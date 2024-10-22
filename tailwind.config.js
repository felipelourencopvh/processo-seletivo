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
        './app/Filament/**/*.php',
        './app/Filament/**/**/*.php',
        // Add mary
        './vendor/robsontenorio/mary/src/View/Components/**/*.php',
        './vendor/filament/**/*.blade.php',
    ],
    theme: {
        extend: {
            boxShadow: {
                sm: 'var(--surface-offset-none) var(--surface-offset-sm) var(--surface-blur-lg) rgba(var(--surface-shadow-color), var(--surface-opacity-xs))',
                md: 'var(--surface-offset-none) var(--surface-offset-md) var(--surface-blur-lg) rgba(var(--surface-shadow-color), var(--surface-opacity-xs))',
                lg: 'var(--surface-offset-none) var(--surface-offset-lg) var(--surface-blur-lg) rgba(var(--surface-shadow-color), var(--surface-opacity-xs))',
                xl: 'var(--surface-offset-none) var(--surface-offset-xl) var(--surface-blur-lg) rgba(var(--surface-shadow-color), var(--surface-opacity-xs))',
            },
            fontFamily: {
                sans: ['Inter', 'system-ui', 'sans-serif'],
                rawline: ['"Rawline"', 'sans-serif'],
                raleway: ['"Raleway"', 'sans-serif'],
            },
            fontSize: {
                xxs: 'var(--font-size-scale-down-03)',
                xs: 'var(--font-size-scale-down-02)',
                sm: 'var(--font-size-scale-down-01)',
                base: 'var(--font-size-scale-base)',
                lg: 'var(--font-size-scale-up-01)',
                xl: 'var(--font-size-scale-up-02)',
                '2xl': 'var(--font-size-scale-up-03)',
                '3xl': 'var(--font-size-scale-up-04)',
                '4xl': 'var(--font-size-scale-up-05)',
                '5xl': 'var(--font-size-scale-up-06)',
                '6xl': 'var(--font-size-scale-up-07)',
                '7xl': 'var(--font-size-scale-up-08)',
                '8xl': 'var(--font-size-scale-up-09)',
                '9xl': 'var(--font-size-scale-up-10)',
                '10xl': 'var(--font-size-scale-up-11)',
            },
        },
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
                    primary: '#1351b4',
                    'primary-content': '#FFF',
                    secondary: '#3B3E99',
                    'secondary-content': '#FFF',
                    accent: '#00ca67',
                    'accent-content': '#000f04',
                    neutral: '#1e1406',
                    'neutral-content': '#cdcac6',
                    // "base-100": "#F9F9F9",
                    // 'base-200': '#fff',
                    // "base-300": "#045AA4",
                    'base-content': '#333',
                    info: '#155BCB',
                    // 'info-content': '#FFF',
                    success: '#168821',
                    // 'success-content': '#FFF',
                    warning: '#FFCD07',
                    // 'warning-content': '#FFF',
                    error: '#E52207',
                    // 'error-content': '#FFF',
                    '--rounded-box': '1rem',
                    '--rounded-btn': '100em',
                },
            },
        ],
    },
};
