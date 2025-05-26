module.exports = {
  content: ['./index.html', './src/**/*.{js,ts,jsx,tsx,vue}'], // Paths to all of your templates
  darkMode: false, // or 'media' or 'class'
  theme: {
    extend: {},
  },
  variants: {
    extend: {
      width: ['responsive', 'hover', 'focus'],
    },
  },
  plugins: [],
}