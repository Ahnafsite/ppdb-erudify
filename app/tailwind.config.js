import preset from "./vendor/filament/support/tailwind.config.preset";
import { fontFamily } from "tailwindcss/defaultTheme";

/** @type {import('tailwindcss').Config} */
export default {
    presets: [preset],
    content: [
        "./app/Filament/**/*.php",
        "./resources/views/filament/**/*.blade.php",
        "./vendor/filament/**/*.blade.php",
        "./resources/js/**/*",
        "./vendor/awcodes/filament-tiptap-editor/resources/**/*.blade.php",

        "./resources/views/livewire/**/*.blade.php",
    ],
    theme: {
        extend: {
            fontFamily: {
                sans: ["Inter", ...fontFamily.sans],
            },
        },
    },
};
