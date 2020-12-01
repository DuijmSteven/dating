<div class="Tile LatestViewed ModuleProfiles JS--Tile Tile--withToggle">
    <div class="Tile__heading LatestViewedBy__heading JS--Tile__toggle">
        <span class="material-icons">
            people
        </span>

        {{ trans(config('app.directory_name') . '/viewed_by_users.viewed_by_users') }}

{{--        @include('frontend.components.tile-toggle')--}}

        <div class="Tile__toggle JS--Tile__toggleExpand hidden">
            <span class="material-icons">
                expand_more
            </span>
        </div>

        <div class="Tile__toggle JS--Tile__toggleCollapse">
            <span class="material-icons">
                expand_less
            </span>
        </div>

    </div>
    <div class="Tile__body LatestViewedBy__body ModuleProfiles__body JS--Tile__body">
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
                        <img class="ModuleProfiles__profile-image" src="{!! $user->profileImageUrlThumb !!}" alt="">

                        @if(in_array($user->getId(), $onlineUserIds))
                            <div class="onlineCircle blinking"></div>
                        @endif
                    </a>

                    <div class="ModuleProfiles__buttons">
                        <div class="ModuleProfiles__button sendMessage"
                             v-on:click="addChat({!! $authenticatedUser->getId() !!}, {!! $user->getId() !!}, '1', true)"
                        >
                            <i class="material-icons material-icon ModuleProfiles__button__icon">chat_bubble</i>
                        </div >

                        <a
                            class="ModuleProfiles__button seeProfile"
                            href="{{ route('users.show', ['username' => $user->getUsername()])  }}"
                        >
                            <span class="material-icons ModuleProfiles__button__icon">
                                account_circle
                            </span>
                        </a>
                    </div>

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
                        {{ trans(config('app.directory_name') . '/viewed_by_users.no_views') }}
                    </p>
                </div>
            @endforelse
        </div>
    </div>
</div>