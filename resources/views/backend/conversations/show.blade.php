@extends('backend.layouts.default.layout')

@section('content')

    <?php
        $userARole = ($conversation->userA->roles[0]->id == 2)  ? 'peasant' : 'bot';
        $userBRole = ($conversation->userB->roles[0]->id == 2)  ? 'peasant' : 'bot';
    ?>

<div class="row">
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
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="box-body">
                @foreach(\UserConstants::selectableFields() as $fieldName => $a)
                    @if(isset($conversation->userA->meta->{$fieldName}))
                        <strong>{!! ucfirst(str_replace('_', ' ', $fieldName)) !!}:
                        </strong> {!! @trans('user_constants.' . $fieldName . '.' . $conversation->userA->meta->{$fieldName}) !!} <br>
                    @endif
                @endforeach

                @foreach(array_merge(\UserConstants::textFields(), \UserConstants::textInputs()) as $fieldName)
                    @if(isset($conversation->userA->meta->{$fieldName}) && $conversation->userA->meta->{$fieldName} != '')
                        <strong>{!! @trans('user_constants.' . $fieldName) !!}:
                        </strong> {!! $conversation->userA->meta->{$fieldName} !!}<br>
                    @endif
                @endforeach
            </div>
        </div>
        <div class="box box-userA">
            <div class="box-header with-border">
                <h3 class="box-title">Notes</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <div class="box-group" id="accordion">
                    <!-- we are adding the .panel class so bootstrap.js collapse plugin detects it -->
                    <div class="panel box">
                        <div class="box-header with-border">
                            <h4 class="box-title">
                                <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="false" class="collapsed">
                                    Family
                                </a>
                            </h4>
                        </div>
                        <div id="collapseOne" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
                            <div class="box-body">
                                Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3
                                wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum
                                eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla
                                assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred
                                nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer
                                farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus
                                labore sustainable VHS.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.box-body -->
        </div>
    </div>
    <div class="col-xs-12 col-sm-6 sm_min_pad0">
        <div class="box direct-chat direct-chat-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Conversation</h3>

                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <!-- Conversations are loaded here -->
                <div class="direct-chat-messages scroll-bottom">
                    @foreach($conversation->messages as $message)
                        <?php
                            if ($message->sender_id === $conversation->userA->id) {
                                $user = $conversation->userA;
                                $alignment = '';
                            } else {
                                $user = $conversation->userB;
                                $alignment = 'right';
                            }
                        ?>
                        <!-- Message. Default to the left -->
                        <div class="direct-chat-msg {!! $alignment !!}">
                            <div class="direct-chat-info clearfix">
                                <span class="direct-chat-name pull-{!! ($alignment === 'right') ? 'right' : 'left' !!}">{!! $user->username !!}</span>
                                <span class="direct-chat-timestamp pull-{!! ($alignment === 'right') ? 'left' : 'right' !!}">{!! $message->created_at->diffForHumans() !!}</span>
                            </div>
                            <!-- /.direct-chat-info -->
                            <img class="direct-chat-img" src="{!! $user->profileImage ? \StorageHelper::userImageUrl($user->id, $user->profileImage->filename) : 'http://placehold.it/100x100' !!}" alt="message user image"><!-- /.direct-chat-img -->
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
                                        {!! $message->body !!}
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
                <form role="form" method="POST" action="{!! route('conversations.store') !!}" enctype="multipart/form-data">
                    {!! csrf_field() !!}
                    <input type="hidden" value="{!! $conversation->id !!}" name="conversation_id">
                    <input type="hidden" value="{!! $conversation->userA->id !!}" name="sender_id">
                    <input type="hidden" value="{!! $conversation->userB->id !!}" name="recipient_id">
                    <div style="float: left; padding: 0 10px 0 10px;">
                        <label style="margin-bottom: 0; cursor: pointer">
                            <i class="fa fa-paperclip" style="font-size: 20px; line-height: 34px;"></i>
                            <input type="file" id="attachment" name="attachment" style="display: none;">
                        </label>
                    </div>
                    <div>
                        <div class="input-group">
                            <input type="text" name="message" placeholder="Type Message ..." class="form-control">
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
    </div>
    <div class="col-xs-12 col-sm-3">
        <div class="box box-userB">
            <div class="box-header with-border">
                <h3 class="box-title">
                    {!! $conversation->userB->username !!}
                    <span class="label label-userB">{!! \UserConstants::selectableField('role')[$conversation->userB->roles[0]->id] !!}</span>
                </h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="box-body">
                @foreach(\UserConstants::selectableFields() as $fieldName => $a)
                    @if(isset($conversation->userB->meta->{$fieldName}))
                        <strong>{!! ucfirst(str_replace('_', ' ', $fieldName)) !!}:
                        </strong> {!! @trans('user_constants.' . $fieldName . '.' . $conversation->userB->meta->{$fieldName}) !!} <br>
                    @endif
                @endforeach

                @foreach(array_merge(\UserConstants::textFields(), \UserConstants::textInputs()) as $fieldName)
                    @if(isset($conversation->userB->meta->{$fieldName}) && $conversation->userB->meta->{$fieldName} != '')
                        <strong>{!! @trans('user_constants.' . $fieldName) !!}:
                        </strong> {!! $conversation->userB->meta->{$fieldName} !!}<br>
                    @endif
                @endforeach
            </div>
        </div>
        <div class="box box-userB">
            <div class="box-header with-border">
                <h3 class="box-title">
                    Notes
                </h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="box-body">
                Gryphon replied very politely, 'for I can't put it right; 'not that it was talking in a piteous tone.
                And the Eaglet bent down its head down, and was a large mustard-mine near here. And the moral of.
            </div>
        </div>
    </div>
</div>

@endsection