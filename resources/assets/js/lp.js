import $ from "jquery";

require('./bootstrap');
require('bootstrap-datepicker');
require('jquery-ui/ui/widgets/autocomplete');
require('disableautofill');
require('clientjs');

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

    // $('.JS--register-button').click(function(event) {
    //     grecaptcha.execute(DP.recaptchaKey, {action: 'register'}).then(function(token) {
    //         document.getElementById('g-recaptcha-response').value = token;
    //
    //         $('#JS--registrationForm').submit();
    //     });
    // });

    // setTimeout(() => {
    //     // Create a new ClientJS object
    //     var client = new ClientJS();
    //
    //     // Get the client's fingerprint id
    //     var fingerprint = client.getFingerprint();
    //
    //     $('#userFingerprintInput').val(fingerprint)
    // }, 100);

    if ($('input[type="submit"]').length > 0 || $('button[type="submit"]').length > 0) {
        $('form').submit(function () {
            $('input[type=submit]', this).attr('disabled', 'disabled');
            $('button[type="submit"]', this).attr('disabled', 'disabled');
        });
    }

    $('.scrollToRegistration').click(() => {
        $('html, body').animate({scrollTop:0}, 500, 'swing');

        $('#JS--registrationForm #email').focus();
    });

    //$('#JS--registrationForm').disableAutoFill();

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

    $('.JS--enhancedFormGroup').each((index, element) => {
        if ($(element).find('input').val().length === 0) {
            $(element).removeClass('open');
        }

        if ($(element).find('input').val().length > 0) {
            $(element).addClass('open');
        }
    });

    $('.JS--enhancedFormGroup').click(($event) => {
        $('.JS--enhancedFormGroup').each((index, element) => {
            if ($(element).find('input').val().length === 0) {
                $(element).removeClass('open');
            }
        });

        $($event.currentTarget).addClass('open');
        $($event.currentTarget).find('input').focus();
    });

    $('.JS--enhancedFormGroup input').keyup(function (e) {
        $('.JS--enhancedFormGroup').each((index, element) => {
            if ($(element).find('input').val().length === 0) {
                $(element).removeClass('open');
            }

            if ($(element).find('input').val().length > 0) {
                $(element).addClass('open');
            }
        });
    });

    $(document).mouseup(function(e)
    {
        var container = $('.JS--enhancedFormGroup');

        // if the target of the click isn't the container nor a descendant of the container
        if (!container.is(e.target) && container.has(e.target).length === 0)
        {
            $('.JS--enhancedFormGroup').each((index, element) => {
                if ($(element).find('input').val().length === 0) {
                    $(element).removeClass('open');
                }

                if ($(element).find('input').val().length > 0) {
                    $(element).addClass('open');
                }
            });
        }
    });

    function calculateExcessOfForm() {
        let bottomOfForm = $('.JS--form-wrapper').offset().top + $('.JS--form-wrapper').height();
        return  bottomOfForm - $('.JS--header').height();
    }

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
            let excessOfForm = calculateExcessOfForm() + 60;

            if (!(excessOfForm > 60)) {
                excessOfForm = 60;
            }

            $('.JS--currentMembers').css('margin-top', excessOfForm);
        }, 200);
    }
    //};

    // var formSelected = Cookies.get("lpFormSelection");
    //
    // if (formSelected === 'login') {
    //     $('#JS--registrationForm').addClass('hidden');
    //     $('#JS--loginForm').removeClass('hidden');
    //     $('.JS--currentMembers').removeClass('withRegistrationForm');
    //
    //     if ($(window).width() <= 767) {
    //         setTimeout(() => {
    //             let excessOfForm = calculateExcessOfForm() + 60;
    //
    //             if (!(excessOfForm > 60)) {
    //                 excessOfForm = 60;
    //             }
    //
    //             $('.JS--currentMembers').css('margin-top', excessOfForm);
    //         }, 200);
    //     }
    // }
    //
    // $('#JS--registerButton').click(function(){
    //     $('#JS--loginForm').addClass('hidden');
    //     $('#JS--registrationForm').removeClass('hidden');
    //     $('.JS--currentMembers').addClass('withRegistrationForm');
    //     if ($(window).width() <= 767) {
    //         setTimeout(() => {
    //             let excessOfForm = calculateExcessOfForm() + 60;
    //
    //             if (!(excessOfForm > 60)) {
    //                 excessOfForm = 60;
    //             }
    //
    //             $('.JS--currentMembers').css('margin-top', excessOfForm);
    //         }, 200);
    //     }
    //
    //     Cookies.set("lpFormSelection", 'register');
    // });
    //
    // $('#JS--loginButton').click(function(){
    //     $('#JS--registrationForm').addClass('hidden');
    //     $('#JS--loginForm').removeClass('hidden');
    //     $('.JS--currentMembers').removeClass('withRegistrationForm');
    //     if ($(window).width() <= 767) {
    //         setTimeout(() => {
    //             let excessOfForm = calculateExcessOfForm() + 60;
    //
    //             if (!(excessOfForm > 60)) {
    //                 excessOfForm = 60;
    //             }
    //
    //             $('.JS--currentMembers').css('margin-top', excessOfForm);
    //         }, 200);
    //     }
    //
    //     Cookies.set("lpFormSelection", 'login');
    // });

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
    $.getJSON(DP.baseUrl + '/api/cities/nl/')
        .done(function (response) {
            cityList = response.cities;

            $('.JS--autoCompleteCites').autocomplete({
                source: response.cities,
                minLength: 2
            })
        }).fail(function () {
    });

    $('#JS--registrationForm').click(() => {
        setTimeout(() => {
            let excessOfForm = calculateExcessOfForm() + 60;

            if (!(excessOfForm > 60)) {
                excessOfForm = 60;
            }

            $('.JS--currentMembers').css('margin-top', excessOfForm);
        }, 200);
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


