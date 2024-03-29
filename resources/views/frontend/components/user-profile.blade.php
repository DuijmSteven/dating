<div class="Tile User-profile">
    <div class="Tile__heading User-profile__heading">
        {{ $user->username }}, {{ $user->meta->dob->diffInYears($carbonNow)  }}
    </div>
    <div class="Tile__body User-profile__body">
        <div class="row">
            <div class="col-xs-12 col-sm-3">
                <div class="User-profile__user-image">
                    <img src="{{ \StorageHelper::profileImageUrl($user) }}" alt="user image">
                </div>
                <div class="row">
                    @php
                        $galleryImages = $user->nonProfileImages;
                    @endphp
                    @foreach($galleryImages as $galleryImage)
                        <div class="col-xs-6">
                            <div class="User-profile__user-image">
                                <img src="{{ \StorageHelper::userImageUrl($galleryImage->user_id, $galleryImage->filename) }}" alt="user image">
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="text-center User-profile__favorite-button">
                    @include('frontend.components.button', [
                        'buttonContext' => 'form',
                        'buttonType' => 'submit',
                        'buttonState' => 'primary',
                        'buttonText' => 'Favorite'
                    ])
                </div>
            </div>
            <div class="col-xs-12 col-sm-9">
                <h5><i class="fa fa-user"></i> Information</h5>
                <hr>
                <div class="row User-profile__text">
                    <div class="col-xs-6">
                        <div> <strong>{{ trans(config('app.directory_name') . '/user_constants.age') }}:</strong> {{ $user->meta->dob->diffInYears($carbonNow) }}</div>
                        <div> <strong>{{ trans(config('app.directory_name') . '/user_constants.labels.relationship_status') }}:</strong>
                            {{ ucfirst(str_replace('_', ' ', \UserConstants::selectableField('relationship_status', $user->roles[0]->name)[$user->meta->relationship_status])) }}
                        <div> <strong>{{ trans(config('app.directory_name') . '/user_constants.city') }}:</strong>
                            {{ $user->meta->city }}
                        </div>
                        <div> <strong>{{ trans(config('app.directory_name') . '/user_constants.labels.height') }}:</strong>
                            {{ ucfirst(str_replace('_', ' ', \UserConstants::selectableField('height', $user->roles[0]->name)[$user->meta->relationship_status])) }}
                        </div>
                    </div>
                    <div class="col-xs-6">
                        <div> <strong>{{ trans(config('app.directory_name') . '/user_constants.labels.body_type') }}:</strong>
                            {{ ucfirst(str_replace('_', ' ', \UserConstants::selectableField('body_type', $user->roles[0]->name)[$user->meta->relationship_status])) }}
                        </div>
                        <div> <strong>{{ trans(config('app.directory_name') . '/user_constants.labels.eye_color') }}:</strong>
                            {{ ucfirst(str_replace('_', ' ', \UserConstants::selectableField('eye_color', $user->roles[0]->name)[$user->meta->relationship_status])) }}
                        </div>
                        <div> <strong>{{ trans(config('app.directory_name') . '/user_constants.labels.hair_color') }}:</strong>
                            {{ ucfirst(str_replace('_', ' ', \UserConstants::selectableField('hair_color', $user->roles[0]->name)[$user->meta->relationship_status])) }}
                        </div>
                        <div> <strong>{{ trans(config('app.directory_name') . '/user_constants.labels.smoking_habits') }}:</strong>
                            {{ ucfirst(str_replace('_', ' ', \UserConstants::selectableField('smoking_habits', $user->roles[0]->name)[$user->meta->relationship_status])) }}
                        </div>
                        <div> <strong>{{ trans(config('app.directory_name') . '/user_constants.labels.drinking_habits') }}:</strong>
                            {{ ucfirst(str_replace('_', ' ', \UserConstants::selectableField('drinking_habits', $user->roles[0]->name)[$user->meta->relationship_status])) }}
                        </div>
                    </div>
                </div>
                <h5><i class="fa fa-book"></i> {{ trans(config('app.directory_name') . '/user_constants.about_me') }}</h5>
                <hr>
                <div class="User-profile__text">
                    {{ $user->meta->about_me }}
                </div>
            </div>
        </div>
    </div>
</div>