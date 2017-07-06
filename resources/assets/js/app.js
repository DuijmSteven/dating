window.jQuery = require('jquery');
window.$ = window.jQuery;
require('bootstrap-sass');
require('jquery-ui-dist/jquery-ui.js');

$(document).ready(function() {
    //console.log($.fn.tooltip.Constructor.VERSION);

    require('./global_helpers');

    if ($('.Shoutbox').length > 0) {
        require('./modules/shoutbox');
    }

    if ($('.Search').length > 0) {
        require('./modules/search');
    }
});



