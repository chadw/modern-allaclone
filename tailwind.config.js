module.exports = {
  content: [
    './resources/**/*.blade.php',
    './resources/**/*.js',
    './resources/**/*.vue',
  ],
  theme: {
    extend: {},
  },
  safelist: [
    'scrollbar',
    'scrollbar-thin',
    'scrollbar-thumb-sky-700',
    'scrollbar-track-sky-300',
  ],
  plugins: [
    require('daisyui'),
    require('tailwind-scrollbar'),
],
};
