@extends('frontend.layouts.default.layout')

@section('content')

    <public-chat></public-chat>

    <div class="Tile Activity__sectionTitle">
        <div class="Tile__heading" style="padding: 5px">
            <span class="material-icons">
                dynamic_feed
            </span>
            {{ trans(config('app.directory_name') . '/home.new_members') }}
        </div>
    </div>

    @foreach($users as $user)
        @include('frontend.components.sites.' . config('app.directory_name') . '.user-summary',
            ['user' => $user, 'showAboutMe' => true, 'firstTile' => $loop->index === 0]
        )
    @endforeach

@endsection
