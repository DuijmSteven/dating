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

mix.copy('resources/assets/img', 'public/img');
mix.copy('resources/assets/styleguide', 'public/styleguide');
mix.copy('node_modules/font-awesome/fonts', 'public/build/fonts');

mix.styles([
    'resources/assets/admin-lte/css/_all-skins.min.css',
    'resources/assets/admin-lte/css/AdminLTE.min.css',
    'resources/assets/admin-lte/css/bootstrap.min.css',
    'resources/assets/admin-lte/css/bootstrap-markdown.min.css',
    'resources/assets/admin-lte/css/bootstrap3-wysihtml5.min.css',
    'resources/assets/admin-lte/css/jquery-jvectormap-1.2.2.css',
    'resources/assets/admin-lte/css/morris.css',
    'resources/assets/admin-lte/css/jquery-ui.min.css',
], 'public/admin/css/plugins.css')
    .sourceMaps()
    .version();

mix.scripts([
    'resources/assets/admin-lte/js/jquery-3.1.1.min.js',
    'resources/assets/admin-lte/js/jquery-ui.min.js',
    'resources/assets/admin-lte/js/adminlte.min.js',
    'resources/assets/admin-lte/js/bootstrap.min.js',
    'resources/assets/admin-lte/js/bootstrap-markdown.js',
    'resources/assets/admin-lte/js/bootstrap3-wysihtml5.all.min.js',
    'resources/assets/admin-lte/js/bootstrap3-wysihtml5.all.min.js',
    'resources/assets/admin-lte/js/fastclick.js',
    'resources/assets/admin-lte/js/jquery.knob.js',
    'resources/assets/admin-lte/js/jquery.slimscroll.min.js',
    'resources/assets/admin-lte/js/jquery.sparkline.min.js',
    'resources/assets/admin-lte/js/jquery.sparkline.min.js',
    'resources/assets/admin-lte/js/morris.min.js',
    'resources/assets/admin-lte/js/moment.min.js',
    'resources/assets/admin-lte/js/raphael.min.js',
    'resources/assets/admin-lte/js/morris.min.js',
    'resources/assets/admin-lte/js/custom.js',
], 'public/admin/js/plugins.js')
    .sourceMaps()
    .version();

mix.copy('resources/assets/admin-lte/img', 'public/admin/img');