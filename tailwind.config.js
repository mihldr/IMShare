/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./assets/**/*.js",
    "./templates/**/*.html.twig",
    "./templates/**/*.js.twig",
  ],
  theme: {
    extend: {},
  },
  plugins: [],
  corePlugins: {
    container: false,
    preflight: false,
    borderCollapse: false
  },
}

