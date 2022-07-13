/** @type {import('tailwindcss').Config} */
module.exports = {
  content: ["./*.{html,js,php}","./report/*.php"],
  theme: {
    extend: {
      fontFamily: {
        palanquin: ['palanquin', 'sans-serif']
      }
    },
  },
  plugins: [],
}
