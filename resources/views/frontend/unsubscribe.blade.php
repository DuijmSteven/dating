@extends('frontend.layouts.default.layout')

@section('content')

<div class="row">
    <div class="col-md-8 col-md-offset-2 col-xs-12">
        <div class="Tile">
            <div class="Tile__body">
                @if(!isset($error) && !isset($success))
                <form method="post" action="{{ URL::signedRoute('unsubscribe.post', ['user' => $user->id]) }}">
                    {{ csrf_field() }}
                    <div class="row text-center">
                        <div class="col-xs-12">
                            <h3>{{ trans(config('app.directory_name') . '/unsubscribe.form_title') }}</h3>
                            <hr/>
                            <p>{{ trans(config('app.directory_name') . '/unsubscribe.form_text') }} </p>
                            <strong>{{ $user->email }}</strong>
                        </div>
                    </div>
                    <div class="row text-center" style="margin-top: 20px; margin-bottom: 20px;">
                        <div class="col-xs-6">
                            <button type="submit" class="btn btn-lg btn-success" style="min-width: 80%">{{ trans(config('app.directory_name') . '/chat.yes') }}</button>
                        </div>
                        <div class="col-xs-6">
                            <a href="{{ route('home') }}" class="btn btn-lg btn-warning" style="min-width: 80%">{{ trans(config('app.directory_name') . '/chat.no') }}</a>
                        </div>
                    </div>
                </form>
                @elseif(isset($error) && !isset($success))
                    <div class="row text-center">
                        <div class="col-xs-12">
                            <h3>{{ trans(config('app.directory_name') . '/unsubscribe.form_error_1') }}</h3>
                            <hr/>
                            <p>{{ trans(config('app.directory_name') . '/unsubscribe.form_error_2') }}</p>
                        </div>
                    </div>
                @else
                    <div class="row text-center">
                        <div class="col-xs-12">
                            <h3>{{ trans(config('app.directory_name') . '/unsubscribe.form_success_1') }}</h3>
                            <hr/>
                            <p>{{ trans(config('app.directory_name') . '/unsubscribe.form_success_2') }}</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection
