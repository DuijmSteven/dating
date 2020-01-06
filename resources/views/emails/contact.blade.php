@extends('emails.layouts.default.layout')

@section('content')

    <h3>Name: {{ $requestData['name'] }}</h3>

    <p>{{ $requestData['body'] }}</p>

@endsection