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
        extend: {
            fontFamily: {
                sans: ['Inter', 'system-ui', 'sans-serif'],
                rawline: ['"Rawline"', 'sans-serif'],
                raleway: ['"Raleway"', 'sans-serif'],
            },
            fontSize: {
                xxs: '0.579rem',
                xs: '0.694rem',
                sm: '0.833rem',
                base: '1rem',
                xl: '1.2rem',
                '2xl': '1.44rem',
                '3xl': '1.728rem',
                '4xl': '2.074rem',
                '5xl': '2.488rem',
                '6xl': '2.986rem',
                '7xl': '3.583rem',
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
