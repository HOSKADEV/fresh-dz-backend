/** @type {import('tailwindcss').Config} */
export const content = ["./src/**/*.{html,js}"];
export const theme = {
  extend: {
    boxShadow: {
      myShadow: "0 0 10px 2px #ddd",
      myDarkShadow: "0 0 10px 2px #1f2937",
      homeShadow: "0 0 160px 100px #f9cbc2",
      darkHomeShadow: "0 0 160px 100px black",
    },
    screens: {
      sm: "500px",
    },
    colors: {
      secondary: "#6b7280",
      darksecondary: "#d1d5db",
      "light-gray": "gray-100", // Use default Tailwind colors without quotes
      spdarkbg: "#1f2937",
      dark: "#18181b",
      "overlay-bg": "rgb(39,39,42)", // Custom light gray for overlay
      "navbar-bg": "#ffffff", // Custom white for navbar background
      "border-gray": "#d1d5db", // Custom gray for borders
      "close-btn-bg": "#4b5563", // Custom dark gray for close button background
      "close-btn-text": "#ffffff", // Custom white for close button text
    },
  },
};
export const darkMode = "class";
export const plugins = [];
