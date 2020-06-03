@extends('admin.layouts.default.layout')


@section('content')

    <div id="js-BotSelection" class="box box-primary js-publicChat" style="overflow: auto">
        <div class="box-header with-border">
            <h3 class="box-title">Select bot to send message from</h3>
        </div>

        @if(count($bots) === 0)
            <div style="padding-left: 15px; font-weight: bold; font-size: 23px; color: #245269"><p>No bots</p></div>
        @else
            <ul style="max-height: 230px;
                        overflow-y: scroll;
                        border: 1px solid #ccc;"
            >
                @foreach($bots as $bot)
                    <li data-bot-id="{!! $bot->getId() !!}"
                        data-bot-profile-image="{!! \StorageHelper::profileImageUrl($bot) !!}"
                        data-bot-username="{!! $bot->username !!}"
                        data-bot-age="{!! $bot->meta->dob ? $bot->meta->dob->diffInYears($carbonNow) : 'Not set'!!}"
                        data-bot-status="{!! ucfirst(str_replace('_', ' ', $bot->meta->relationship_status ? \UserConstants::selectableField('relationship_status', $bot->roles[0]->name)[$bot->meta->relationship_status] : 'Nog niet ingevuld')) !!}"
                        data-bot-city="{!! $bot->meta->city !!}"
                        data-bot-height="{!! ucfirst(str_replace('_', ' ', $bot->meta->height ? \UserConstants::selectableField('height', $bot->roles[0]->name)[$bot->meta->height] : 'Nog niet ingevuld')) !!}"
                        data-bot-body-type="{!! ucfirst(str_replace('_', ' ', $bot->meta->body_type ? \UserConstants::selectableField('body_type', $bot->roles[0]->name)[$bot->meta->body_type] : 'Nog niet ingevuld')) !!}"
                        data-bot-eye-color="{!! ucfirst(str_replace('_', ' ', $bot->meta->eye_color ? \UserConstants::selectableField('eye_color', $bot->roles[0]->name)[$bot->meta->eye_color] : 'Nog niet ingevuld')) !!}"
                        data-bot-hair-color="{!! ucfirst(str_replace('_', ' ', $bot->meta->hair_color ? \UserConstants::selectableField('hair_color', $bot->roles[0]->name)[$bot->meta->hair_color] : 'Nog niet ingevuld')) !!}"
                        data-bot-smoking="{!! ucfirst(str_replace('_', ' ', $bot->meta->smoking_habits ?\UserConstants::selectableField('smoking_habits', $bot->roles[0]->name)[$bot->meta->smoking_habits] : 'Nog niet ingevuld')) !!}"
                        data-bot-drinking="{!! ucfirst(str_replace('_', ' ', $bot->meta->drinking_habits ? \UserConstants::selectableField('drinking_habits', $bot->roles[0]->name)[$bot->meta->drinking_habits] : 'Nog niet ingevuld')) !!}"
                        data-bot-about-me="{!! $bot->meta->about_me !!}"
                    >
                        <img style="width: 50px"
                             src="{!! \StorageHelper::profileImageUrl($bot, true) !!}"
                             alt="bot image">
                        (ID :<a
                            href="{{ route('admin.bots.edit.get', ['botId' => $bot->getId()]) }}">{!! $bot->getId() !!}</a>)
                        <span class="js-fillBotData botUsername" style="cursor:pointer;">
                            {!! $bot->username !!}

                            @if(in_array($bot->getId(), $onlineBotIds))
                                <span class="onlineDot"></span>
                            @endif
                        </span>
                    </li>
                @endforeach
            </ul>
        @endif

        <div class="Tile Bot-profile">
            <div class="col-xs-12">
                <div style="text-align: center; font-size: 18px; padding: 10px 0"
                     class="Tile__heading Bot-profile__heading">
                    <span id="js-botUsername"></span>
                </div>
                <div class="Tile__body Bot-profile__body">
                    <div class="row">
                        <div class="col-xs-12 col-sm-4 col-md-3">
                            <div class="Bot-profile__user-image" style="margin-bottom: 40px">
                                <img id="js-botProfileImage"
                                     style="width: 100%"
                                     src="" alt="bot image">
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-8 col-md-9">
                            <h5><i class="fa fa-user"></i> Information</h5>
                            <hr>
                            <div style="margin-bottom: 40px"
                                 class="row Bot-profile__text">
                                <div class="col-xs-6">
                                    <div><strong>Age:</strong> <span id="js-botAge"></span></div>
                                    <div><strong>Status:</strong>
                                        <span id="js-botStatus"></span>
                                    </div>
                                    <div><strong>City:</strong>
                                        <span id="js-botCity"></span>
                                    </div>
                                    <div><strong>Height:</strong>
                                        <span id="js-botHeight"></span>
                                    </div>
                                </div>
                                <div class="col-xs-6">
                                    <div><strong>Body type:</strong>
                                        <span id="js-botBodyType"></span>
                                    </div>
                                    <div><strong>Eye color:</strong>
                                        <span id="js-botEyeColor"></span>
                                    </div>
                                    <div><strong>Hair color:</strong>
                                        <span id="js-botHairColor"></span>
                                    </div>
                                    <div><strong>Smoking:</strong>
                                        <span id="js-botSmoking"></span>
                                    </div>
                                    <div><strong>Drinking:</strong>
                                        <span id="js-botDrinking"></span>
                                    </div>
                                </div>
                            </div>

                            <h5 style="border-bottom: 1px solid #ddd"><i class="fa fa-book"></i> About me</h5>
                            <div style="margin-bottom: 40px"
                                 class="Bot-profile__text">
                                <span id="js-aboutMe"></span>
                            </div>

                            <form method="POST" action="{{ route('admin.public-chat-items.send-as-bot.post') }}">
                                @csrf

                                <input id="sender_id_input" type="hidden" name="sender_id" value="">
                                <input type="hidden" name="type" value="{{ \App\PublicChatItem::TYPE_ADMIN }}">

                                <textarea
                                    class="form-control"
                                    id="text"
                                    name="text"
                                    maxlength="200"
                                    rows="2"
                                ></textarea>

                                <button
                                    class="btn"
                                    type="submit"
                                >
                                    Send
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
