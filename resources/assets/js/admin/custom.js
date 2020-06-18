import $ from 'jquery';
window.$ = window.jQuery = $;

require("jquery-ui/ui/widgets/autocomplete");

require('moment');
require('bootstrap');
require('bootstrap-datepicker');

$(window).on('load', function () {

    /*
     *   CREATE BOT =================================================
     */
    // Instantiate date picker in  bot creation view
    $('.datepicker__date:not(.defaultToPresent)').datepicker({
        weekStart: 1,
        autoclose: 1,
        startView: 2,
        minView: 2,
        useCurrent: false,
        defaultViewDate: new Date(1990, 11, 24),
        format: "dd-mm-yyyy"
    });

    $('.datepicker__date.defaultToPresent').datepicker({
        weekStart: 1,
        autoclose: 1,
        startView: 2,
        minView: 2,
        useCurrent: false,
        defaultViewDate: new Date(),
        format: "dd-mm-yyyy"
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

    if ($('input[type="submit"]').length > 0 || $('button[type="submit"]').length > 0) {
        $('form').submit(function(){
            $('input[type=submit]', this).attr('disabled', 'disabled');
            $('button[type="submit"]', this).attr('disabled', 'disabled');
        });
    }

    if ($('.JS--showConversation').length > 0 && $('.JS--operatorCountdown').length > 0) {
        var lockedDate = new Date($('.JS--showConversation').data('locked-at'));

        var countdownTime = (new Date($('.JS--showConversation').data('locked-at'))).setMinutes(lockedDate.getMinutes() + 6);

        var redirect = false;
        // start the countdown timer
        var x = setInterval(function() {
            var now = new Date().getTime();
            var timeLeft = countdownTime - now;

            var minutes = Math.floor((timeLeft % (1000 * 60 * 60)) / (1000 * 60));
            var seconds = Math.floor((timeLeft % (1000 * 60)) / 1000);

            if (minutes < 1 && !$('.JS--operatorCountdown').hasClass('warning')) {
                $('.JS--operatorCountdown').addClass('warning');
            }

            if (minutes === 0 && seconds === 0 || (minutes < 0 || seconds < 0)) {
                if (!redirect) {
                    window.location = DP.operatorDashboardRoute;
                    redirect = true;
                }
            }

            $('.JS--operatorCountdown').html(minutes + "m " + seconds + "s");
        }, 1000);

        const conversationId = $('.JS--showConversation').data('conversation-id');

        if (localStorage.getItem('conversation' + conversationId)) {
            $('.JS--sendMessageTextarea').val(localStorage.getItem('conversation' + conversationId));
        }

        $('.JS--createLogItemForm').submit((event) => {
            const textAreaValue = $('.JS--sendMessageTextarea').val();

            if (textAreaValue.length > 0) {
                localStorage.setItem('conversation' + conversationId, textAreaValue);
            }

            return true;
        });

        $('.JS--sendMessageForm').submit((event) => {
            if (localStorage.getItem('conversation' + conversationId)) {
                localStorage.removeItem('conversation' + conversationId)
            }

            return true;
        });
    }

    if ($('#js-BotSelection').length > 0) {
        $('.js-fillBotData').click(function () {

            const isPublicChatView = $(this).closest('#js-BotSelection').hasClass('js-publicChat');

            if (isPublicChatView) {
                const botId = $(this).closest('li').data('bot-id');
                $('#sender_id_input').val(botId);
            }

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

    if ($('#js-PeasantSelection').length > 0) {
        $('.js-fillPeasantData').click(function () {
            $('#js-goToConversation').attr(
                'href',
                DP.baseUrl +
                '/' +
                'operator-platform/conversations/' +
                $(this).closest('li').data('peasant-id') +
                '/' +
                $('#js-peasant-profile').data('bot-id')
            );

            $('#js-peasantProfileImage').attr('src', $(this).closest('li').data('peasant-profile-image'));

            $('#js-peasantUsername').text($(this).closest('li').data('peasant-username'));
            $('#js-peasantAge').text($(this).closest('li').data('peasant-age'));
            $('#js-peasantStatus').text($(this).closest('li').data('peasant-status'));
            $('#js-peasantCity').text($(this).closest('li').data('peasant-city'));
            $('#js-peasantHeight').text($(this).closest('li').data('peasant-height'));
            $('#js-peasantBodyType').text($(this).closest('li').data('peasant-body-type'));
            $('#js-peasantEyeColor').text($(this).closest('li').data('peasant-eye-color'));
            $('#js-peasantHairColor').text($(this).closest('li').data('peasant-hair-color'));
            $('#js-peasantSmoking').text($(this).closest('li').data('peasant-smoking'));
            $('#js-peasantDrinking').text($(this).closest('li').data('peasant-drinking'));
            $('#js-aboutMe').text($(this).closest('li').data('peasant-about-me'));
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

    if ($('.DashboardWidget .showMore').length > 0) {
        $('.DashboardWidget .showMore').click(function ($event) {
            $event.preventDefault();

            $(this).addClass('hidden');

            $(this).closest('.DashboardWidget.expandable')
                .find('.showLess')
                .removeClass('hidden');

            $(this).closest('.DashboardWidget.expandable')
                .find('.defaultHidden')
                .removeClass('hidden');

        });

        $('.DashboardWidget .showLess').click(function ($event) {
            $event.preventDefault();

            $(this).addClass('hidden');

            $(this).closest('.DashboardWidget.expandable')
                .find('.showMore')
                .removeClass('hidden');

            $(this).closest('.DashboardWidget.expandable')
                .find('.defaultHidden')
                .addClass('hidden');
        });
    }



});

