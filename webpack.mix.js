const mix = require('laravel-mix');
const Dotenv = require('dotenv-webpack');

// Load webpack plugins
mix.webpackConfig({
    plugins: [
        new Dotenv(),
    ],
});

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

mix.js('resources/js/app.js', 'public/ui-client/js')
   .sass('resources/sass/app.scss', 'public/ui-client/css');
