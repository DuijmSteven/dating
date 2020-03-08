@extends('emails.layouts.default.layout')

@section('content')

    <h1 style="margin: 0 0 30px; font-size: 25px; line-height: 30px; color: #333333; font-weight: bold; text-align: center">
        New sale my bitches!
    </h1>

    <p>
        <b>Username</b>: {{ $user->username }} <br>
        <b>User ID</b>: {{ $user->id }} <br>
        <b>User creation date</b>: {{ $user->created_at }} <br>
        <b>Creditpack</b>: {{ $creditPack->name }} <br>
        <b>Prijs</b>: {{ $creditPack->price }} <br>
    </p>

@endsection