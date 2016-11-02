window.$ = window.jQuery = require('jquery');
var autocomplete = require( 'jquery-ui/autocomplete');
require('bootstrap-sass');

$(document).ready(function() {
    console.log($.fn.tooltip.Constructor.VERSION);
});

require('./global_helpers');

