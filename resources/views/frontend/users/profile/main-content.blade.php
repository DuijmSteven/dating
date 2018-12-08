<div class="Tile User-profile__info">
    <div class="Tile__heading User-profile__info__heading">
        <h5><i class="fa fa-info"></i> Information</h5>
    </div>
    <div class="Tile__body User-profile__info__body">
        <div class="row User-profile__text">
            <div class="col-xs-6">
                <div><strong>Age:</strong> {{ $user->meta->dob->diffInYears($carbonNow) }}</div>
                <div><strong>Status:</strong>
                    {{ ucfirst(str_replace('_', ' ', \UserConstants::selectableField('relationship_status', $user->roles[0]->name)[$user->meta->relationship_status])) }}
                </div>
                <div><strong>Province:</strong>
                    {{ ucfirst(str_replace('_', ' ', \UserConstants::selectableField('province', $user->roles[0]->name)[$user->meta->province])) }}
                </div>
                <div><strong>City:</strong>
                    {{ $user->meta->city }}
                </div>
                <div><strong>Height:</strong>
                    {{ ucfirst(str_replace('_', ' ', \UserConstants::selectableField('height', $user->roles[0]->name)[$user->meta->height])) }}
                </div>
            </div>
            <div class="col-xs-6">
                <div><strong>Body type:</strong>
                    {{ ucfirst(str_replace('_', ' ', \UserConstants::selectableField('body_type', $user->roles[0]->name)[$user->meta->body_type])) }}
                </div>
                <div><strong>Eye color:</strong>
                    {{ ucfirst(str_replace('_', ' ', \UserConstants::selectableField('eye_color', $user->roles[0]->name)[$user->meta->eye_color])) }}
                </div>
                <div><strong>Hair color:</strong>
                    {{ ucfirst(str_replace('_', ' ', \UserConstants::selectableField('hair_color', $user->roles[0]->name)[$user->meta->hair_color])) }}
                </div>
                <div><strong>Smoking:</strong>
                    {{ ucfirst(str_replace('_', ' ', \UserConstants::selectableField('smoking_habits', $user->roles[0]->name)[$user->meta->smoking_habits])) }}
                </div>
                <div><strong>Drinking:</strong>
                    {{ ucfirst(str_replace('_', ' ', \UserConstants::selectableField('drinking_habits', $user->roles[0]->name)[$user->meta->drinking_habits])) }}
                </div>
            </div>
        </div>
    </div>
</div>

<div class="Tile User-profile__about">
    <div class="Tile__heading User-profile__about__heading">
        <h5><i class="fa fa-user"></i> About me</h5>
    </div>
    <div class="Tile__body User-profile__about__body">
        <div class="User-profile__text">
            {{ $user->meta->about_me }}
        </div>
    </div>
</div>