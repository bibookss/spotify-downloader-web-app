/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        "./resources/**/*.blade.php",
        "./node_modules/flowbite/**/*.js",
    ],
    theme: {
        extend: {
            opacity: {

            },
            colors: {
                'spotifyGreen': '#1ED760',
                'spotifyDark': '#181818',
                'spotifyDarker': '#121212',
                'spotifyCard': '#312828',
                'spotifyDescription': '#A2A2A2',
                'spotifyHover': '#D0D0D0',
            }
        },
    },
    plugins: [
        require('flowbite/plugin')
    ],
}
