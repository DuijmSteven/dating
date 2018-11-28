@extends('admin.layouts.default.layout')


@section('content')

    <div class="box box-primary" style="overflow: auto">
        <div class="box-header with-border">
            <h3 class="box-title">Bot selection to send message from</h3>
        </div>
        <div class="Tile User-profile">
            <div class="form-group">
                <label for="password">City</label>
                <input type="text"
                       class="js-fetchBots form-control"
                       name="city"
                       value="{!! old('city', '') !!}"
                >
                @if ($errors->has('city'))
                    {!! $errors->first('city', '<small class="form-error">:message</small>') !!}
                @endif
            </div>
            <div class="col-xs-12">
                <div style="text-align: center; font-size: 18px; padding: 10px 0"
                    class="Tile__heading User-profile__heading">
                    {{ $peasant->username }}, {{ $peasant->meta->dob->diffInYears($carbonNow)  }}
                </div>
                <div class="Tile__body User-profile__body">
                    <div class="row">
                        <div class="col-xs-12 col-sm-4 col-md-3">
                            <div class="User-profile__user-image" style="margin-bottom: 40px">
                                <img style="width: 100%" src="{{ \StorageHelper::profileImageUrl($peasant) }}" alt="user image">
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-8 col-md-9">
                            <h5><i class="fa fa-user"></i> Information</h5>
                            <hr>
                            <div style="margin-bottom: 40px"
                                 class="row User-profile__text">
                                <div class="col-xs-6">
                                    <div> <strong>Age:</strong> {{ $peasant->meta->dob->diffInYears($carbonNow) }}</div>
                                    <div> <strong>Status:</strong>
                                        {{ ucfirst(str_replace('_', ' ', \UserConstants::selectableField('relationship_status', $peasant->roles[0]->name)[$peasant->meta->relationship_status])) }}
                                    </div>
                                    <div> <strong>Province:</strong>
                                        {{ ucfirst(str_replace('_', ' ', \UserConstants::selectableField('province', $peasant->roles[0]->name)[$peasant->meta->relationship_status])) }}
                                    </div>
                                    <div> <strong>City:</strong>
                                        {{ $peasant->meta->city }}
                                    </div>
                                    <div> <strong>Height:</strong>
                                        {{ ucfirst(str_replace('_', ' ', \UserConstants::selectableField('height', $peasant->roles[0]->name)[$peasant->meta->relationship_status])) }}
                                    </div>
                                </div>
                                <div class="col-xs-6">
                                    <div> <strong>Body type:</strong>
                                        {{ ucfirst(str_replace('_', ' ', \UserConstants::selectableField('body_type', $peasant->roles[0]->name)[$peasant->meta->relationship_status])) }}
                                    </div>
                                    <div> <strong>Eye color:</strong>
                                        {{ ucfirst(str_replace('_', ' ', \UserConstants::selectableField('eye_color', $peasant->roles[0]->name)[$peasant->meta->relationship_status])) }}
                                    </div>
                                    <div> <strong>Hair color:</strong>
                                        {{ ucfirst(str_replace('_', ' ', \UserConstants::selectableField('hair_color', $peasant->roles[0]->name)[$peasant->meta->relationship_status])) }}
                                    </div>
                                    <div> <strong>Smoking:</strong>
                                        {{ ucfirst(str_replace('_', ' ', \UserConstants::selectableField('smoking_habits', $peasant->roles[0]->name)[$peasant->meta->relationship_status])) }}
                                    </div>
                                    <div> <strong>Drinking:</strong>
                                        {{ ucfirst(str_replace('_', ' ', \UserConstants::selectableField('drinking_habits', $peasant->roles[0]->name)[$peasant->meta->relationship_status])) }}
                                    </div>
                                </div>
                            </div>

                            @if($peasant->meta->about_me)
                                <h5 style="border-bottom: 1px solid #ddd"><i class="fa fa-book"></i> About me</h5>
                                <div style="margin-bottom: 40px"
                                     class="User-profile__text">
                                    {{ $peasant->meta->about_me }}
                                </div>
                            @endif
                            @if($peasant->meta->looking_for)
                                <h5 style="border-bottom: 1px solid #eee"><i class="fa fa-search"></i> Looking for</h5>
                                <div class="User-profile__text">
                                    {{ $peasant->meta->looking_for }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="box box-primary" style="overflow: auto">
        <div class="box-header with-border">
            <h3 class="box-title">Peasant to be messaged</h3>
        </div>
        <div class="Tile User-profile">
            <div class="col-xs-12">
                <div style="text-align: center; font-size: 18px; padding: 10px 0"
                     class="Tile__heading User-profile__heading">
                    {{ $peasant->username }}, {{ $peasant->meta->dob->diffInYears($carbonNow)  }}
                </div>
                <div class="Tile__body User-profile__body">
                    <div class="row">
                        <div class="col-xs-12 col-sm-4 col-md-3">
                            <div class="User-profile__user-image" style="margin-bottom: 40px">
                                <img style="width: 100%" src="{{ \StorageHelper::profileImageUrl($peasant) }}" alt="user image">
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-8 col-md-9">
                            <h5><i class="fa fa-user"></i> Information</h5>
                            <hr>
                            <div style="margin-bottom: 40px"
                                 class="row User-profile__text">
                                <div class="col-xs-6">
                                    <div> <strong>Age:</strong> {{ $peasant->meta->dob->diffInYears($carbonNow) }}</div>
                                    <div> <strong>Status:</strong>
                                        {{ ucfirst(str_replace('_', ' ', \UserConstants::selectableField('relationship_status', $peasant->roles[0]->name)[$peasant->meta->relationship_status])) }}
                                    </div>
                                    <div> <strong>Province:</strong>
                                        {{ ucfirst(str_replace('_', ' ', \UserConstants::selectableField('province', $peasant->roles[0]->name)[$peasant->meta->relationship_status])) }}
                                    </div>
                                    <div> <strong>City:</strong>
                                        {{ $peasant->meta->city }}
                                    </div>
                                    <div> <strong>Height:</strong>
                                        {{ ucfirst(str_replace('_', ' ', \UserConstants::selectableField('height', $peasant->roles[0]->name)[$peasant->meta->relationship_status])) }}
                                    </div>
                                </div>
                                <div class="col-xs-6">
                                    <div> <strong>Body type:</strong>
                                        {{ ucfirst(str_replace('_', ' ', \UserConstants::selectableField('body_type', $peasant->roles[0]->name)[$peasant->meta->relationship_status])) }}
                                    </div>
                                    <div> <strong>Eye color:</strong>
                                        {{ ucfirst(str_replace('_', ' ', \UserConstants::selectableField('eye_color', $peasant->roles[0]->name)[$peasant->meta->relationship_status])) }}
                                    </div>
                                    <div> <strong>Hair color:</strong>
                                        {{ ucfirst(str_replace('_', ' ', \UserConstants::selectableField('hair_color', $peasant->roles[0]->name)[$peasant->meta->relationship_status])) }}
                                    </div>
                                    <div> <strong>Smoking:</strong>
                                        {{ ucfirst(str_replace('_', ' ', \UserConstants::selectableField('smoking_habits', $peasant->roles[0]->name)[$peasant->meta->relationship_status])) }}
                                    </div>
                                    <div> <strong>Drinking:</strong>
                                        {{ ucfirst(str_replace('_', ' ', \UserConstants::selectableField('drinking_habits', $peasant->roles[0]->name)[$peasant->meta->relationship_status])) }}
                                    </div>
                                </div>
                            </div>

                            @if($peasant->meta->about_me)
                                <h5 style="border-bottom: 1px solid #ddd"><i class="fa fa-book"></i> About me</h5>
                                <div style="margin-bottom: 40px"
                                     class="User-profile__text">
                                    {{ $peasant->meta->about_me }}
                                </div>
                            @endif
                            @if($peasant->meta->looking_for)
                                <h5 style="border-bottom: 1px solid #eee"><i class="fa fa-search"></i> Looking for</h5>
                                <div class="User-profile__text">
                                    {{ $peasant->meta->looking_for }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
