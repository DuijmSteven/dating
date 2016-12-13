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
            <div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
                <div class="panel-body">
                    <div class="row">
                        @foreach($newConversations as $conversation)
                            @php
                                $message = $conversation->messages->last();

                                if ($message->sender_id === $conversation->userA->id) {
                                    $user = $conversation->userA;
                                } else {
                                    $user = $conversation->userB;
                                }
                            @endphp
                            <div class="col-xs-12 col-sm-4 col-md-3">
                                <!-- Widget: user widget style 1 -->
                                <div class="box box-widget widget-user-2 default-border">
                                    <!-- Add the bg color to the header using any of the bg-* classes -->
                                    <div class="convo-tile">
                                        <img src="http://placehold.it/70x50">
                                        <div class="convo-tile_text">
                                            <div class="username">{!! $user->username !!}</div>
                                            <div class="date">{!! $message->created_at !!}</div>
                                        </div>
                                        <!-- /.widget-user-image -->
                                    </div>
                                    <div class="box-footer no-padding">
                                        <div class="text-summary">
                                            {!! $message->body !!}
                                        </div>
                                    </div>
                                    <div>
                                        <a href="conversations/{!! $conversation->id !!}" class="btn btn-primary btn-flat btn-block">View Conversation</a>
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
                        New Messages <span class="label label-success">{!! count($conversationsWithNewMessages) !!}</span>
                    </a>
                </h4>
            </div>
            <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
                <div class="panel-body">
                    <div class="row">
                        @foreach($conversationsWithNewMessages as $conversation)
                            @php
                                $message = $conversation->messages->last();

                                if ($message->sender_id === $conversation->userA->id) {
                                    $user = $conversation->userA;
                                } else {
                                    $user = $conversation->userB;
                                }
                            @endphp
                            <div class="col-xs-12 col-sm-4 col-md-3">
                                <!-- Widget: user widget style 1 -->
                                <div class="box box-widget widget-user-2 default-border">
                                    <!-- Add the bg color to the header using any of the bg-* classes -->
                                    <div class="convo-tile">
                                        <img src="http://placehold.it/70x50">
                                        <div class="convo-tile_text">
                                            <div class="username">{!! $user->username !!}</div>
                                            <div class="date">{!! $message->created_at !!}</div>
                                        </div>
                                        <!-- /.widget-user-image -->
                                    </div>
                                    <div class="box-footer no-padding">
                                        <div class="text-summary">
                                            {!! $message->body !!}
                                        </div>
                                    </div>
                                    <div>
                                        <a href="conversations/{!! $conversation->id !!}" class="btn btn-primary btn-flat btn-block">View Conversation</a>
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
                        Flirts <span class="label label-success">{!! count($newFlirts) !!}</span>
                    </a>
                </h4>
            </div>
            <div id="collapseThree" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
                <div class="panel-body">
                    <div class="row">
                        @foreach($newFlirts as $flirt)
                            <div class="col-xs-12 col-sm-4 col-md-3">
                                <div class="box box-primary">
                                    <div class="box-body no-padding">
                                        <ul class="users-list-flirts clearfix">
                                            <li>
                                                <img src="https://almsaeedstudio.com/themes/AdminLTE/dist/img/user1-128x128.jpg" alt="User Image">
                                                <a class="users-list-name" href="#">{!! $flirt->sender->username !!}</a>
                                            </li>
                                            <li>
                                                <i class="fa fa-arrow-circle-o-right" style="font-size: 4em; color: #dd4b39"></i>
                                            </li>
                                            <li>
                                                <img src="https://almsaeedstudio.com/themes/AdminLTE/dist/img/user7-128x128.jpg" alt="User Image">
                                                <a class="users-list-name" href="#">{!! $flirt->recipient->username !!}</a>
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
