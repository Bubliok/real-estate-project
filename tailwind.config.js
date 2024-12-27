/** @type {import('tailwindcss').Config} */
const daisyui = require("daisyui");
module.exports = {
  content: [
    "./assets/**/*.js",
    "./templates/**/*.html.twig",
  ],
  theme: {
    extend: {},
  },
  plugins: [
      require('daisyui'),
  ],
}
