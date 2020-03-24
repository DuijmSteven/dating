let mix = require('laravel-mix');

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

mix.js('resources/assets/js/app.js', 'public/js')
    .sass('resources/assets/sass/app.scss', 'public/css')
    .sourceMaps()
    .version();

mix.js('resources/assets/js/lp.js', 'public/js')
    .sourceMaps()
    .version();

mix.copy('resources/assets/styleguide', 'public/styleguide');
mix.copy('node_modules/bootstrap-sass/assets/fonts/bootstrap', 'public/admin/fonts');
mix.copy('resources/assets/img', 'public/img');

mix.styles([
    'resources/assets/css/admin/_all-skins.min.css',
    'resources/assets/css/admin/AdminLTE.min.css',
    'resources/assets/css/admin/bootstrap.min.css',
    'resources/assets/css/admin/bootstrap-markdown.min.css',
    'resources/assets/css/admin/bootstrap3-wysihtml5.min.css',
    'resources/assets/css/admin/jquery-jvectormap-1.2.2.css',
    'resources/assets/css/admin/morris.css',
    'resources/assets/css/admin/jquery-ui.min.css',
], 'public/admin/css/plugins.css')
    .sourceMaps()
    .version();

mix.scripts([
    'resources/assets/js/admin/adminlte.min.js',
    'resources/assets/js/admin/bootstrap-markdown.js',
    'resources/assets/js/admin/bootstrap3-wysihtml5.all.min.js',
    'resources/assets/js/admin/bootstrap3-wysihtml5.all.min.js',
    'resources/assets/js/admin/fastclick.js',
    'resources/assets/js/admin/jquery.knob.js',
    'resources/assets/js/admin/jquery.slimscroll.min.js',
    'resources/assets/js/admin/jquery.sparkline.min.js',
    'resources/assets/js/admin/moment.min.js',
    'resources/assets/js/admin/raphael.min.js'
], 'public/admin/js/plugins.js')
    .sourceMaps()
    .version();

mix.js(
    'resources/assets/js/admin/custom.js',
    'public/admin/js'
)
    .sass('resources/assets/sass/admin/custom.scss', 'public/admin/css')
    .sourceMaps()
    .version();

//mix.copy('resources/assets/admin-lte/img', 'public/admin/img');
