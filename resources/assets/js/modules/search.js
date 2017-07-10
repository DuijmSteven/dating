function searchDutchCities() {
    $.getJSON(DP.baseUrl + '/api/cities/nl')
        .done(function (response) {
            $(".JS--Search__autoCompleteCites").autocomplete({
                source: [response.cities]
            })
        }).fail(function () {
            console.log("Error: Ajax call to users/cities endpoint failed");
        });

    $('.Search .xdsoft_autocomplete_dropdown').css('max-height', '100px')
}

searchDutchCities();
