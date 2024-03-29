@extends('frontend.layouts.default.layout')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Login</div>
                    <div class="panel-body">
                        <form
                            class="form-horizontal"
                            role="form"
                            method="POST"
                            action="{{ route('login.post') }}"
                        >
                            {!! csrf_field() !!}

                            <div class="form-group{{ $errors->has('identity') ? ' has-error' : '' }}">
                                <label class="col-md-4 control-label">E-Mail Address or Username</label>

                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="identity" value="{{ old('identity') }}">

                                    @if ($errors->has('identity') || $errors->has('username') || $errors->has('email'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('identity') }}</strong>
                                        </span>
                                        <span class="help-block">
                                            <strong>{{ $errors->first('username') }}</strong>
                                        </span>
                                        <span class="help-block">
                                            <strong>{{ $errors->first('email') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                                <label class="col-md-4 control-label">Password</label>

                                <div class="col-md-6">
                                    <input type="password" class="form-control" name="password">

                                    @if ($errors->has('password'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-4">
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="remember"> Remember Me
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-4">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fa fa-btn fa-sign-in"></i>Login
                                    </button>

                                    <p style="margin-top: 20px; font-size: 1.7rem">Ben je je wachtwoord vergeten? Vraag je coach om een nieuw wachtwoord.</p>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection