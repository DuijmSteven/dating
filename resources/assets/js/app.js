window.jQuery = require('jquery');
window.$ = window.jQuery;
require('bootstrap-sass');

$(document).ready(function() {
    console.log($.fn.tooltip.Constructor.VERSION);
});

require('./global_helpers');

