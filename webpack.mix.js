const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

//Сайт
mix.js("resources/js/app.js", "public/js").version();
mix.sass("resources/scss/app.scss", "public/css").version();
mix.css("resources/css/main.css", "public/css").version();
mix.disableNotifications();
