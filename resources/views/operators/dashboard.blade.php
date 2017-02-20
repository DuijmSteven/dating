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
                        @foreach($newConversations as $conversationId => $conversation)
                            <?php
                                if ($conversation['user_a']['role_id'] === 3) {
                                    $userA = $conversation['user_a'];

                                    $conversation['user_a'] = $conversation['user_b'];
                                    $conversation['user_b'] = $userA;
                                }
                            ?>
                            <div class="col-xs-12 col-sm-4 col-md-3">
                                <!-- Widget: user widget style 1 -->
                                <div class="box box-widget widget-user-2 default-border">
                                    <!-- Add the bg color to the header using any of the bg-* classes -->
                                    <div class="convo-tile {!! \UserConstants::selectableField('role')[$conversation['user_a']['role_id']] !!}">
                                        <div class="convo-tile_text">
                                            <div class="username">{!! $conversation['user_a']['username'] !!} (ID: {!! $conversation['user_a']['id'] !!})</div>
                                        </div>
                                        <!-- /.widget-user-image -->
                                    </div>
                                    <div class="convo-tile {!! \UserConstants::selectableField('role')[$conversation['user_b']['role_id']] !!}">
                                        <div class="convo-tile_text">
                                            <div class="username">{!! $conversation['user_b']['username'] !!} (ID: {!! $conversation['user_b']['id'] !!})</div>
                                        </div>
                                        <!-- /.widget-user-image -->
                                    </div>
                                    <div class="box-footer no-padding">
                                        <div class="text-summary text-center">
                                            @if($conversation['last_message']['type'] === 'flirt')
                                                <i class="fa fa-heart" style="color:red"></i>
                                            @else
                                                @if($conversation['last_message']['has_attachment'])
                                                    <i class="fa fa-fw fa-paperclip"></i>
                                                @endif
                                                @if($conversation['last_message']['body'])
                                                    <em>"{!! $conversation['last_message']['body'] !!}"</em>
                                                @endif
                                            @endif
                                        </div>
                                    </div>
                                    <div>
                                        <a href="conversations/{!! $conversationId !!}" class="btn btn-default btn-flat btn-block">View Conversation</a>
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
