require('./bootstrap');
require('bootstrap-datepicker');
require('jquery-ui/ui/widgets/autocomplete');
require('disableautofill');

import isUndefined from "admin-lte/bower_components/moment/src/lib/utils/is-undefined";

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

window.addEventListener('load', function () {
    setTimeout(() => {
        $('.roundImageWrapper').each((index, element) => {
            fitRoundImageToContainer($(element));
        });

    }, 100);

}, false);

$(window).on('load', function () {
    require('./global_helpers');

    $('#JS--registrationForm').disableAutoFill();

    // this will disable right-click on all images
    $("img").on("contextmenu",function(e){
        return false;
    });

    // this will disable dragging of all images
    $("img").mousedown(function(e){
        e.preventDefault()
    });

    var cookiesAccepted = Cookies.get("lpCookiesAccepted");

    if (cookiesAccepted !== 'true') {
        $('.cookie-popup').removeClass('hidden');
    }

    $('.JS--acceptCookies').click(() => {
        $('.cookie-popup').addClass('hidden');

        Cookies.set("lpCookiesAccepted", 'true');
    });

    function calculateExcessOfForm() {
        let bottomOfForm = $('.JS--form-wrapper').offset().top + $('.JS--form-wrapper').height();
        return  bottomOfForm - $('.JS--header').height();
    }

    //var formSelected = Cookies.get("lpFormSelection");

    // if (formSelected === 'login') {
    //     $('.JS--form-wrapper').removeClass('hidden');
    //     $('#JS--loginForm').toggle('fast');
    //
    //     if ($(window).width() <= 767) {
    //         setTimeout(() => {
    //             $('.JS--welcome-container').css('margin-top', calculateExcessOfForm() + 60);
    //         }, 200);
    //     }
    // } else {
    //     $('.JS--form-wrapper').removeClass('hidden');
    //     $('#JS--registrationForm').toggle('fast');
    //     $('.JS--welcome-container').addClass('withRegistrationForm');
    if ($(window).width() <= 767) {
        setTimeout(() => {
            $('.JS--welcome-container').css('margin-top', calculateExcessOfForm() + 60);
        }, 200);
    }
    //};

    $('#JS--registerButton').click(function(){
        $('#JS--loginForm').toggle('fast');
        $('#JS--registrationForm').toggle('fast');
        $('.JS--welcome-container').addClass('withRegistrationForm');
        if ($(window).width() <= 767) {
            setTimeout(() => {
                $('.JS--welcome-container').css('margin-top', calculateExcessOfForm() + 60);
            }, 200);
        }

        Cookies.set("lpFormSelection", 'register');
    });

    $('#JS--loginButton').click(function(){
        $('#JS--registrationForm').toggle('fast');
        $('#JS--loginForm').toggle('fast');
        $('.JS--welcome-container').removeClass('withRegistrationForm');
        if ($(window).width() <= 767) {
            setTimeout(() => {
                $('.JS--welcome-container').css('margin-top', calculateExcessOfForm() + 60);
            }, 200);
        }

        Cookies.set("lpFormSelection", 'login');
    });

    // // Setup locale and bootstrap datepicker options
    // $.fn.datepicker.dates['nl'] = {
    //     days: ["Zondag", "Maandag", "Dinsdag", "Woensdag", "Donderdag", "Vrijdag", "Zaterdag"],
    //     daysShort: ["zo", "ma", "di", "wo", "do", "vr", "za"],
    //     daysMin: ["zo", "ma", "di", "wo", "do", "vr", "za"],
    //     months: ["Januari", "Februari", "Maart", "April", "Mei", "Juni", "Juli", "Augustus", "September", "Oktober", "November", "December"],
    //     monthsShort: ["Jan", "Feb", "Mrt", "Apr", "Mei", "Jun", "Jul", "Aug", "Sep", "Okt", "Nov", "Dec"],
    //     today: "Vandaag",
    //     monthsTitle: "Maanden",
    //     clear: "Wissen",
    //     weekStart: 1,
    //     format: "dd-mm-yyyy"
    // };
    //
    // $.fn.datepicker.defaults.language = DP.locale;
    //
    // $("#datepicker_dob").datepicker({
    //     weekStart: 1,
    //     autoclose: 1,
    //     startView: 2,
    //     minView: 2,
    //     useCurrent: false,
    //     defaultViewDate: new Date(1990, 11, 24),
    // });

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

        // const $searchRadiusInput = $('.JS--radiusSearchInput');
        //
        // if ($('.JS--autoCompleteCites').length > 0 && $('.JS--autoCompleteCites').val().length > 0) {
        //     $searchRadiusInput.removeClass('hidden');
        // }
        //
        // $('.JS--autoCompleteCites').keyup(function () {
        //     if ($(this).val().length > 0) {
        //         if ($searchRadiusInput.hasClass('hidden')) {
        //             $searchRadiusInput.removeClass('hidden');
        //         }
        //     } else {
        //         if (!$searchRadiusInput.hasClass('hidden')) {
        //             $searchRadiusInput.addClass('hidden');
        //         }
        //     }
        // });
        //
        // getCoordinatesAndFillInputs();
        //
        // $('.JS--autoCompleteCites').keyup(function () {
        //     getCoordinatesAndFillInputs();
        // });
});

function fitRoundImageToContainer(element) {
    let containerHeight = element.height();
    let $imageToFit = $(element).find('img');
    let imageToFitHeight = $imageToFit.height();

    if (containerHeight > imageToFitHeight) {
        $imageToFit.addClass('fitVertically');
    }
}


