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
        'rick-green': '#66eaa1',    // ← Changed to match login.css
        'portal-blue': '#1778df',   // ← Changed to match login.css  
        'focus-blue': '#667eea',    // ← Added for input focus color
        'link-blue': '#667eea',     // ← Added for link color
        'link-purple': '#764ba2',   // ← Added for link hover color
        'morty-yellow': '#f2e53d',
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