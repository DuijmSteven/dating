function searchDutchCities() {
    if ($('.JS--Search__autoCompleteDutchCites').length > 0) {
        $.getJSON(DP.baseUrl + '/cities/nl')
            .done(function (response) {
                $(".JS--Search__autoCompleteDutchCites").autocomplete({
                    source: [response.cities]
                })
            }).fail(function () {
            console.log("Error: Ajax call to users/cities endpoint failed");
        });
    }
}

searchDutchCities();
