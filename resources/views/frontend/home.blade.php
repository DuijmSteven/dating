@extends('frontend.layouts.default.layout')

@section('content')

    @include('frontend.modules.user-activity', [
        'title' => 'Newsfeed',
        'body' => 'Body!'
    ])

@endsection
