module.exports = {
  content: ["./**/*.php", "./src/**/*.js"],
  plugins: [require("@tailwindcss/typography")],
  theme: {
    extend: {
      fontFamily: {
        roboto: ["'Roboto Condensed'", "sans-serif"],
        garamond: ["'EB Garamond'", "serif"],
      },
    },
  },
};
