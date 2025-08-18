/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./templates/**/*.html.twig",
    "./assets/**/*.js",
    "./src/**/*.php",
  ],
  theme: {
    extend: {
      colors: {
        'rick-green': '#97ce4c',
        'morty-yellow': '#f2e53d',
        'portal-blue': '#00b5d8',
        'space-dark': '#0f172a',
      },
      fontFamily: {
        'rick-morty': ['"Get Schwifty"', 'cursive'],
      },
    },
  },
  plugins: [
    require('@tailwindcss/forms'),
  ],
}
