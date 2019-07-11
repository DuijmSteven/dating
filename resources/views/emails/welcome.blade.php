@extends('emails.layouts.default.layout')

@section('content')
    <td style="text-align: center; padding: 20px; font-family: sans-serif; font-size: 15px; line-height: 20px; color: #555555;">

        <h1 style="margin: 0 0 30px; font-size: 25px; line-height: 30px; color: #333333; font-weight: bold; text-align: center">
            Welkom!
        </h1>

        <p style="font-family: sans-serif; font-weight: normal; margin: 0; Margin-bottom: 15px;">Hi {{ $user->username }},</p>

        <p>
            Welkom bij Altijdsex.nl!
        </p>

        <p style="font-family: sans-serif; font-weight: normal; margin: 0; Margin-bottom: 15px;">Best regards, <br>
            Team Altijdsex.nl</p>
    </td>

@endsection