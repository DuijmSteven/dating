@extends('frontend.layouts.default.layout')


@section('content')

    {{ $user->username }}, {{ $user->meta->dob->diffInYears($carbonNow)  }}

    <br>
    <br>

    {{ $user->roles[0]->name }}

@endsection