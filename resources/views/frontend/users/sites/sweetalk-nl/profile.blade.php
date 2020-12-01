@extends('frontend.layouts.default.layout')

@section('content')

    <div class="User-profile">
        <div class="row">
{{--            <div class="col-xs-12">--}}
{{--                <a href="{{ $backUrl }}" class="GeneralBackButton">--}}
{{--                    <i class="material-icons">--}}
{{--                        chevron_left--}}
{{--                    </i>{{ trans(config('app.directory_name') . '/user_profile.back') }}--}}
{{--                </a>--}}
{{--            </div>--}}

            <div class="col-xs-12 col-md-7">
                @include('frontend.users.sites.' . config('app.directory_name') . '.main-content')

            </div>

            <div class="col-xs-12 col-md-5">
                @include('frontend.users.sites.' . config('app.directory_name') . '.user-profile-sidebar')

                <div
                    class="User-profile__sendMessageButton"
                    v-on:click="addChat({!! $authenticatedUser->getId() !!}, {!! $user->getId() !!}, '1', true)"
                >
                    <i class="material-icons material-icon User-profile__sendMessageButton__icon">chat_bubble</i>
                    <span class="User-profile__sendMessageButton__text">
                            {{ trans(config('app.directory_name') . '/user_profile.send_message', ['username' => $user->getUsername()]) }}
                        </span>
                </div>
            </div>
        </div>
    </div>
@endsection