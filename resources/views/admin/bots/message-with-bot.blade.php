@extends('admin.layouts.default.layout')


@section('content')

    <div id="js-PeasantSelection" class="box box-primary" style="overflow: auto">
        <div class="box-header with-border">
            <h3 class="box-title">Select peasant to send message to</h3>
        </div>

        @if(count($peasants) === 0)
            <div style="padding-left: 15px; font-weight: bold; font-size: 23px; color: #245269"><p>No peasants</p></div>
        @else
            <ul style="max-height: 230px;
                        overflow-y: scroll;
                        border: 1px solid #ccc;"
            >
                @foreach($peasants as $peasant)
                    <li data-peasant-id="{!! $peasant->getId() !!}"
                        data-peasant-profile-image="{!! \StorageHelper::profileImageUrl($peasant) !!}"
                        data-peasant-username="{!! $peasant->username !!}"
                        data-peasant-age="{!! $peasant->meta->dob ? $peasant->meta->dob->diffInYears($carbonNow) : 'Not set'!!}"
                        data-peasant-status="{!! ucfirst(str_replace('_', ' ', $peasant->meta->relationship_status ? \UserConstants::selectableField('relationship_status', $peasant->roles[0]->name)[$peasant->meta->relationship_status] : 'Nog niet ingevuld')) !!}"
                        data-peasant-city="{!! $peasant->meta->city !!}"
                        data-peasant-height="{!! ucfirst(str_replace('_', ' ', $peasant->meta->height ? \UserConstants::selectableField('height', $peasant->roles[0]->name)[$peasant->meta->height] : 'Nog niet ingevuld')) !!}"
                        data-peasant-body-type="{!! ucfirst(str_replace('_', ' ', $peasant->meta->body_type ? \UserConstants::selectableField('body_type', $peasant->roles[0]->name)[$peasant->meta->body_type] : 'Nog niet ingevuld')) !!}"
                        data-peasant-eye-color="{!! ucfirst(str_replace('_', ' ', $peasant->meta->eye_color ? \UserConstants::selectableField('eye_color', $peasant->roles[0]->name)[$peasant->meta->eye_color] : 'Nog niet ingevuld')) !!}"
                        data-peasant-hair-color="{!! ucfirst(str_replace('_', ' ', $peasant->meta->hair_color ? \UserConstants::selectableField('hair_color', $peasant->roles[0]->name)[$peasant->meta->hair_color] : 'Nog niet ingevuld')) !!}"
                        data-peasant-smoking="{!! ucfirst(str_replace('_', ' ', $peasant->meta->smoking_habits ?\UserConstants::selectableField('smoking_habits', $peasant->roles[0]->name)[$peasant->meta->smoking_habits] : 'Nog niet ingevuld')) !!}"
                        data-peasant-drinking="{!! ucfirst(str_replace('_', ' ', $peasant->meta->drinking_habits ? \UserConstants::selectableField('drinking_habits', $peasant->roles[0]->name)[$peasant->meta->drinking_habits] : 'Nog niet ingevuld')) !!}"
                        data-peasant-about-me="{!! $peasant->meta->about_me !!}"
                    >
                        <img style="width: 50px"
                             src="{!! \StorageHelper::profileImageUrl($peasant, true) !!}"
                             alt="peasant image">
                        (ID :<a href="{{ route('admin.peasants.edit.get', ['peasantId' => $peasant->getId()]) }}">{!! $peasant->getId() !!}</a>)
                        <span class="js-fillPeasantData peasantUsername" style="cursor:pointer;">
                            {!! $peasant->username !!}

                            @if(in_array($peasant->getId(), $onlinePeasantIds))
                                <span class="onlineDot"></span>
                            @endif
                        </span>
                    </li>
                @endforeach
            </ul>
        @endif

        <div class="Tile Peasant-profile">
            <div class="col-xs-12">
                <div style="text-align: center; font-size: 18px; padding: 10px 0"
                    class="Tile__heading Peasant-profile__heading">
                    <span id="js-peasantUsername"></span>
                </div>
                <div class="Tile__body Peasant-profile__body">
                    <div class="row">
                        <div class="col-xs-12 col-sm-4 col-md-3">
                            <div class="Peasant-profile__user-image" style="margin-peasanttom: 40px">
                                <img id="js-peasantProfileImage"
                                     style="width: 100%"
                                     src="" alt="peasant image">
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-8 col-md-9">
                            <h5><i class="fa fa-user"></i> Information</h5>
                            <hr>
                            <div style="margin-bottom: 40px"
                                 class="row Peasant-profile__text">
                                <div class="col-xs-6">
                                    <div> <strong>Age:</strong> <span id="js-peasantAge"></span></div>
                                    <div> <strong>Status:</strong>
                                        <span id="js-peasantStatus"></span>
                                    </div>
                                    <div> <strong>City:</strong>
                                        <span id="js-peasantCity"></span>
                                    </div>
                                    <div> <strong>Height:</strong>
                                        <span id="js-peasantHeight"></span>
                                    </div>
                                </div>
                                <div class="col-xs-6">
                                    <div> <strong>Body type:</strong>
                                        <span id="js-peasantBodyType"></span>
                                    </div>
                                    <div> <strong>Eye color:</strong>
                                        <span id="js-peasantEyeColor"></span>
                                    </div>
                                    <div> <strong>Hair color:</strong>
                                        <span id="js-peasantHairColor"></span>
                                    </div>
                                    <div> <strong>Smoking:</strong>
                                        <span id="js-peasantSmoking"></span>
                                    </div>
                                    <div> <strong>Drinking:</strong>
                                        <span id="js-peasantDrinking"></span>
                                    </div>
                                </div>
                            </div>

                            <h5 style="border-peasanttom: 1px solid #ddd"><i class="fa fa-book"></i> About me</h5>
                            <div style="margin-peasanttom: 40px"
                                 class="Peasant-profile__text">
                                <span id="js-aboutMe"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

            <div class="col-xs-12"
                 style="text-align: right; margin-bottom: 20px"
            >
                <a id="js-goToConversation" href="#" class="btn btn-info">
                    Go to conversation
                </a>
            </div>
    </div>

    <div class="box box-primary" style="overflow: auto">
        <div class="box-header with-border">
            <h3 class="box-title">Bot to message peasant with</h3>
        </div>

        <div class="Tile User-profile"
             id="js-peasant-profile"
             data-bot-id="{!! $bot->getId() !!}">
            <div class="col-xs-12">
                <div style="text-align: center; font-size: 18px; padding: 10px 0"
                     class="Tile__heading User-profile__heading">
                    {{ $bot->username }}, (ID: {!! $bot->getId() !!})
                </div>
                <div class="Tile__body User-profile__body">
                    <div class="row">
                        <div class="col-xs-12 col-sm-4 col-md-3">
                            <div class="User-profile__user-image" style="margin-peasanttom: 40px">
                                <img style="width: 100%" src="{{ \StorageHelper::profileImageUrl($bot) }}" alt="user image">
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-8 col-md-9">
                            <h5><i class="fa fa-user"></i> Information</h5>
                            <hr>
                            <div style="margin-peasanttom: 40px"
                                 class="row User-profile__text">
                                <div class="col-xs-6">
                                    <div> <strong>Age:</strong> {{ $bot->meta->dob ? $bot->meta->dob->diffInYears($carbonNow) : 'Nog niet ingevuld' }}</div>
                                    <div> <strong>Status:</strong>
                                        {{ ucfirst(str_replace('_', ' ', $bot->meta->relationship_status ? \UserConstants::selectableField('relationship_status', $bot->roles[0]->name)[$bot->meta->relationship_status] : 'Nog niet ingevuld')) }}
                                    </div>
                                    <div> <strong>City:</strong>
                                        {{ $bot->meta->city }}
                                    </div>
                                    <div> <strong>Height:</strong>
                                        {{ ucfirst(str_replace('_', ' ', $bot->meta->height ? \UserConstants::selectableField('height', $bot->roles[0]->name)[$bot->meta->height ] : 'Nog niet ingevuld')) }}
                                    </div>
                                </div>
                                <div class="col-xs-6">
                                    <div> <strong>Body type:</strong>
                                        {{ ucfirst(str_replace('_', ' ', $bot->meta->body_type ? \UserConstants::selectableField('body_type', $bot->roles[0]->name)[$bot->meta->body_type] : 'Nog niet ingevuld')) }}
                                    </div>
                                    <div> <strong>Eye color:</strong>
                                        {{ ucfirst(str_replace('_', ' ', $bot->meta->eye_color ? \UserConstants::selectableField('eye_color', $bot->roles[0]->name)[$bot->meta->eye_color] : 'Nog niet ingevuld')) }}
                                    </div>
                                    <div> <strong>Hair color:</strong>
                                        {{ ucfirst(str_replace('_', ' ', $bot->meta->hair_color ? \UserConstants::selectableField('hair_color', $bot->roles[0]->name)[$bot->meta->hair_color] : 'Nog niet ingevuld')) }}
                                    </div>
                                    <div> <strong>Smoking:</strong>
                                        {{ ucfirst(str_replace('_', ' ', $bot->meta->smoking_habits ? \UserConstants::selectableField('smoking_habits', $bot->roles[0]->name)[$bot->meta->smoking_habits] : 'Nog niet ingevuld')) }}
                                    </div>
                                    <div> <strong>Drinking:</strong>
                                        {{ ucfirst(str_replace('_', ' ', $bot->meta->drinking_habits ? \UserConstants::selectableField('drinking_habits', $bot->roles[0]->name)[$bot->meta->drinking_habits] : 'Nog niet ingevuld')) }}
                                    </div>
                                </div>
                            </div>

                            @if($bot->meta->about_me)
                                <h5 style="border-peasanttom: 1px solid #ddd"><i class="fa fa-book"></i> About me</h5>
                                <div style="margin-peasanttom: 40px"
                                     class="User-profile__text">
                                    {{ $bot->meta->about_me }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
