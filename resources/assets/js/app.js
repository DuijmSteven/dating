/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */
import isUndefined from "admin-lte/bower_components/moment/src/lib/utils/is-undefined";

window.Vue = require('vue/dist/vue.min.js');

import VuejsDialog from 'vuejs-dialog';

import VueMq from 'vue-mq';

Vue.use(require('vue-moment'));

Vue.use(VueMq, {
    breakpoints: {
        xs: 768,
        sm: 992,
        md: 1200,
        lg: Infinity,
    }
});

Vue.use(VuejsDialog);

require('./bootstrap');
require('bootstrap-datepicker');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

require('./vue/custom');

Vue.component('private-chat-manager', require('./vue/components/private-chat/PrivateChatManager.vue'));
Vue.component('private-chat', require('./vue/components/private-chat/PrivateChat.vue'));
Vue.component('chat-message', require('./vue/components/private-chat/ChatMessage.vue'));
Vue.component('chat-form', require('./vue/components/private-chat/ChatForm.vue'));
Vue.component('credits-count', require('./vue/components/CreditsCount.vue'));

require('./vue/vue-js-app');

/**
 * Other Javascript
 */
require("jquery-ui/ui/widgets/autocomplete");
require('disableautofill');

let cityList;

$(window).on('load', function () {
    require('./global_helpers');

    if ($('#JS--SearchBarForm')) {
       // $('#JS--SearchBarForm').disableAutoFill();
    }

    if ($('#JS--EditProfileUserDetailsForm')) {
        $('#JS--EditProfileUserDetailsForm').disableAutoFill();
    }

    // this will disable right-click on all images
    $("img").on("contextmenu",function(e){
        return false;
    });

    // this will disable dragging of all images
    $("img").mousedown(function(e){
        e.preventDefault()
    });

    $('#JS--search-city-input').keydown(function(e){
        if(e.keyCode === 13)
        {
            e.preventDefault();
            $('#JS--SearchBarForm').find('.Button').click();
        }
    });

    if ($('.JS--searchResultsHeader').length > 0) {
        clearTimeout($.data(this, 'removeHeadingTimer'));

        $.data(this, 'removeHeadingTimer', setTimeout(function() {
            $('.JS--searchResultsHeader').addClass('hidden');
        }, 2500));

        $(window).scroll(function() {
            clearTimeout($.data(this, 'scrollTimer'));

            $.data(this, 'scrollTimer', setTimeout(function() {
                if ($(window).scrollTop() > 60) {
                    $('.JS--searchResultsHeader').addClass('hidden');
                } else {
                    if ($('body').css('position') !== 'fixed') {
                        $('.JS--searchResultsHeader').removeClass('hidden');
                    }

                    clearTimeout($.data(this, 'removeHeadingTimer'));

                    $.data(this, 'removeHeadingTimer', setTimeout(function() {
                        $('.JS--searchResultsHeader').addClass('hidden');
                    }, 2500));
                }
            }, 100));
        });
    }

    if ($("#datepicker_dob").length > 0) {
        //Setup locale and bootstrap datepicker options
        // $.fn.datepicker.dates['nl'] = {
        //     days: ["Zondag", "Maandag", "Dinsdag", "Woensdag", "Donderdag", "Vrijdag", "Zaterdag"],
        //     daysShort: ["zo", "ma", "di", "wo", "do", "vr", "za"],
        //     daysMin: ["zo", "ma", "di", "wo", "do", "vr", "za"],
        //     months: ["Januari", "Februari", "Maart", "April", "Mei", "Juni", "Juli", "Augustus", "September", "Oktober", "November", "December"],
        //     today: "Vandaag",
        //     monthsTitle: "Maanden",
        //     clear: "Wissen",
        //     format: "dd-mm-yyyy"
        // };

        // $.fn.datepicker.defaults.language = DP.locale;

        $("#datepicker_dob").datepicker({
            weekStart: 1,
            autoclose: 1,
            startView: 2,
            minView: 2,
            useCurrent: false,
            defaultViewDate: new Date(1990, 11, 24),
            days: ["Zondag", "Maandag", "Dinsdag", "Woensdag", "Donderdag", "Vrijdag", "Zaterdag"],
            daysShort: ["zo", "ma", "di", "wo", "do", "vr", "za"],
            daysMin: ["zo", "ma", "di", "wo", "do", "vr", "za"],
            months: ["Januari", "Februari", "Maart", "April", "Mei", "Juni", "Juli", "Augustus", "September", "Oktober", "November", "December"],
            today: "Vandaag",
            monthsTitle: "Maanden",
            clear: "Wissen",
            format: "dd-mm-yyyy"
        });
    }

    if ($('.welcomeModal').length > 0) {
        $('.welcomeModal').modal('show');

        setTimeout(() => {
            $.get(DP.baseUrl + '/api/users/' + DP.authenticatedUser.id + '/milestones/accepted-welcome-message', function( data ) {
            });
        }, 1000);
    }

    if ($('.JS--ScrollTopButton').length > 0) {
        $('.JS--ScrollTopButton').click(() => {
            $('html, body').animate({scrollTop:0}, 500, 'swing');
        });

        $(window).scroll(() => {
            if ( $(window).scrollTop() > 400) {
                $('.JS--ScrollTopButton').removeClass('hidden');
            } else {
                $('.JS--ScrollTopButton').addClass('hidden');
            }
        });
    }

    if ($('.Shoutbox').length > 0) {
        require('./modules/shoutbox');
    }

    if ($('.JS--imagesWrapper').length > 0) {
        if ($('.JS--imagesWrapper').css('height') > 600) {
            $('.JS--imagesWrapper').addClass('overflown');
        }
    }

    if ($('.JS--Search__autoCompleteCites').length > 0) {
        require('./modules/search');
    }

    if ($('.JS--autoCompleteCites').length > 0) {
        // Auto-completes Dutch cities in bot creation view text field
        $.getJSON(DP.baseUrl + '/api/cities/nl')
            .done(function (response) {
                cityList = response.cities;

                $('.JS--autoCompleteCites').autocomplete({
                    source: response.cities,
                    minLength: 2
                })
            }).fail(function () {
        });

        const $searchRadiusInput = $('.JS--radiusSearchInput');

        if ($('.JS--autoCompleteCites.JS--bar').length > 0 && $('.JS--autoCompleteCites.JS--bar').val().length > 0) {
            $searchRadiusInput.removeClass('hidden');
        }

        $('.JS--autoCompleteCites.JS--bar').keyup(function () {
            if ($(this).val().length > 0) {
                if ($searchRadiusInput.hasClass('hidden')) {
                    $searchRadiusInput.removeClass('hidden');
                }
            } else {
                if (!$searchRadiusInput.hasClass('hidden')) {
                    $searchRadiusInput.addClass('hidden');
                }
            }
        });
    }

    if ($('.SearchBar .city').hasClass('has-error')) {
        $('.SearchBar').toggleClass('hidden');
    }

    if ($('.modalImage').length > 0) {
        $(".modalImage").on("click", function (event) {
            event.preventDefault();

            if ($(this).find('img').data('src') && $(this).find('img').data('src').length > 0) {
                $('#imagePreview').attr('src', $(this).find('img').data('src'));
            } else {
                $('#imagePreview').attr('src', $(this).find('img').attr('src')); // here asign the image to the modal when the user click the enlarge link
            }

            $('#imageModal').modal('show'); // imagemodal is the id attribute assigned to the bootstrap modal, then i use the show function
        });
    }

    if ($('.JS--galleryImage').length > 0) {
        $('.JS--galleryImage').click((event) => {
            if ($('.JS--galleryImage').length > 1) {
                let imageSourcesArraysBuilt = false;

                let clickedImageSrc = $(event.target).data('src');
                let imageSourcesArray = [];
                let justClickedAnArrow = false;

                if ($('.JS--galleryImage').length > 1) {
                    $('.JS--imageModalArrow').removeClass('hidden');
                }

                $('.JS--rightArrow').click(() => {
                    if (!justClickedAnArrow) {
                        justClickedAnArrow = true;

                        setTimeout(() => {
                            justClickedAnArrow = false;
                        }, 200);

                        if (!imageSourcesArraysBuilt) {
                            $('.JS--galleryImage').each((index, element) => {
                                let imageSource = $(element).data('src');

                                if (imageSource !== clickedImageSrc) {
                                    imageSourcesArray.push(imageSource);
                                }
                            });

                            imageSourcesArraysBuilt = true;
                        }

                        imageSourcesArray.push($('#imagePreview').attr('src'));
                        $('#imagePreview').attr('src', imageSourcesArray.shift());
                    }
                });

                $('.JS--leftArrow').click(() => {
                    if (!justClickedAnArrow) {
                        justClickedAnArrow = true;

                        setTimeout(() => {
                            justClickedAnArrow = false;
                        }, 200);

                        if (!imageSourcesArraysBuilt) {
                            $('.JS--galleryImage').each((index, element) => {
                                let imageSource = $(element).data('src');

                                if (imageSource !== clickedImageSrc) {
                                    imageSourcesArray.unshift(imageSource);
                                }
                            });

                            imageSourcesArraysBuilt = true;
                        }

                        imageSourcesArray.unshift($('#imagePreview').attr('src'));
                        $('#imagePreview').attr('src', imageSourcesArray.pop());
                    }
                });
            }
        });

        $('#imageModal').on('hidden.bs.modal', function () {
            $('.JS--imageModalArrow').addClass('hidden');

            $('.JS--rightArrow').unbind('click');
            $('.JS--leftArrow').unbind('click');
        });
    }

    if ($('.JS--creditpack').length > 0) {
        $('.JS--creditpack').click(function () {
            $('html, body').animate({scrollTop: $('.JS--paymentMethods__title').offset().top}, 1000, 'swing');

            $('.table-rose').removeClass('table-rose');
            $('.block-raised').removeClass('block-raised');
            $('.btn-white').removeClass('btn-white').addClass('btn-rose');

            $(this).addClass('block-raised');
            $(this).find('.table').addClass('table-rose');
            $(this).find('.btn').addClass('btn-white');

            let creditPackId = $('.block-raised').data('creditpack-id');

            //change cart values based on selected credits package
            $('span.cart-value').html($('.block-raised .JS--price').html());
            $('input[name="amount"]').val($('.block-raised .JS--price').html());
            $('span.cart-credits').html($('.block-raised b.package-credits').html());
            $('input[name="creditpack_id"]').val(creditPackId);
            $('span.cart-package').html($('.block-raised .category').html());
        });
    }

    if ($('.JS--banksContainer').length > 0) {
        $('input:radio[name="paymentMethod"]').change(function () {
            $('.JS--paymentMethodListItem').removeClass('selected');

            if ($(this).is(':checked') && $(this).val() === 'ideal') {
                $('.JS--banksContainer').show();
            } else {
                $('.JS--banksContainer').hide();
            }

            if ($(this).is(':checked')) {
                $(this).closest('.JS--paymentMethodListItem').addClass('selected');

                if ($(this).val() !== 'ideal') {
                    $('html, body').animate({scrollTop: $('.JS--finalizePaymentTitle').offset().top}, 1000, 'swing');
                }
            }
        });

        $('#bank').change(function () {
            if ($(this).val().length > 0) {
                $('html, body').animate({scrollTop: $('.JS--finalizePaymentTitle').offset().top}, 1000, 'swing');
            }
        });

    }

    if ($('.JS--searchToggleButton').length > 0) {
        $('.JS--searchToggleButton').click(() => {
            $('.JS--SearchBar').removeClass('hidden');
            $('.JS--searchToggleButton').toggleClass('pressed');
            Cookies.set('searchBarState', 'open');
        });

        $(document).on("mousedown", function(e)
        {
            var container = $(".JS--SearchBar");
            var autocompleteCities = $(".ui-autocomplete");

            // if the target of the click isn't the container nor a descendant of the container
            if ((!container.is(e.target) && container.has(e.target).length === 0) && (!autocompleteCities.is(e.target) && autocompleteCities.has(e.target).length === 0))
            {
                container.addClass('hidden');
                $('.JS--searchToggleButton').removeClass('pressed');
                Cookies.set('searchBarState', 'hidden');
            }
        });

    }

    if ($('.dropdown-submenu').length > 0) {
        $('.dropdown-submenu a.JS--showLanguagesSubmenu').on("click", function (e) {
            $(this).next('ul').toggle();
            e.stopPropagation();
            e.preventDefault();
        });
    }

});




