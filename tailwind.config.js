const colors = require("tailwindcss/colors");

/** @type {import('tailwindcss').Config} */
module.exports = {
    // Scan templates files to delete unused style and generate optimal CSS file
    darkMode: "class",
    content: [
        "./assets/**/*.js",
        "./templates/**/*.html.twig",
        "./node_modules/flowbite/**/*.js"
    ],
    theme: {
        extend: {
            colors: {
                primary: colors.green,
                secondary: colors.red,
                neutral: colors.sky,
                font: colors.gray,
            },
        },
    },
    plugins: [
        require('flowbite/plugin')
    ],
}