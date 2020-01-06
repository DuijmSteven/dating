@extends('frontend.layouts.default.layout')

@section('content')
    <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">

    <div class="row">
        <div class="col-md-12 text-center" style="margin: 30% 20px 30% 20px">
            @if($status == true)
                <h1 style="font-size: 60px; font-weight: 700;">{{ @trans('credits_bought_feedback.thanks') }}</h1>
                <i class="fa fa-check" style="font-size: 10rem; color: #2ab27b"></i>
                <h4>{{ @trans('credits_bought_feedback.success_messsage') }}</h4>
                <a href="{{ route('home') }}" class="btn btn-success btn-lg btn-block" style="margin-top: 30px">
                    {{ @trans('credits_bought_feedback.home') }}
                </a>
            @else
                <h1 style="font-size: 60px; font-weight: 700;">{{ @trans('credits_bought_feedback.problem_with_payment') }}</h1>
                <h4>{{ @trans('credits_bought_feedback.unsuccessful_payment_message') }}</h4>
                <a href="{{ route('credits.show') }}" class="btn btn-warning btn-lg btn-block" style="margin-top: 30px">
                    {{ @trans('credits_bought_feedback.retry') }}
                </a>
            @endif
        </div>
    </div>
@endsection
