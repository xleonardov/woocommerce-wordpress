module.exports = {
  content: ["./**/*.php", "./src/**/*.js"],
  plugins: [require("@tailwindcss/typography")],
  theme: {
    extend: {
      fontFamily: {
        roboto: ["'Roboto Condensed'", "sans-serif"],
        garamond: ["'EB Garamond'", "serif"],
      },
      colors: {
        amarelo: "#ff5a3c",
        primary: "#000",
      },
      aspectRatio: {
        modalidades: "389 / 480",
        "1280/852": "1280 / 852",
        "852/1280": "852 / 1280",
        wide: "3/1",
        tall: "1/2",
        productCard: "1000 / 1076",
        productImg: "1800 / 2560",
        gamaCard: "598 / 518",
        bannerHome: "2560 / 1707",
      },
      transitionProperty: {
        top: "top",
      },
      animation: {
        fadein: "0.3s ease-in-out 0s 1 fadeIn",
      },
      keyframes: (theme) => ({
        fadeIn: {
          from: { opacity: 0 },
          to: { opacity: 1 },
        },
      }),
    },
  },
};
