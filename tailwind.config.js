/** @type {import('tailwindcss').Config} */
const daisyui = require("daisyui");
module.exports = {
  content: [
    "./assets/**/*.js",
    "./templates/**/*.html.twig",
    "./node_modules/flowbite/**/*.js"
  ],
  theme: {
    extend: {},
  },
  plugins: [
      require('daisyui'),
    require('flowbite/plugin')
  ],
}
