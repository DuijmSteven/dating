@extends('admin.layouts.default.layout')

@section('content')

    @php
        $userARole = ($conversation->userA->roles[0]->id == 2) ? 'peasant' : 'bot';
        $userBRole = ($conversation->userB->roles[0]->id == 2) ? 'peasant' : 'bot';
    @endphp

    <div
        class="row JS--showConversation showConversation"
        data-conversation-id="{{ $conversation->getId() }}"
        {!! isset($lockedAt) ? "data-locked-at='" . $lockedAt . "'" : "" !!}
    >
        @if(isset($hasCountdown) && $hasCountdown)
            <div class="operatorCountdown JS--operatorCountdown"></div>
        @endif

        <div class="col-xs-12 col-sm-3">
            <div class="box box-userA">
                <div class="box-header with-border">
                    <h3 class="box-title">
                        {!! $conversation->userA->username !!}
                        <span class="label label-userA">
                        {!! $userARole !!}
                    </span>
                    </h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                class="fa fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="box-body">
                    <div style="text-align: center">
                        <img style="max-width: 200px"
                             src="{!! \StorageHelper::profileImageUrl($conversation->userA) !!}"
                             alt="user image"
                        >
                    </div>

                    @foreach(\UserConstants::selectableFields() as $fieldName => $a)
                        @if(isset($conversation->userA->meta->{$fieldName}))
                            <strong>{!! ucfirst(str_replace('_', ' ', $fieldName)) !!}:
                            </strong> {!! @trans('user_constants.' . $fieldName . '.' . $conversation->userA->meta->{$fieldName}) !!}
                            <br>
                        @endif
                    @endforeach

                    @foreach(array_merge(\UserConstants::textFields(), \UserConstants::textInputs()) as $fieldName)
                        @if(isset($conversation->userA->meta->{$fieldName}) && $conversation->userA->meta->{$fieldName} != '')
                            @if($fieldName === 'dob')
                                <strong>{!! @trans('user_constants.' . $fieldName) !!}:
                                </strong> {!! $conversation->userA->meta->{$fieldName} !!}<br>
                                <strong>{!! @trans('user_constants.age') !!}:
                                </strong> {!! $conversation->userA->meta->{$fieldName}->diffInYears(\Carbon\Carbon::now('Europe/Amsterdam')) !!}<br>
                            @else
                                <strong>{!! @trans('user_constants.' . $fieldName) !!}:
                                </strong> {!! $conversation->userA->meta->{$fieldName} !!}<br>
                            @endif

                        @endif
                    @endforeach
                </div>
            </div>

            @if($conversation->id)
                {{-- Include Notes module for user B --}}
                @include('admin.conversations.partials.notes', [
                    'moduleId' => 'A',
                    'userClassName' => 'userA',
                    'notes' => $userANotes,
                    'userId' => $conversation->userA->id
                ])
            @endif

        </div>
        <div class="col-xs-12 col-sm-6 sm_min_pad0">
            <div class="box direct-chat direct-chat-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">
                        Conversation {!! isset($lockedByUserId) ? '<span class="lockedByUserIdWarning">LOCKED BY: ' . $lockedByUserId . ' - ' . $lockedByUser->getUsername() . '</span>' : '' !!}</h3>

                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                class="fa fa-minus"></i>
                        </button>
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <!-- Conversations are loaded here -->
                    <div class="direct-chat-messages scroll-bottom">
                    @php
                        $userAProfileImageUrl = \StorageHelper::profileImageUrl($conversation->userA, true);
                        $userBProfileImageUrl = \StorageHelper::profileImageUrl($conversation->userB, true);
                    @endphp

                    @foreach($conversation->messages as $message)
                        @php
                            if ($message->sender_id === $conversation->userA->id) {
                                $user = $conversation->userA;
                                $alignment = '';
                                $profileImageUrl = $userAProfileImageUrl;
                            } else {
                                $user = $conversation->userB;
                                $alignment = 'right';
                                $profileImageUrl = $userBProfileImageUrl;
                            }
                        @endphp
                        <!-- Message. Default to the left -->
                            <div class="direct-chat-msg {!! $alignment !!}">
                                <div class="direct-chat-info clearfix">
                                        <span
                                            class="direct-chat-name pull-{!! ($alignment === 'right') ? 'right' : 'left' !!}">{!! $user->username !!}</span>
                                    <span
                                        class="direct-chat-timestamp pull-{!! ($alignment === 'right') ? 'left' : 'right' !!}">{!! $message->created_at->diffForHumans() !!} ({!! $message->created_at->format('d-m-Y H:i:s') !!})</span>
                                </div>
                                <img class="direct-chat-img" src="{{ $profileImageUrl }}"
                                     alt="message user image">
                                <div class="direct-chat-text {!! ($alignment === 'right') ? 'userB' : 'userA' !!}">
                                    @if($message->type === 'flirt')
                                        <i class="fa fa-heart" style="color:red"></i>
                                    @else
                                        @if($message->has_attachment)
                                            <div>
                                                <img height="100" src="{!! \StorageHelper::messageAttachmentUrl(
                                                    $conversation->id,
                                                    $message->attachment->filename
                                                ) !!}"
                                                     alt="">
                                            </div>
                                        @endif

                                        @if($message->body)
                                            @if($authenticatedUser->isAdmin())
                                                {!! $message->body !!}
                                            @else
                                                {!! \App\Helpers\FormattingHelper::stripPhonesAndEmails($message->body) !!}
                                            @endif
                                        @endif
                                    @endif
                                </div>
                                <!-- /.direct-chat-text -->
                            </div>
                        @endforeach
                    </div>
                    <!--/.direct-chat-messages-->
                </div>
                <!-- /.box-body -->

                <div class="box-footer">
                    <form
                        class="JS--sendMessageForm"
                        role="form"
                        method="POST"
                        action="{!! route('admin.conversations.store') !!}"
                        enctype="multipart/form-data"
                    >
                        {!! csrf_field() !!}
                        <input type="hidden" value="{!! $conversation->id !!}" name="conversation_id">
                        <input type="hidden" value="{!! $conversation->userA->id !!}" name="sender_id">
                        <input type="hidden" value="{!! $conversation->userB->id !!}" name="recipient_id">
                        <div style="float: left; padding: 0 10px 0 10px;">
                            <label style="margin-bottom: 0; cursor: pointer">
                                <i class="fa fa-paperclip" style="font-size: 20px; line-height: 34px;"></i>
                                <input type="file" accept=".png,.jpg,.jpeg" id="attachment" name="attachment"
                                       style="display: none;">
                            </label>
                        </div>
                        <div>
                            <div class="input-group">
                                <textarea
                                    class="form-control JS--sendMessageTextarea"
                                    name="message"
                                    id=""
                                    rows="5"
                                    style="width: 94%"
                                    placeholder="Type Message ..."
                                >{{ old('message', '') }}</textarea>
{{--                                <input type="text" name="message" placeholder="Type Message ..." class="form-control">--}}
                                <span class="input-group-btn">
                                    <button type="submit" class="btn btn-success btn-flat">Send</button>
                                </span>
                            </div>
                        </div>
                        <div class="attachment-path-container">
                            Selected image name: <span class="attachment-path"></span>
                        </div>
                    </form>
                </div>
                <!-- /.box-footer-->
            </div>
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Invisible images</h3>

                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                class="fa fa-minus"></i>
                        </button>
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <div class="conversation__invisibleImages">
                        <?php $bot = $conversation->userA ?>
                        @foreach($bot->invisibleImages as $invisibleImage)
                            <div class="col-xs-12 col-sm-4">
                                <form role="form" method="POST"
                                      action="{!! route('admin.conversations.add-invisible-image') !!}"
                                      enctype="multipart/form-data">
                                    {!! csrf_field() !!}
                                    <input type="hidden" value="{!! $conversation->id !!}" name="conversation_id">
                                    <input type="hidden" value="{!! $conversation->userA->id !!}" name="sender_id">
                                    <input type="hidden" value="{!! $conversation->userB->id !!}" name="recipient_id">
                                    <input type="hidden" value="{!! $invisibleImage->id !!}" name="image_id">

                                    <textarea name="body" cols="30" rows="10" style="width: 100%; height: 200px"
                                              class="hidden"></textarea>

                                    <img style=""
                                         src="{{ \App\Helpers\StorageHelper::userImageUrl($bot->getId(), $invisibleImage->filename) }}"
                                         alt="">
                                    <input class="selectInvisibleImage" type="radio" value="{!! $invisibleImage->id!!}"
                                           name="image_id">
                                    @if ($errors->has('image_id') && old('image_id_error_check') == $invisibleImage->getId())
                                        {!! $errors->first('image_id', '<small class="form-error">:message</small>') !!}
                                    @endif
                                    <button style="width: 100%" type="submit" class="btn btn-success btn-flat">Send
                                    </button>

                                </form>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Received user images</h3>

                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                class="fa fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="box-body receivedUserImages">
                    @foreach($conversation->messages->reverse() as $message)
                        @if($message->attachment && $message->sender->roles[0]->id === \App\User::TYPE_PEASANT)
                            <div class="receivedUserImage"><img height="100" src="{!! \StorageHelper::messageAttachmentUrl(
                                                        $conversation->id,
                                                        $message->attachment->filename
                                                    ) !!}"
                                     alt="">

                                <p class="receivedUserImageInfo">
                                    {{ $message->getCreatedAt()->diffForHumans() }}
                                </p>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
        <div class="col-xs-12 col-sm-3">
            <div class="box box-userB">
                <div class="box-header with-border">
                    <h3 class="box-title">
                        {!! $conversation->userB->username !!}
                        <span
                            class="label label-userB">{!! \UserConstants::selectableField('role')[$conversation->userB->roles[0]->id] !!}</span>
                    </h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                class="fa fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="box-body">
                    <div style="text-align: center">
                        <img style="max-width: 200px"
                             src="{!! \StorageHelper::profileImageUrl($conversation->userB) !!}"
                             alt="user image"
                        >
                    </div>

                    @foreach(\UserConstants::selectableFields() as $fieldName => $a)
                        @if(isset($conversation->userB->meta->{$fieldName}))
                            <strong>{!! ucfirst(str_replace('_', ' ', $fieldName)) !!}:
                            </strong> {!! @trans('user_constants.' . $fieldName . '.' . $conversation->userB->meta->{$fieldName}) !!}
                            <br>
                        @endif
                    @endforeach

                    @foreach(array_merge(\UserConstants::textFields(), \UserConstants::textInputs()) as $fieldName)
                        @if(isset($conversation->userB->meta->{$fieldName}) && $conversation->userB->meta->{$fieldName} != '')
                            @if($fieldName === 'dob')
                                <strong>{!! @trans('user_constants.' . $fieldName) !!}:
                                </strong> {!! $conversation->userB->meta->{$fieldName} !!}<br>
                                <strong>{!! @trans('user_constants.age') !!}:
                                </strong> {!! $conversation->userB->meta->{$fieldName}->diffInYears(\Carbon\Carbon::now('Europe/Amsterdam')) !!}<br>
                            @else
                                <strong>{!! @trans('user_constants.' . $fieldName) !!}:
                                </strong> {!! $conversation->userB->meta->{$fieldName} !!}<br>
                            @endif

                        @endif
                    @endforeach
                </div>
            </div>

            @if($conversation->id)
                {{-- Include Notes module for user B --}}
                @include('admin.conversations.partials.notes', [
                    'moduleId' => 'B',
                    'userClassName' => 'userB',
                    'notes' => $userBNotes,
                    'userId' => $conversation->userB->id
                ])
            @endif
        </div>
    </div>

    @include('admin.conversations.partials.notes-modal', [
        'conversationId' => $conversation->id
    ])

    @if($authenticatedUser->isAdmin() && $conversation->getLockedByUserId())
        <a href="{{ route('admin.conversations.unlock', ['conversationId' => $conversation->getId()]) }}"
           class="btn btn-success btn-flat"
        >
            Unlock conversation
        </a>

        @if($conversation->getReplyableAt())
            <a href="{!! route('admin.conversations.set-unreplyable', [$conversation->getId()]) !!}"
               class="btn btn-default">Make unreplyable</a>
        @endif
    @endif

@endsection
