module.exports = {
  purge: [
      './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
      './vendor/laravel/jetstream/**/*.blade.php',
      './storage/framework/views/*.php',
      './resources/views/**/*.blade.php',
      './resources/views/**/*.blade.php',
      './resources/**/*.js',
  ],
  darkMode: false, // or 'media' or 'class'
  theme: {
    extend: {},
  },
  variants: {
    extend: {},
  },
  plugins: [],
}
