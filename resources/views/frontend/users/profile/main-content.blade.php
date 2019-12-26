<div class="Tile User-profile__info">
    <div class="Tile__heading User-profile__info__heading">
        <i class="fa fa-info"></i> Information
    </div>
    <div class="Tile__body User-profile__info__body">
        <div class="row User-profile__text">
            <div class="col-xs-12">
                <table class="table table-striped">
                    <tbody>
                        <tr>
                            <td><span class="User-profile__info__title">{{ trans('user_constants.age') }}</span></td>
                            <td><span class="User-profile__info__value">{{ $user->meta->dob->diffInYears($carbonNow) }}</span></td>
                        </tr>
                        <tr>
                            <td><span class="User-profile__info__title">{{ trans('user_constants.city') }}</span></td>
                            <td><span class="User-profile__info__value">{{ $user->meta->city }}</span></td>
                        </tr>
                        <tr>
                            <td><span class="User-profile__info__title">{{ trans('user_constants.labels.relationship_status') }}</span></td>
                            <td><span class="User-profile__info__value">{{ trans('user_constants.relationship_status.' . $user->meta->relationship_status) }}</span></td>
                        </tr>
                        <tr>
                            <td><span class="User-profile__info__title">{{ trans('user_constants.labels.height') }}</span></td>
                            <td><span class="User-profile__info__value">{{ trans('user_constants.height.' . $user->meta->height) }}</span></td>
                        </tr>
                        <tr>
                            <td><span class="User-profile__info__title">{{ trans('user_constants.labels.body_type') }}</span></td>
                            <td><span class="User-profile__info__value">{{ trans('user_constants.body_type.' . $user->meta->body_type) }}</span></td>
                        </tr>
                        <tr>
                            <td><span class="User-profile__info__title">{{ trans('user_constants.labels.eye_color') }}</span></td>
                            <td><span class="User-profile__info__value">{{ trans('user_constants.eye_color.' . $user->meta->eye_color) }}</span></td>
                        </tr>
                        <tr>
                            <td><span class="User-profile__info__title">{{ trans('user_constants.labels.hair_color') }}</span></td>
                            <td><span class="User-profile__info__value">{{ trans('user_constants.hair_color.' . $user->meta->hair_color) }}</span></td>
                        </tr>
                        <tr>
                            <td><span class="User-profile__info__title">{{ trans('user_constants.labels.smoking_habits') }}</span></td>
                            <td><span class="User-profile__info__value">{{ trans('user_constants.smoking_habits.' . $user->meta->smoking_habits) }}</span></td>
                        </tr>
                        <tr>
                            <td><span class="User-profile__info__title">{{ trans('user_constants.labels.drinking_habits') }}</span></td>
                            <td><span class="User-profile__info__value">{{ trans('user_constants.drinking_habits.' . $user->meta->drinking_habits) }}</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="Tile User-profile__about">
    <div class="Tile__heading User-profile__about__heading">
        <i class="fa fa-user"></i> About me
    </div>
    <div class="Tile__body User-profile__about__body">
        <div class="User-profile__text">
            <blockquote>
                <p>{{ $user->meta->about_me }}</p>
            </blockquote>
        </div>
    </div>
</div>