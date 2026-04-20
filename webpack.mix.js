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
    .js('resources/js/admin/manageFeatured.js', 'public/js/admin')
    .postCss('resources/css/app.css', 'public/css')
    .postCss('resources/css/navigation/topNavbar.css', 'public/css/navigation')
    .postCss('resources/css/cart/cartPage.css', 'public/css/cart')
    .postCss('resources/css/admin/manageFeatured.css', 'public/css/admin')
    .postCss('resources/css/search/searchPage.css', 'public/css/search');