/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./index.html",
    "./src/**/*.{js,ts,jsx,tsx}",
  ],
  theme: {
    extend: {
      colors: {
        'rac-gold': '#C8A45C',
        'rac-gold-light': '#D4B978',
        'rac-gold-dark': '#A0803A',
        'rac-dark': '#1A1A2E',
        'rac-dark-light': '#16213E',
        'rac-green': '#2D6A4F',
        'rac-green-light': '#40916C',
        'rac-red': '#C1121F',
        'rac-orange': '#E85D04',
        'rac-blue': '#0077B6',
      },
      fontFamily: {
        'title': ['"Cormorant Garamond"', 'serif'],
        'body': ['"Syne"', 'sans-serif'],
        'mono': ['"DM Mono"', 'monospace'],
      },
      animation: {
        'pulse-slow': 'pulse 3s cubic-bezier(0.4, 0, 0.6, 1) infinite',
        'fade-in': 'fadeIn 0.5s ease-out',
        'slide-up': 'slideUp 0.5s ease-out',
        'counter': 'counter 1s ease-out',
      },
      keyframes: {
        fadeIn: {
          '0%': { opacity: '0' },
          '100%': { opacity: '1' },
        },
        slideUp: {
          '0%': { transform: 'translateY(20px)', opacity: '0' },
          '100%': { transform: 'translateY(0)', opacity: '1' },
        },
      }
    },
  },
  plugins: [],
}
