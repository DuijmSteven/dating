<?php

return [

    /*
     * The api key used when sending requests to TheCheker email verification service.
     */
    'api_key' => env('THE_CHECKER_API_KEY', ''),

    'api_base_url' => 'https://api.thechecker.co/v2/',

    'api_single_verification_endpoint' => 'verify/'
];
