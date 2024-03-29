@extends('frontend.layouts.default.layout')

<!-- Main Content -->
@section('content')
<div class="container" style="padding-top: 100px; padding-bottom: 100px">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">{{ trans(config('app.directory_name') . '/reset_password.reset_password') }}</div>
                <div class="panel-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form class="form-horizontal" role="form" method="POST" action="{{ route('password.email') }}">
                        @csrf

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email" class="col-md-4 control-label">{{ trans(config('app.directory_name') . '/reset_password.registered_email') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required>

                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group text-right">
                            <div class="col-md-6 col-md-offset-4">
                                @include('frontend.components.button', [
                                 'buttonContext' => 'form',
                                 'buttonType' => 'submit',
                                 'buttonState' => 'primary',
                                 'buttonText' => trans(config('app.directory_name') . '/reset_password.send_reset_link')
                             ])
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
