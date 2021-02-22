@extends('frontend.layouts.default.layout')

@section('content')
    <div class="row">
        <div class="col-xs-12">
            <div class="FeedbackContainer">
                @if ($status == 'success')
                    <h1 id="analytics--successfulPayment" style="margin-top:0; font-size: 60px; font-weight: 700;">{{ trans(config('app.directory_name') . '/credits_bought_feedback.thanks') }}</h1>
                    <i class="material-icons" style="font-size: 10rem; color: #2ab27b">done</i>
                    <h4 style="margin-bottom: 30px">{{ trans(config('app.directory_name') . '/credits_bought_feedback.success_message') }}</h4>

                    <a
                        href="{{ route('home') }}"
                        class="Button Button--primary centered"
                    >
                        <span class="Button__content">
                            {{ trans(config('app.directory_name') . '/credits_bought_feedback.home') }}
                        </span>
                    </a>
                @elseif ($status == 'completed')
                    <h1 style="margin-top:0; font-size: 60px; font-weight: 700;">{{ trans(config('app.directory_name') . '/credits_bought_feedback.thanks') }}</h1>
                    <i class="material-icons" style="font-size: 10rem; color: #2ab27b">done</i>
                    <h4 style="margin-bottom: 30px">{{ trans(config('app.directory_name') . '/credits_bought_feedback.success_message') }}</h4>

                    <a
                        href="{{ route('home') }}"
                        class="Button Button--primary centered"
                    >
                        <span class="Button__content">
                            {{ trans(config('app.directory_name') . '/credits_bought_feedback.home') }}
                        </span>
                    </a>
                @else
                    <h1 style="font-size: 60px; font-weight: 700;">
                        {{ trans(config('app.directory_name') . '/credits_bought_feedback.problem_with_payment') }}
                    </h1>
                    <h3>{{ $info }}</h3>

                    <a
                        href="{{ route('credits.show') }}"
                        class="Button Button--primary centered"
                    >
                        <span class="Button__content">
                            {{ trans(config('app.directory_name') . '/credits_bought_feedback.retry') }}
                        </span>
                    </a>
                @endif

            </div>
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
