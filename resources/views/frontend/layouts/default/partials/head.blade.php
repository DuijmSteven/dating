<head>
    <meta charset="utf-8">
    <title>{{ isset ($title) ? $title : 'Title'  }}</title>
    <meta name="description" content="Dating app">
    <meta name="author" content="Dating">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons"
          rel="stylesheet">

    @include('frontend.layouts.default.partials.favicons')

    <!--[if lt IE 9]>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.js"></script>
    <![endif]-->
</head>
