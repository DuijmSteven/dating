const elixir = require('laravel-elixir');

require('laravel-elixir-vue-2');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for your application as well as publishing vendor resources.
 |
 */

elixir(function (mix) {
    mix.sass('app.scss')
        .browserify('app.js')
        .version([
            'public/js/app.js',
            'public/css/app.css'
        ]);

    mix.copy('resources/assets/img', 'public/img', false);
    mix.copy('node_modules/font-awesome/fonts', 'public/build/fonts');
});