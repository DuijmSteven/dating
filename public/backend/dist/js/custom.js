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

    // scrolls all the elements on the page with a class "scroll-bottom" to the bottom
    if ($('.scroll-bottom').length > 0) {
        $('.scroll-bottom').each(function(index, element) {
            $(element).scrollTop(element.scrollHeight);
        });
    }

    if (DP.currentRoute === 'operators_platform.conversations.show') {

        $(document).on('change','#attachment' , function(){
            var filename = $('#attachment').val().split('\\').pop();

            if (filename) {
                $('.attachment-path-container .attachment-path').text(filename);
                $('.attachment-path-container').show();
            } else {
                $('.attachment-path-container').hide();
            }

        })
    }

    /* Conversation notes */
    // Set user_id on modal form on click depending on user th note is intended for
    $('.js_toggle-note-modal').click(function() {
        var userId = $(this).data('userid');

        $('#note_user_id').val(userId);
    });
});

