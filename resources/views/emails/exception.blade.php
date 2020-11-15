@extends('emails.layouts.default.layout')

@section('content')

    <h1 style="margin: 0 0 30px; font-size: 25px; line-height: 30px; color: #333333; font-weight: bold; text-align: center">
        Exception {{ get_class($exception) }}!
    </h1>

    <h3>Site</h3>
    <p>
        <b>ID: </b> {{ $siteId }} <br>
        <b>Name: </b> {{ $siteName }} <br>
        <b>Domain: </b> {{ $siteDomain }} <br>
    </p>

    @if(isset($user) && $user)

        <h3>User</h3>
        <p>
            <b>Username</b>: {{ $user->username }} <br>
            <b>User ID</b>: {{ $user->id }} <br>
            <b>User creation date</b>: {{ $user->created_at }} <br>
        </p>
    @endif

    <h3>Exception Message: </h3>
    <pre>
        {{ $exception->getMessage() }}
    </pre>

    <h3>Exception Trace: </h3>
    <pre>
        {{ $exception->getTraceAsString() }}
    </pre>





@endsection