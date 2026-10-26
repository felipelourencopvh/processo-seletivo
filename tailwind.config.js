/** @type {import('tailwindcss').Config} */
import daisyui from "daisyui"

export default {
    // O site não tem modo escuro. "class" (e nunca pôr a classe .dark) desliga as variantes dark: do maryUI;
    // com "false" o Tailwind 3 gerava essas variantes SEM condição e o menu do usuário ficava transparente.
    darkMode: "class",
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./app/View/Components/**/*.php",
        "./app/Livewire/**/*.php",
        "./app/Filament/**/*.php",
        "./vendor/robsontenorio/mary/src/View/Components/**/*.php",
        "./vendor/filament/**/*.blade.php",
    ],
    theme: {
        extend: {
            // Mesmas fontes do site stxgov.com.br
            fontFamily: {
                sans: ['"Inter Tight"', "system-ui", "sans-serif"],
                display: ['"Sora"', '"Inter Tight"', "sans-serif"],
            },
            colors: {
                navy: { 700: "#0e2a3f", 800: "#0b2336", 900: "#071c2c" },
                rosa: { 50: "#fdf2f8", 100: "#fce7f3", 300: "#f9a8d4", 400: "#f472b6", 600: "#db2777", 700: "#be185d" },
                laranja: "#f36f21",
            },
        },
    },
    safelist: [
        {
            pattern: /badge-|(bg-primary|bg-success|bg-info|bg-error|bg-warning|bg-neutral|bg-purple|bg-yellow)/,
        },
    ],
    plugins: [daisyui],
    daisyui: {
        themes: [
            {
                light: {
                    primary: "#db2777",
                    "primary-content": "#FFF",
                    secondary: "#071C2C",
                    "secondary-content": "#FFF",
                    accent: "#F36F21",
                    "accent-content": "#FFF",
                    neutral: "#1e1406",
                    "neutral-content": "#cdcac6",
                    "base-100": "#ffffff",
                    "base-200": "#f4f6f8",
                    "base-300": "#e2e7ef",
                    "base-content": "#0b1f2e",
                    info: "#155BCB",
                    success: "#168821",
                    warning: "#FFCD07",
                    error: "#E52207",
                    "--rounded-box": "1rem",
                    "--rounded-btn": "0.625rem",
                },
            },
        ],
    },
}
