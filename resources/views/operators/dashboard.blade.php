@extends('backend.layouts.default.layout')


@section('content')



    <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
        <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="headingOne">
                <h4 class="panel-title">
                    <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                        New Conversations <span class="label label-success">{!! count($newConversations) !!}</span>
                    </a>
                </h4>
            </div>
            <div id="collapseOne" class="panel-collapse" role="tabpanel" aria-labelledby="headingOne">
                <div class="panel-body">
                    <div class="row">
                        @foreach($newConversations as $conversation)
                            <?php
                                if ($conversation->userB->roles[0]->id === 3) {
                                    $userA = $conversation->userA;

                                    $conversation->userA = $conversation->userB;
                                    $conversation->userB = $userA;
                                }
                            ?>
                            <div class="col-xs-12 col-sm-4 col-md-3">
                                <!-- Widget: user widget style 1 -->
                                <div class="box box-widget widget-user-2 default-border">
                                    <!-- Add the bg color to the header using any of the bg-* classes -->
                                    <div class="convo-tile {!! \UserConstants::selectableField('role')[$conversation->userA->roles[0]->id] !!}">
                                        <div class="convo-tile_text">
                                            <div class="username">{!! $conversation->userA->username !!} (ID: {!! $conversation->userA->id !!})</div>
                                        </div>
                                        <!-- /.widget-user-image -->
                                    </div>
                                    <div class="convo-tile {!! \UserConstants::selectableField('role')[$conversation->userB->roles[0]->id] !!}">
                                        <div class="convo-tile_text">
                                            <div class="username">{!! $conversation->userB->username !!} (ID: {!! $conversation->userB->id !!})</div>
                                        </div>
                                        <!-- /.widget-user-image -->
                                    </div>
                                    <div class="box-footer no-padding">
                                        <div class="text-summary text-center">
                                            @if($conversation->last_message_type === 'flirt')
                                                <i class="fa fa-heart" style="color:red"></i>
                                            @else
                                                @if($lastMessage->has_attachment)
                                                    <i class="fa fa-fw fa-paperclip"></i>
                                                @endif
                                                @if($lastMessage->body)
                                                    <em>"{!! $lastMessage->body !!}"</em>
                                                @endif
                                            @endif
                                        </div>
                                    </div>
                                    <div>
                                        <a href="conversations/{!! $conversation->id !!}" class="btn btn-default btn-flat btn-block">View Conversation</a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="headingTwo">
                <h4 class="panel-title">
                    <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                        Unreplied Conversations <span class="label label-success">{!! count($unrepliedConversations) !!}</span>
                    </a>
                </h4>
            </div>
            <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
                <div class="panel-body">
                    <div class="row">
                        @foreach($unrepliedConversations as $conversation)
                            <?php
                            $lastMessage = $conversation->messages->last();

                            if ($conversation->userB->roles[0]->id === 3) {
                                $userA = $conversation->userA;

                                $conversation->userA = $conversation->userB;
                                $conversation->userB = $userA;
                            }
                            ?>
                            <div class="col-xs-12 col-sm-4 col-md-3">
                                <!-- Widget: user widget style 1 -->
                                <div class="box box-widget widget-user-2 default-border">
                                    <!-- Add the bg color to the header using any of the bg-* classes -->
                                    <div class="convo-tile {!! \UserConstants::selectableField('role')[$conversation->userA->roles[0]->id] !!}">
                                        <div class="convo-tile_text">
                                            <div class="username">{!! $conversation->userA->username !!} (ID: {!! $conversation->userA->id !!})</div>
                                        </div>
                                        <!-- /.widget-user-image -->
                                    </div>
                                    <div class="convo-tile {!! \UserConstants::selectableField('role')[$conversation->userB->roles[0]->id] !!}">
                                        <div class="convo-tile_text">
                                            <div class="username">{!! $conversation->userB->username !!} (ID: {!! $conversation->userB->id !!})</div>
                                        </div>
                                        <!-- /.widget-user-image -->
                                    </div>
                                    <div class="box-footer no-padding">
                                        <div class="text-summary text-center">
                                            @if($lastMessage->type === 'flirt')
                                                <i class="fa fa-heart" style="color:red"></i>
                                            @else
                                                <em>"{!! $lastMessage->body !!}"</em>
                                            @endif
                                        </div>
                                    </div>
                                    <div>
                                        <a href="conversations/{!! $conversation->id !!}" class="btn btn-default btn-flat btn-block">View Conversation</a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="headingThree">
                <h4 class="panel-title">
                    <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                        Flirts <span class="label label-success">{!! count($newFlirtConversations) !!}</span>
                    </a>
                </h4>
            </div>
            <div id="collapseThree" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
                <div class="panel-body">
                    <div class="row">
                        @foreach($newFlirtConversations as $conversation)

                            <?php
                                $lastMessage = $conversation->messages->last();

                                if ($conversation->userB->roles[0]->id === 3) {
                                    $userA = $conversation->userA;

                                    $conversation->userA = $conversation->userB;
                                    $conversation->userB = $userA;
                                }
                            ?>

                            <div class="col-xs-12 col-sm-4 col-md-3">
                                <div class="box box-primary">
                                    <div class="box-body no-padding">
                                        <ul class="users-list-flirts clearfix">
                                            <li>
                                                <img src="https://almsaeedstudio.com/themes/AdminLTE/dist/img/user1-128x128.jpg" alt="User Image">
                                                <a class="users-list-name" href="#">{!! $lastMessage->sender->username !!}</a>
                                            </li>
                                            <li>
                                                <i class="fa fa-arrow-circle-o-right" style="font-size: 4em; color: #dd4b39"></i>
                                            </li>
                                            <li>
                                                <img src="https://almsaeedstudio.com/themes/AdminLTE/dist/img/user7-128x128.jpg" alt="User Image">
                                                <a class="users-list-name" href="#">{!! $lastMessage->recipient->username !!}</a>
                                            </li>
                                        </ul>
                                    </div>
                                    <div>
                                        <a href="" class="btn btn-primary btn-flat btn-block">View</a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>



@endsection
