@extends('frontend.layouts.default.layout')


@section('content')
    <div class="User-profile">
        <div class="col-md-7">
            @include('frontend.users.profile.user-profile-sidebar')
        </div>

        <div class="col-md-5">
            @include('frontend.users.profile.main-content')
        </div>
    </div>
@endsection