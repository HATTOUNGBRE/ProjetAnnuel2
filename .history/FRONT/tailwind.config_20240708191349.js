/** @type {import('tailwindcss').Config} */
export default {
  content: [
    './src/**/*.{js,jsx,ts,tsx}',
    'node_modules/flowbite-react/**/*.{js,jsx,ts,tsx}'
  ],
  theme: {
    extend: {},
    colors:{
      pcs:{
        100: '#f0f0f0',
        200: '#05668D',
        250: '#028090',
        300: '#A5BE00',
        400: '#679436',
        500: '#F4F3EE',
        600: '#6C68AB',
        650: '#5B5B7F',
        700: '#4F4C7B',

      }
    }
  },
  plugins: [
    require('flowbite/plugin')
],
}

