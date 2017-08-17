const elixir = require('laravel-elixir');

require('laravel-elixir-vue-2');

require('laravel-elixir-vueify');

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

    mix.copy('resources/assets/styleguide', 'public/styleguide', false);
    mix.copy('node_modules/font-awesome/fonts', 'public/fonts');

    mix.copy('resources/assets/img', 'public/img', false);

    mix.styles([
        'admin/_all-skins.min.css',
        'admin/AdminLTE.min.css',
        'admin/bootstrap.min.css',
        'admin/bootstrap-markdown.min.css',
        'admin/bootstrap3-wysihtml5.min.css',
        'admin/jquery-jvectormap-1.2.2.css',
        'admin/morris.css',
        'admin/jquery-ui.min.css',
    ], 'public/admin/css/plugins.css')
       .version([
            'public/admin/css/plugins.css'
       ]);

    mix.scripts([
        'admin/jquery-3.1.1.min.js',
        'admin/jquery-ui.min.js',
        'admin/adminlte.min.js',
        'admin/bootstrap.min.js',
        'admin/bootstrap-markdown.js',
        'admin/bootstrap3-wysihtml5.all.min.js',
        'admin/bootstrap3-wysihtml5.all.min.js',
        'admin/fastclick.js',
        'admin/jquery.knob.js',
        'admin/jquery.slimscroll.min.js',
        'admin/jquery.sparkline.min.js',
        'admin/jquery.sparkline.min.js',
        'admin/morris.min.js',
        'admin/moment.min.js',
        'admin/raphael.min.js',
        'admin/morris.min.js',
        'admin/custom.js',
    ], 'public/admin/js/plugins.js')
        .version([
            'public/admin/js/plugins.js'
        ]);
});