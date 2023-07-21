/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./resources/**/*.blade.php",
  ],
  theme: {
    extend: {
      colors: {
        'spotify': '#1ED760',
        'spotifyDark': '#181818',
      }
    },
  },
  plugins: [],
}