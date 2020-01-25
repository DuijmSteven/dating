/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */
import isUndefined from "admin-lte/bower_components/moment/src/lib/utils/is-undefined";

window.Vue = require('vue/dist/vue.js');

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
require("jquery-ui/ui/widgets/datepicker");
require("jquery-ui/ui/widgets/autocomplete");

let cityList;

function getCoordinatesAndFillInputs() {
    var geocoder = new google.maps.Geocoder();

    if ($.inArray($('.JS--autoCompleteCites').val(), cityList) > 0 || isUndefined(cityList)) {
        geocoder.geocode({'address': $('.JS--autoCompleteCites').val() + ', nl'}, function (results, status) {
            if (status === google.maps.GeocoderStatus.OK) {
                $('.js-hiddenLatInput').val(results[0].geometry.location.lat());
                $('.js-hiddenLngInput').val(results[0].geometry.location.lng());
            } else {
                $('.js-hiddenLatInput').val('');
                $('.js-hiddenLngInput').val('');
            }
        });
    } else {
        $('.js-hiddenLatInput').val('');
        $('.js-hiddenLngInput').val('');
    }
}

$(window).on('load', function () {
    require('./global_helpers');

    if ($('.Shoutbox').length > 0) {
        require('./modules/shoutbox');
    }

    if ($('.JS--Search__autoCompleteCites').length > 0) {
        require('./modules/search');
    }

    if ($('#JS--datepicker__date').length > 0) {
        $('#JS--datepicker__date').datepicker({
            dateFormat: 'dd-mm-yy'
        });
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

        getCoordinatesAndFillInputs();

        $('.JS--autoCompleteCites').keyup(function () {
            getCoordinatesAndFillInputs();
        });
    }

    if ($('.JS--SearchBar').length > 0 || $('.JS--Edit-Profile').length > 0) {
        getCoordinatesAndFillInputs();

        $('.JS--autoCompleteCites').keyup(function () {
            getCoordinatesAndFillInputs();
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
            let imageSourcesArraysBuilt = false;

            if ($('.JS--galleryImage').length > 1) {
                let clickedImageSrc = $(event.target).data('src');

                let leftImageSourcesArray = [];
                let rightImageSourcesArray = [];

                $('.JS--imageModalArrow').removeClass('hidden');

                $('.JS--rightArrow').click(() => {
                    if (!imageSourcesArraysBuilt) {
                        $('.JS--galleryImage').each((index, element) => {
                            let imageSource = $(element).data('src');

                            if (imageSource !== clickedImageSrc) {
                                rightImageSourcesArray.push(imageSource);
                            }
                        });

                        leftImageSourcesArray.push(clickedImageSrc);
                        imageSourcesArraysBuilt = true;
                    }

                    if (rightImageSourcesArray.length > 0) {
                        leftImageSourcesArray.push($('#imagePreview').attr('src'));
                        $('#imagePreview').attr('src', rightImageSourcesArray.shift());
                    } else {
                        $('.JS--imageModalArrow.JS--rightArrow').addClass('hidden');
                    }

                    if (leftImageSourcesArray.length > 0) {
                        $('.JS--imageModalArrow.JS--leftArrow').removeClass('hidden');
                    }
                });

                $('.JS--leftArrow').click(() => {
                    if (!imageSourcesArraysBuilt) {
                        $('.JS--galleryImage').each((index, element) => {
                            let imageSource = $(element).data('src');

                            if (imageSource !== clickedImageSrc) {
                                leftImageSourcesArray.unshift(imageSource);
                            }
                        });

                        rightImageSourcesArray.unshift(clickedImageSrc);
                        imageSourcesArraysBuilt = true;
                    }

                    if (leftImageSourcesArray.length > 0) {
                        rightImageSourcesArray.unshift($('#imagePreview').attr('src'));
                        $('#imagePreview').attr('src', leftImageSourcesArray.pop());
                    } else {
                        $('.JS--imageModalArrow.JS--leftArrow').addClass('hidden');
                    }

                    if (rightImageSourcesArray.length > 0) {
                        $('.JS--imageModalArrow.JS--rightArrow').removeClass('hidden');
                    }
                });
            }

        });

        $('#imageModal').on('hidden.bs.modal', function () {
            $('.JS--imageModalArrow').addClass('hidden');
        });
    }

    if ($('.JS--creditpack').length > 0) {
        $('.JS--creditpack').click(function () {
            $('.table-rose').removeClass('table-rose');
            $('.block-raised').removeClass('block-raised');
            $('.btn-white').removeClass('btn-white').addClass('btn-rose');

            $(this).addClass('block-raised');
            $(this).find('.table').addClass('table-rose');
            $(this).find('.btn').addClass('btn-white');

            let creditPackId = $('.block-raised').data('creditpack-id');

            //change cart values based on selected credits package
            $('span.cart-value').html($('.block-raised .block-caption span').html());
            $('input[name="amount"]').val($('.block-raised .block-caption span').html());
            $('span.cart-credits').html($('.block-raised b.package-credits').html());
            $('input[name="creditpack_id"]').val(creditPackId);
            $('span.cart-package').html($('.block-raised .category').html());
        });
    }

    if ($('.JS--banksContainer').length > 0) {
        $('input:radio[name="paymentMethod"]').change(function () {
            if ($(this).is(':checked') && $(this).val() == 'ideal') {
                $('.JS--banksContainer').show();
            } else
                $('.JS--banksContainer').hide();
        });
    }

    if ($('.JS--banksContainer').length > 0) {
        $('input:radio[name="paymentMethod"]').change(function () {
            if ($(this).is(':checked') && $(this).val() == 'ideal') {
                $('.JS--banksContainer').show();
            } else
                $('.JS--banksContainer').hide();
        });
    }

    if ($('.JS--UserSummary').length > 0) {
        $('.JS--UserSummary__user-image').each((index, element) => {
            fitGeneralImageToContainer($(element));
        });

        $(window).resize(() => {
            setTimeout(() => {
                $('.JS--UserSummary__user-image').each((index, element) => {
                    fitGeneralImageToContainer($(element));
                });

                if ($('.JS--UserSummary__otherImages').length > 0) {
                    $('.JS--UserSummary__nonProfileImageWrapper').each((index, element) => {
                        fitGeneralImageToContainer($(element));
                    });
                }
            }, 20);
        });

        if ($('.JS--UserSummary__otherImages').length > 0) {
            $('.JS--UserSummary__nonProfileImageWrapper').each((index, element) => {
                fitGeneralImageToContainer($(element));
            });
        }
    }

    if ($('.JS--searchToggle').length > 0) {
        $('.JS--searchToggle').click((event) => {
            event.preventDefault();
            $('.JS--SearchBar').toggleClass('hidden');
            $('.JS--searchToggleButton').toggleClass('pressed');

            if ($('.JS--SearchBar').hasClass('hidden')) {
                Cookies.set('searchBarState', 'hidden');
            } else {
                Cookies.set('searchBarState', 'open');
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

function fitGeneralImageToContainer(element) {
    var containerHeight = element.height();
    var containerWidth = element.width();

    const $image = $(element).find('img');

    var imageHeight = $image.height();

    if (imageHeight < containerHeight) {
        $image.css("width", "auto");
        $image.css("height", containerHeight);

        const imageWidth = $image.css('width');

        const imageWidthAsInteger = parseInt(imageWidth.replace('px', ''));
        $image.css("margin-left", -(imageWidthAsInteger - containerWidth) / 2);
    } else {
        if ($image.css('width') !== "100%") {
            $image.css("width", "100%");
            $image.css("height", "auto");
            $image.css("margin-left", 'initial');
        }
    }
}



