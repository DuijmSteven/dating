require('clientjs');


$(window).on('load', function () {
    setTimeout(() => {
        // Create a new ClientJS object
        var client = new ClientJS();

        // Get the client's fingerprint id
        var fingerprint = client.getFingerprint();

        $('#userFingerprintInput').val(fingerprint)
    }, 100);
});


