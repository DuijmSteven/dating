<div class="row">
@php
    $count = 0;
@endphp
@forelse($users as $user)
    <div class="col-xs-6 {{ $count % 2 === 0 ? 'firstColumn' : 'secondColumn' }}">
        <div class="ModuleProfiles__username">
            {{ $user->username }}{{ $user->meta->dob ? ' - ' : '' }}
            {{ $user->meta->dob ? $user->meta->dob->diffInYears(\Carbon\Carbon::now('Europe/Amsterdam')) : '' }}
        </div>

        <a href="{!! route('users.show', ['username' => $user->getUsername()]) !!}" class="ModuleProfiles__item">
            <img class="ModuleProfiles__profile-image" src="{!! \StorageHelper::profileImageUrl($user, true) !!}" alt="">

            @if(in_array($user->getId(), $onlineUserIds))
                <div class="onlineCircle"></div>
            @endif
        </a>

        <div class="ModuleProfiles__sendMessage"
             v-on:click="addChat({!! $authenticatedUser->getId() !!}, {!! $user->getId() !!}, '1', true)"
        >
            <i class="material-icons material-icon ModuleProfiles__sendMessage__icon">forward_to_inbox</i>
            <span class="ModuleProfiles__sendMessage__text">Bericht</span>
        </div >
    </div>

{{--    <div class="ModuleProfiles__bodyWrapper">--}}
{{--        <a href="{!! route('users.show', ['username' => $user->getUsername()]) !!}" class="ModuleProfiles__item">--}}
{{--            <span class="ModuleProfiles__profile-image">--}}
{{--                <img src="{!! \StorageHelper::profileImageUrl($user, true) !!}" alt="">--}}

{{--                @if(in_array($user->getId(), $onlineUserIds))--}}
{{--                    <div class="onlineCircle"></div>--}}
{{--                @endif--}}
{{--            </span>--}}
{{--            <span class="ModuleProfiles__username">--}}
{{--                {{ $user->username }}{{ $user->meta->dob ? ', ' : '' }}--}}
{{--                {{ $user->meta->dob ? $user->meta->dob->diffInYears(\Carbon\Carbon::now('Europe/Amsterdam')) : '' }}--}}
{{--            </span>--}}
{{--        </a>--}}

{{--        <div class="ModuleProfiles__sendMessage"--}}
{{--             v-on:click="addChat({!! $authenticatedUser->getId() !!}, {!! $user->getId() !!}, '1', true)"--}}
{{--        >--}}
{{--            <i class="material-icons material-icon ModuleProfiles__sendMessage__icon">textsms</i>--}}
{{--        </div>--}}
{{--    </div>--}}
    @php
        $count++;
    @endphp
@empty
    <div class="col-xs-12">
        <p>
            {{ trans('viewed_by_users.no_views') }}
        </p>
    </div>
@endforelse
</div>
