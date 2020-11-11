<div class="Tile User-profile__about">
    <div class="Tile__heading User-profile__about__heading">
        <span class="material-icons">
            emoji_people
        </span>
        {{ trans(config('app.directory_name') . '/user_profile.about_me') }}
    </div>
    <div class="Tile__body User-profile__about__body">
        <div class="User-profile__text">
            @if($user->meta->about_me)
                <p>{{ $user->meta->about_me }}</p>
            @else
                <p>
                    {{ trans(config('app.directory_name') . '/user_constants.not_filled') }}
                </p>
            @endif
        </div>
    </div>
</div>

<div class="Tile User-profile__info">
    <div class="Tile__heading User-profile__info__heading">
        <span class="material-icons">
            info
        </span>
        {{ trans(config('app.directory_name') . '/user_profile.information') }}
    </div>
    <div class="Tile__body User-profile__info__body">
        <div class="User-profile__text">
            <table class="table">
                <tbody>
                @if($user->meta->dob)
                    <tr>
                        <td><span class="User-profile__info__title">{{ trans(config('app.directory_name') . '/user_constants.age') }}</span></td>
                        <td><span class="User-profile__info__value">{{ $user->meta->dob->diffInYears($carbonNow) }}</span>
                        </td>
                    </tr>
                @endif
                @if($user->meta->city)
                    <tr>
                        <td><span class="User-profile__info__title">{{ trans(config('app.directory_name') . '/user_constants.city') }}</span></td>
                        <td><span class="User-profile__info__value">{{ $user->meta->city }}</span></td>
                    </tr>
                @endif
                <tr>
                    <td><span
                            class="User-profile__info__title">{{ trans(config('app.directory_name') . '/user_constants.labels.relationship_status') }}</span>
                    </td>
                    <td><span
                            class="User-profile__info__value"
                        >
                            @if($user->meta->relationship_status)
                                {{ trans(config('app.directory_name') . '/user_constants.relationship_status.' . $user->meta->relationship_status) }}
                            @else
                                {{ trans(config('app.directory_name') . '/user_constants.not_filled') }}
                            @endif
                        </span>
                    </td>
                </tr>
                <tr>
                    <td><span class="User-profile__info__title">{{ trans(config('app.directory_name') . '/user_constants.labels.height') }}</span></td>
                    <td><span
                            class="User-profile__info__value"
                        >
                            @if($user->meta->height)
                                {{ trans(config('app.directory_name') . '/user_constants.height.' . $user->meta->height) }}
                            @else
                                {{ trans(config('app.directory_name') . '/user_constants.not_filled') }}
                            @endif
                        </span>
                    </td>
                </tr>
                <tr>
                    <td><span class="User-profile__info__title">{{ trans(config('app.directory_name') . '/user_constants.labels.body_type') }}</span>
                    </td>
                    <td><span
                            class="User-profile__info__value"
                        >
                            @if($user->meta->body_type)
                                {{ trans(config('app.directory_name') . '/user_constants.body_type.' . $user->meta->body_type) }}
                            @else
                                {{ trans(config('app.directory_name') . '/user_constants.not_filled') }}
                            @endif
                        </span>
                    </td>
                </tr>
                <tr>
                    <td><span class="User-profile__info__title">{{ trans(config('app.directory_name') . '/user_constants.labels.eye_color') }}</span>
                    </td>
                    <td><span
                            class="User-profile__info__value"
                        >
                            @if($user->meta->eye_color)
                                {{ trans(config('app.directory_name') . '/user_constants.eye_color.' . $user->meta->eye_color) }}
                            @else
                                {{ trans(config('app.directory_name') . '/user_constants.not_filled') }}
                            @endif
                        </span>
                    </td>
                </tr>
                <tr>
                    <td><span class="User-profile__info__title">{{ trans(config('app.directory_name') . '/user_constants.labels.hair_color') }}</span>
                    </td>
                    <td><span
                            class="User-profile__info__value"
                        >
                            @if($user->meta->hair_color)
                                {{ trans(config('app.directory_name') . '/user_constants.hair_color.' . $user->meta->hair_color) }}
                            @else
                                {{ trans(config('app.directory_name') . '/user_constants.not_filled') }}
                            @endif
                        </span>
                    </td>
                </tr>
                <tr>
                    <td><span
                            class="User-profile__info__title">{{ trans(config('app.directory_name') . '/user_constants.labels.smoking_habits') }}</span>
                    </td>
                    <td><span
                            class="User-profile__info__value"
                        >
                            @if($user->meta->smoking_habits)
                                {{ trans(config('app.directory_name') . '/user_constants.smoking_habits.' . $user->meta->smoking_habits) }}
                            @else
                                {{ trans(config('app.directory_name') . '/user_constants.not_filled') }}
                            @endif
                        </span>
                    </td>
                </tr>
                <tr>
                    <td><span
                            class="User-profile__info__title">{{ trans(config('app.directory_name') . '/user_constants.labels.drinking_habits') }}</span>
                    </td>
                    <td><span
                            class="User-profile__info__value"
                        >
                            @if($user->meta->drinking_habits)
                                {{ trans(config('app.directory_name') . '/user_constants.drinking_habits.' . $user->meta->drinking_habits) }}
                            @else
                                {{ trans(config('app.directory_name') . '/user_constants.not_filled') }}
                            @endif
                        </span>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>