import preset from '../../../../vendor/filament/filament/tailwind.config.preset';

export default {
    presets: [preset],
    content: [
        './app/Filament/**/*.php',
        './app/Filament/**/**/*.php',
        './resources/views/filament/**/*.blade.php',
        './vendor/awcodes/filament-tiptap-editor/resources/**/*.blade.php',
        './vendor/filament/**/*.blade.php',
    ],
    plugins: [
        // ...
        require('tailwind-scrollbar'),
    ],
};
