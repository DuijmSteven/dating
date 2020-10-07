@extends('frontend.layouts.default.layout')

@section('content')
    <div class="User-profile">
        <div class="row">
            <div class="col-xs-12">
                <a href="{{ $backUrl }}" class="GeneralBackButton">
                    <i class="material-icons">
                        chevron_left
                    </i>{{ trans('user_profile.back') }}
                </a>
            </div>

            <div class="col-xs-12 col-md-7">
                @include('frontend.users.sites.' . config('app.directory_name') . '.user-profile-sidebar')
            </div>

            <div class="col-xs-12 col-md-5">
                @include('frontend.users.sites.' . config('app.directory_name') . '.main-content')
            </div>
        </div>
    </div>
@endsection