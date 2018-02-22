@extends('frontend.layouts.default.layout')


@section('content')
    <div id="app">
        <div class="PrivateChatItem">
            <div class="PrivateChatItem__head">
                <div class="PrivateChatItem__head__wrapper">
                    <div class="PrivateChatItem__user">
                        <img class="PrivateChatItem__profile-image" src="http://placehold.it/40x40">
                        <div class="PrivateChatItem__username">Jumanjo</div>
                    </div>

                    <div class="PrivateChatItem__close"><i class="fa fa-close"></i></div>
                </div>
            </div>

            <div class="PrivateChatItem__body">
                <div class="PrivateChatItem__body__wrapper">
                    <div class="PrivateChatItem__body__content">
                        <div class="PrivateChatItem__message PrivateChatItem__message--user-a">This is from A	</div>
                        <div class="PrivateChatItem__message PrivateChatItem__message--user-b">This is from B, and its amazingly kool nah... i know it even i liked it :)</div>
                        <div class="PrivateChatItem__message PrivateChatItem__message--user-a">This is from A	</div>
                        <div class="PrivateChatItem__message PrivateChatItem__message--user-b">This is from B, and its amazingly kool nah... i know it even i liked it :)</div>
                        <div class="PrivateChatItem__message PrivateChatItem__message--user-a last">Wow, Thats great to hear from you man </div>
                    </div>
                    <textarea class="PrivateChatItem__textarea" rows="4"></textarea>
                </div>
            </div>

        </div>
    </div>
@endsection