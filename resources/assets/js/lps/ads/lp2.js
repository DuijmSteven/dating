require('clientjs');


$(window).on('load', function () {

    $('.JS--prevent-default__click').click(function (event) {
        event.preventDefault();
    });

    $('.JS--register-button').click(function (event) {
        grecaptcha.execute(DP.recaptchaKey, {action: 'register'}).then(function (token) {
            document.getElementById('g-recaptcha-response').value = token;

            $('#JS--registrationForm').submit();
        });
    });
});


