/** @type {import('tailwindcss').Config} */
export default {
    darkMode: "class",
    content: [
      './resources/views/**/*.blade.php',
      './resources/js/**/*.{js,jsx,ts,tsx}',
    ],
    theme: {
      screens: {
        sm: "576px",
        md: "768px",
        lg: "992px",
        xl: "1200px",
        xxl: "1400px",
      },
      extend: {
        fontFamily: {
          spaceGrotesk: ["Space Grotesk", "sans-serif"],
          plusJakarta: ["Plus Jakarta Sans", "sans-serif"],
          dmSans: ["DM Sans", "sans-serif"],
          body: ["Inter", "sans-serif"],
        },
        colors: {
          colorGreen: "#8eec31",
          darkGreen: "#219c0b",
          colorViolet: "#321CA4",
          colorItera: "#B82132",
        },
      },
    },
    plugins: [
      require('@tailwindcss/forms'),
      require('@tailwindcss/typography'),
    ],
  }
