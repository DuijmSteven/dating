import $ from "jquery";

$(window).on('load', function () {

    $('.JS--prevent-default__click').click(function (event) {
        event.preventDefault();
    });

    $('.JS--register-button').click(function (event) {
        event.preventDefault();
        $('.JS--register-button').attr('disabled', 'disabled');

        grecaptcha.execute(DP.recaptchaKey, {action: 'register'}).then(function (token) {
            document.getElementById('g-recaptcha-response').value = token;

            $('#JS--registrationForm').submit();
        });
    });

    if ($('.has-error').length > 0) {
        $('#JS--registerButton').click();
    }

    $('.scrollToRegistration').click(() => {
        $('html, body').animate({scrollTop:0}, 500, 'swing');
    });
});


