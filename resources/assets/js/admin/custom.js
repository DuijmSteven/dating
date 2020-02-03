$(window).load(function () {

    /*
     *   CREATE BOT =================================================
     */
    // Instantiate date picker in  bot creation view
    $('.datepicker__date').datepicker({
        'dateFormat': 'yy-mm-dd'
    });

    if ($('.JS--autoCompleteCites').length > 0) {
        // Auto-completes Dutch cities in bot creation view text field
        $.getJSON(DP.baseUrl + '/api/cities/nl')
            .done(function (response) {
                $(".JS--autoCompleteCites").autocomplete({
                    source: response.cities
                })
            }).fail(function () {
        });
    }

    // scrolls all the elements on the page with a class "scroll-bottom" to the bottom
    if ($('.scroll-bottom').length > 0) {
        $('.scroll-bottom').each(function (index, element) {
            $(element).scrollTop(element.scrollHeight);
        });
    }

    if (DP.currentRoute === 'operator-platform.conversations.show') {

        $(document).on('change', '#attachment', function () {
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
    $('.js_toggle-note-modal').click(function () {
        var userId = $(this).data('userid');

        $('#note_user_id').val(userId);
    });

    // scrolls all the elements on the page with a class "scroll-bottom" to the bottom
    if ($('#js-BotSelection').length > 0) {
        $('.js-fillBotData').click(function () {
            $('#js-goToConversation').attr(
                'href',
                DP.baseUrl +
                '/' +
                'operator-platform/conversations/' +
                $(this).closest('li').data('bot-id') +
                '/' +
                $('#js-peasant-profile').data('peasant-id')
            );

            $('#js-botProfileImage').attr('src', $(this).closest('li').data('bot-profile-image'));

            $('#js-botUsername').text($(this).closest('li').data('bot-username'));
            $('#js-botAge').text($(this).closest('li').data('bot-age'));
            $('#js-botStatus').text($(this).closest('li').data('bot-status'));
            $('#js-botCity').text($(this).closest('li').data('bot-city'));
            $('#js-botHeight').text($(this).closest('li').data('bot-height'));
            $('#js-botBodyType').text($(this).closest('li').data('bot-body-type'));
            $('#js-botEyeColor').text($(this).closest('li').data('bot-eye-color'));
            $('#js-botHairColor').text($(this).closest('li').data('bot-hair-color'));
            $('#js-botSmoking').text($(this).closest('li').data('bot-smoking'));
            $('#js-botDrinking').text($(this).closest('li').data('bot-drinking'));
            $('#js-aboutMe').text($(this).closest('li').data('bot-about-me'));
        });
    }

    if ($('.conversation__invisibleImages').length > 0) {
        $('.selectInvisibleImage').mousedown(function (event) {
            if ($(event.target).is(':checked')) {
                $(event.target).prop('checked', false);
                $(event.target).closest('form').find('textarea').addClass('hidden');
            } else {
                $(event.target).closest('.conversation__invisibleImages').find('.selectInvisibleImage').each(function (index, element) {
                    $(element).prop('checked', false);
                });
                $(event.target).prop('checked', true);

                $('.conversation__invisibleImages').find('textarea').addClass('hidden');
                $(event.target).closest('form').find('textarea').removeClass('hidden');
            }
        });
    }

});

