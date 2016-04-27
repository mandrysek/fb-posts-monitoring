var elixir = require('laravel-elixir');

var paths = {
    bootstrap : './node_modules/bootstrap-sass/assets/',
    jquery : './node_modules/jquery/',
    fontAwesome : './node_modules/font-awesome/'
};

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for our application, as well as publishing vendor resources.
 |
 */

elixir(function (mix) {

    mix.copy(paths.bootstrap + 'fonts/bootstrap/**', 'public/build/fonts');
    mix.copy(paths.fontAwesome + 'fonts/**', 'public/build/fonts');

    mix.sass(['main.scss', 'auth.scss', 'helpers.scss'], 'public/css/auth.css');
    mix.sass(['main.scss', 'app.scss', 'helpers.scss'], 'public/css/app.css');

    mix.coffee(['main.coffee', 'auth.coffee'], 'public/js/auth.js');
    mix.coffee(['main.coffee', 'app.coffee'], 'public/js/app.js');

    mix.scripts([
        paths.jquery + "dist/jquery.min.js",
        paths.bootstrap + "javascripts/bootstrap.js"
    ], 'public/js/auth.js');

    mix.scripts([
        paths.jquery + "dist/jquery.min.js",
        paths.bootstrap + "javascripts/bootstrap.js"
    ], 'public/js/app.js');

    mix.version(['css/app.css', 'js/app.js', 'css/auth.css', 'js/auth.js'])

});
