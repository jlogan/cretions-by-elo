// tailwind.config.js
module.exports = {
  content: [
    "./index.php",
    "./**/*.php",      // Include all PHP files in subdirectories (just in case)
    "./**/*.html",     // Optional: if you also use HTML
    "./**/*.js",       // Optional: if Tailwind classes appear in JS
  ],
  theme: {
    extend: {},
  },
  plugins: [],
}