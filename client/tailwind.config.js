/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./resources/**/*.blade.php",
    "./node_modules/flowbite/**/*.js",
  ],
  theme: {
    extend: {
      colors: {
        'spotify': '#1ED760',
        'spotifyDark': '#181818',
        'spotifyDarker': '#131313',
        'spotifyCard': '#312828',
      }
    },
  },
  plugins: [
    require('flowbite/plugin')
  ],
}