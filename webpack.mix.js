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

mix.js('resources/js/app.js', 'public/js')
    .react()
    .copy('node_modules/bootstrap/dist/css/bootstrap.min.css', 'public/css/bootstrap.min.css')
    .copy('resources/css/app.css', 'public/css/app.css')
    // Copy individual CSS files without merging them
    .copy('resources/css/navigation/topNavbar.css', 'public/css/navigation/topNavbar.css')
    .copy('resources/css/games/gamesStorePage.css', 'public/css/games/gamesStorePage.css')
    .copy('resources/css/cart/cartPage.css', 'public/css/cart/cartPage.css')
    .copy('resources/css/profile/profilePage.css', 'public/css/profile/profilePage.css')
    .copy('resources/css/store/index.css', 'public/css/store/index.css');