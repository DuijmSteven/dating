@extends('frontend.layouts.default.layout')

@section('content')

    @include('frontend.components.user-activity', [
        'title' => 'Newsfeed',
        'body' => 'Body!'
    ])

@endsection
