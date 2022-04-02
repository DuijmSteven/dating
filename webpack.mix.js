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

mix
    .js('resources/assets/js/sites/datevrij-nl/app.js', 'public/js/datevrij-nl/app.js')
    .js('resources/assets/js/sites/liefdesdate-nl/app.js', 'public/js/liefdesdate-nl/app.js')
    .js('resources/assets/js/sites/sweetalk-nl/app.js', 'public/js/sweetalk-nl/app.js')
    .sass('resources/assets/sass/datevrij-nl.scss', 'public/css/datevrij-nl/app.css')
    .sass('resources/assets/sass/liefdesdate-nl.scss', 'public/css/liefdesdate-nl/app.css')
    .sass('resources/assets/sass/sweetalk-nl.scss', 'public/css/sweetalk-nl/app.css')
    .sass('resources/assets/sass/sites/datevrij-nl/adsLps.scss', 'public/css/datevrij-nl/adsLps.css')
    .sass('resources/assets/sass/sites/liefdesdate-nl/adsLps.scss', 'public/css/liefdesdate-nl/adsLps.css')
    .sass('resources/assets/sass/sites/sweetalk-nl/adsLps.scss', 'public/css/sweetalk-nl/adsLps.css')
    .sourceMaps()
    .version();

mix.js('resources/assets/js/lp.js', 'public/js')
    .sourceMaps()
    .version();

mix.js('resources/assets/js/lps/ads/lp1.js', 'public/js/lps/ads')
    .sourceMaps()
    .version();

mix.js('resources/assets/js/lps/ads/lp2.js', 'public/js/lps/ads')
    .sourceMaps()
    .version();

mix.copy('resources/assets/styleguide', 'public/styleguide');
mix.copy('node_modules/bootstrap-sass/assets/fonts/bootstrap', 'public/admin/fonts');
mix.copy('resources/assets/img', 'public/img');
mix.copy('resources/assets/lps', 'public/lps');

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
    'resources/assets/js/admin/boostrap-datetimepicker.min.js',
    'resources/assets/js/admin/raphael.min.js'
], 'public/admin/js/plugins-force.js')
    .sourceMaps()
    .version();

mix.js(
    'resources/assets/js/admin/custom.js',
    'public/admin/js/custom-force.js'
)
    .sass('resources/assets/sass/admin/custom.scss', 'public/admin/css')
    .sourceMaps()
    .version();

//mix.copy('resources/assets/admin-lte/img', 'public/admin/img');
