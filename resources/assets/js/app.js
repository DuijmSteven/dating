/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */
import isUndefined from "admin-lte/bower_components/moment/src/lib/utils/is-undefined";
window.Vue = require('vue/dist/vue.js');

import VuejsDialog from 'vuejs-dialog';

import VueMq from 'vue-mq';

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
    }
    else {
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

    if($('.SearchBar .city').hasClass('has-error')) {
        $('.SearchBar').toggleClass('hidden');
    }

    if ($('.modalImage').length > 0) {
        $(".modalImage").on("click", function(event) {
            event.preventDefault();

            if ($(this).find('img').data('src') && $(this).find('img').data('src').length > 0) {
                $('#imagePreview').attr('src', $(this).find('img').data('src'));
            } else {
                $('#imagePreview').attr('src', $(this).find('img').attr('src')); // here asign the image to the modal when the user click the enlarge link
            }

            $('#imageModal').modal('show'); // imagemodal is the id attribute assigned to the bootstrap modal, then i use the show function
        });
    }

    // if ($('.landingPage').length > 0) {
    //     var data = Cookies.get("lpFormSelection");
    //
    //     if (data === 'register') {
    //         $('#JS--registrationForm').toggle('fast');
    //     } else {
    //         $('#JS--loginForm').toggle('fast');
    //     }
    //
    //     $('#JS--registerButton').click(function(){
    //         $('#JS--loginForm').toggle('fast');
    //         $('#JS--registrationForm').toggle('fast');
    //
    //         Cookies.set("lpFormSelection", 'register');
    //     });
    //
    //     $('#JS--loginButton').click(function(){
    //         $('#JS--registrationForm').toggle('fast');
    //         $('#JS--loginForm').toggle('fast');
    //
    //         Cookies.set("lpFormSelection", 'login');
    //     });
    //
    // }

    if ($('.JS--creditpack').length > 0) {
        $('.JS--creditpack .btn').click(function () {
            $('.table-rose').removeClass('table-rose');
            $('.block-raised').removeClass('block-raised');
            $('.btn-white').removeClass('btn-white').addClass('btn-rose');
            $(this).closest('.JS--creditpack').addClass('block-raised');
            $(this).closest('.table').addClass('table-rose');
            $(this).addClass('btn-white');

            let creditPackId = $('.block-raised').data('creditpack-id');

            //change cart values based on selected credits package
            $('span.cart-value').html($('.block-raised .block-caption span').html());
            $('input[name="amount"]').val($('.block-raised .block-caption span').html());
            $('span.cart-credits').html($('.block-raised b.package-credits').html());
            $('input[name="creditpack_id"]').val(creditPackId);
            $('span.cart-package').html($('.block-raised .category').html());
        });
    }

    if($('.JS--banksContainer').length > 0) {
        $('input:radio[name="paymentMethod"]').change( function(){
            if ($(this).is(':checked') && $(this).val() == 'ideal') {
                $('.JS--banksContainer').show();
            } else
                $('.JS--banksContainer').hide();
        });
    }

    if($('.JS--banksContainer').length > 0) {
        $('input:radio[name="paymentMethod"]').change( function(){
            if ($(this).is(':checked') && $(this).val() == 'ideal') {
                $('.JS--banksContainer').show();
            } else
                $('.JS--banksContainer').hide();
        });
    }

    if($('.JS--UserSummary').length > 0) {
        $('.JS--UserSummary__user-image').each((index, element) => {
            fitImageToContainer($(element));
        });

        if ($('.JS--UserSummary__otherImages').length > 0) {
            $('.JS--UserSummary__nonProfileImageWrapper').each((index, element) => {
                fitImageToContainer($(element));
            });
        }

        // $(window).resize(function() {
        //     $('.JS--UserSummary__user-image').each((index, element) => {
        //         fitImageToContainer($(element));
        //     });
        //
        //     if ($('.JS--UserSummary__otherImages').length > 0) {
        //         $('.JS--UserSummary__nonProfileImageWrapper').each((index, element) => {
        //
        //             fitImageToContainer($(element));
        //         });
        //     }
        // });
    }

    if ($('.JS--searchToggle').length > 0) {
        var searchBarState = Cookies.get('searchBarState');

        // if (searchBarState === 'open') {
        //     $('.JS--SearchBar').removeClass('hidden');
        //     $('.JS--searchToggleButton').addClass('pressed');
        // }

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
        $('.dropdown-submenu a.JS--showLanguagesSubmenu').on("click", function(e){
            $(this).next('ul').toggle();
            e.stopPropagation();
            e.preventDefault();
        });
    }

});

function fitImageToContainer(element) {
    var containerHeight = element.height();
    var containerWidth = element.width();

    const $profileImage = $(element).find('img');

    var profileImageHeight = $profileImage.height();

    if (profileImageHeight < containerHeight + 5) {
        $profileImage.css("width", "auto");
        $profileImage.css("height", containerHeight);

        const profileImageWidth = $profileImage.css('width');

        const imageWidth = parseInt(profileImageWidth.replace('px', ''));
        $profileImage.css("margin-left", - (imageWidth - containerWidth)/2);
    }
}


