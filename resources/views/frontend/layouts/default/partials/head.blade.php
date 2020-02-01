<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ isset ($title) ? $title : 'Title'  }}</title>
    <meta name="description" content="Dating app">
    <meta name="author" content="Dating">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @if(App::environment('production'))
        <!-- Google Tag Manager -->
        <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
                    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
                j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
                'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
            })(window,document,'script','dataLayer','GTM-MJG2S4N');</script>
        <!-- End Google Tag Manager -->
    @endif
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons"
          rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/js-cookie@beta/dist/js.cookie.min.js"></script>

    @include('frontend.layouts.default.partials.favicons')

    <!--[if lt IE 9]>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.js"></script>
    <![endif]-->

    <script src="https://maps.googleapis.com/maps/api/js?key={{ config('googlmapper.key') }}&libraries=places" async defer></script>
    @toastr_css

</head>
