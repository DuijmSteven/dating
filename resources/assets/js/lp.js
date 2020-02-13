require('./bootstrap');
require('bootstrap-datepicker');
require("jquery-ui/ui/widgets/autocomplete");

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
    // var allimages = document.getElementsByTagName('img');
    // for (var i = 0; i < allimages.length; i++) {
    //     if (allimages[i].getAttribute('data-src')) {
    //         allimages[i].setAttribute('src', allimages[i].getAttribute('data-src'));
    //     }
    // }

    setTimeout(() => {
        $('.roundImageWrapper').each((index, element) => {
            fitRoundImageToContainer($(element));
        });

    }, 100);

}, false);

$(window).on('load', function () {
    require('./global_helpers');

    // this will disable right-click on all images
    $("img").on("contextmenu",function(e){
        return false;
    });

    // this will disable dragging of all images
    $("img").mousedown(function(e){
        e.preventDefault()
    });

    var formSelected = Cookies.get("lpFormSelection");
    var cookiesAccepted = Cookies.get("lpCookiesAccepted");

    if (cookiesAccepted !== 'true') {
        $('.cookie-popup').removeClass('hidden');
    }

    $('.JS--acceptCookies').click(() => {
        $('.cookie-popup').addClass('hidden');

        Cookies.set("lpCookiesAccepted", 'true');
    });

    if (formSelected === 'register') {
        $('.form-container').removeClass('hidden');
        $('#JS--registrationForm').toggle('fast');
    } else {
        $('.form-container').removeClass('hidden');
        $('#JS--loginForm').toggle('fast');
    }

    $('#JS--registerButton').click(function(){
        $('#JS--loginForm').toggle('fast');
        $('#JS--registrationForm').toggle('fast');

        Cookies.set("lpFormSelection", 'register');
    });

    $('#JS--loginButton').click(function(){
        $('#JS--registrationForm').toggle('fast');
        $('#JS--loginForm').toggle('fast');

        Cookies.set("lpFormSelection", 'login');
    });

    var date = new Date();
    date.setFullYear( date.getFullYear() - 18 );
    let formatted = ('0' + (date.getMonth() + 1)).slice(-2) + '-' + ('0' + date.getDate()).slice(-2) + '-' + date.getFullYear();

    var firstOpen = true;

    $("#datepicker_dob").datepicker({
        format: 'dd-mm-yyyy',
        weekStart: 1,
        autoclose: 1,
        startView: 2,
        minView: 2,
        useCurrent: false,
        defaultViewDate: new Date(1990, 11, 24)
        // startDate : new Date('01-01-1920'),
        // endDate : new Date(formatted),
    });

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

        if ($('.JS--autoCompleteCites').length > 0 && $('.JS--autoCompleteCites').val().length > 0) {
            $searchRadiusInput.removeClass('hidden');
        }

        $('.JS--autoCompleteCites').keyup(function () {
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
});

function fitRoundImageToContainer(element) {
    let containerHeight = element.height();
    let $imageToFit = $(element).find('img');
    let imageToFitHeight = $imageToFit.height();

    if (containerHeight > imageToFitHeight) {
        $imageToFit.addClass('fitVertically');
    }
}


