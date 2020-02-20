@extends('frontend.layouts.default.layout')

@section('content')
    <div class="User-profile">
        <div class="row">
            <div class="col-xs-12">
                <a href="{{ url()->previous() }}" class="User-profile__backButton">
                    <i class="material-icons">
                        chevron_left
                    </i>{{ trans('user_profile.back') }}
                </a>
            </div>

            <div class="col-xs-12 col-md-7">
                @include('frontend.users.profile.user-profile-sidebar')
            </div>

            <div class="col-xs-12 col-md-5">
                @include('frontend.users.profile.main-content')
            </div>
        </div>
    </div>
@endsection