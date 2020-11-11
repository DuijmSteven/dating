@extends('frontend.layouts.default.layout')

@section('content')
    <div class="row">
        <div class="col-md-12 text-center" style="margin: 30% 20px 30% 20px">
            @if ($status == 'success')
                <h1 id="analytics--successfulPayment" style="font-size: 60px; font-weight: 700;">{{ @trans(config('app.directory_name') . '/credits_bought_feedback.thanks') }}</h1>
                <i class="material-icons" style="font-size: 10rem; color: #2ab27b">done</i>
                <h4>{{ @trans(config('app.directory_name') . '/credits_bought_feedback.success_message') }}</h4>
                <a href="{{ route('home') }}" class="btn btn-success btn-lg btn-block" style="margin-top: 30px">
                    {{ @trans(config('app.directory_name') . '/credits_bought_feedback.home') }}
                </a>
            @elseif ($status == 'completed')
                <h1 style="font-size: 60px; font-weight: 700;">{{ @trans(config('app.directory_name') . '/credits_bought_feedback.thanks') }}</h1>
                <i class="material-icons" style="font-size: 10rem; color: #2ab27b">done</i>
                <h4>{{ @trans(config('app.directory_name') . '/credits_bought_feedback.success_message') }}</h4>
                <a href="{{ route('home') }}" class="btn btn-success btn-lg btn-block" style="margin-top: 30px">
                    {{ @trans(config('app.directory_name') . '/credits_bought_feedback.home') }}
                </a>
            @else
                <h1 style="font-size: 60px; font-weight: 700;">Something went wrong!</h1>
                <h3>{{ $info }}</h3>
                <a href="{{ route('credits.show') }}" class="btn btn-warning btn-lg btn-block" style="margin-top: 30px">
                    {{ @trans(config('app.directory_name') . '/credits_bought_feedback.retry') }}
                </a>
            @endif
        </div>
    </div>
    @if ($status == 'success')
        <script>
            window.dataLayer = window.dataLayer || [];
            dataLayer.push({
                'transactionId': '<?php echo $transactionId ?>',
                'transactionTotal': <?php echo $transactionTotal ?>,
                'transactionProducts': [{
                    'sku': '<?php echo $sku ?>',
                    'name': '<?php echo $name ?>',
                    'price': <?php echo $transactionTotal ?>,
                    'quantity': 1
                }]
            });

            //Redirect user to home page after 8 sec
            setTimeout(function(){
                window.location.href = '<?php route('home') ?>';
            }, 8000);
        </script>
    @endif
@endsection
