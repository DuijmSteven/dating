require('clientjs');


$(window).on('load', function () {
    setTimeout(() => {
        // Create a new ClientJS object
        var client = new ClientJS();

        // Get the client's fingerprint id
        var fingerprint = client.getFingerprint();

        $('#userFingerprintInput').val(fingerprint)
    }, 100);


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


