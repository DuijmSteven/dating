$(window).load(function () {

    /*
    *   CREATE BOT =================================================
    */
    // Instantiate date picker in  bot creation view
    $('.datepicker__date').datepicker({
        'autoclose': true,
        'format': 'yyyy-mm-dd'
    });

    if ($('.js-autoCompleteDutchCites').length > 0) {
        // Auto-completes Dutch cities in bot creation view text field
        $.getJSON(DP.baseUrl + '/cities/nl')
            .done(function (response) {
                $(".js-autoCompleteDutchCites").autocomplete({
                    source: response.cities
                })
            }).fail(function () {
            console.log("Error: Ajax call to users/cities endpoint failed");
        });
    }
});

