const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */
   var node =  'node_modules/';
    mix.js('resources/admin/js/app.js', 'public/js/app.js')
   .sass('resources/admin/sass/style.scss', 'public/css/app.css');

   mix.js('resources/user/user.js', 'public/js/user.js')
   .sass('resources/user/assets/scss/style.scss', 'public/css/user.css');

   mix.combine([
    node + 'jquery/dist/jquery.min.js',
    node + 'toastr/build/toastr.min.js',
    'public/js/user.js'
  ], 'public/js/userBundle.js');

    mix.combine([
      node + 'jquery/dist/jquery.min.js',
      'public/js/app.js'
    ], 'public/js/admin.js');

    mix.version()
    .browserSync('market.test');