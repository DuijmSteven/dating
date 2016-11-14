$(window).load(function () {

    /*
    *   CREATE BOT =================================================
    */
    // Instantiate date picker in  bot creation view
    $('#datepicker-bot-create').datepicker({
        'autoclose': true,
        'format': 'yyyy-mm-dd'
    });

    if ($('.js-autocompleteDutchCites').length > 0) {
        // Auto-completes Dutch cities in bot creation view text field
        $.getJSON(DP.baseUrl + '/' + 'backend/users/cities')
            .done(function (cities) {
                $(".js-autocompleteDutchCites").autocomplete({
                    source: cities
                })
            }).fail(function () {
            console.log("Error: Ajax call to users/cities endpoint failed");
        });
    }
});

