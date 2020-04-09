@extends('admin.layouts.default.layout')


@section('content')



    <div class="panel-group operatorDashboard" id="accordion" role="tablist" aria-multiselectable="true">

        <?php $counter = 0 ?>

        @foreach([
            'new' => 'newConversations',
            'unreplied' => 'unrepliedConversations',
            'stopped' => 'stoppedConversations'
        ] as $typeName => $conversationType)

            <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="headingOne">
                <h4 class="panel-title">
                    <a role="button"
                       data-toggle="collapse"
                       data-parent="#accordion"
                       href="#{!! $conversationType !!}"
                       aria-expanded="false"
                       aria-controls="collapseOne"
                       class="collapsed counterLabelContainer">
                        {!! ucfirst($typeName) !!} <span class="label counterLabel">{!! count(${$conversationType}) !!}</span>
                    </a>
                </h4>
            </div>
            <div id="{{ $conversationType }}" class="panel-collapse {{ $conversationType !== 'unrepliedConversations' ? 'collapse' : '' }}" role="tabpanel" aria-labelledby="headingOne">
                <div class="panel-body">
                    <div class="row">
                        @foreach(${$conversationType} as $conversation)
                            <?php
                                if ($conversation->userA->isBot()) {
                                    $userA = $conversation->userA;

                                    $conversation->userA = $conversation->userB;
                                    $conversation->userB = $userA;
                                }
                            ?>
                            <div class="col-xs-12 col-sm-4 col-md-3">
                                <!-- Widget: user widget style 1 -->
                                <div class="box box-widget widget-user-2 default-border">
                                    <div class="convo-tile conversationId">
                                        ID: {{ $conversation->getId() }}
                                    </div>
                                    <!-- Add the bg color to the header using any of the bg-* classes -->
                                    <div class="convo-tile {!! \UserConstants::selectableField('role')[$conversation->userA->roles[0]->id] !!}">
                                        <div class="convo-tile_text">
                                            <div class="username">
                                                <img class="profileImage" src="{!! \StorageHelper::profileImageUrl($conversation->userA, true) !!}" />
                                                <a href="{!! route('admin.' . \UserConstants::selectableField('role')[$conversation->userA->roles[0]->id] . 's.edit.get', ['id' => $conversation->userA->getId()]) !!}">
                                                    {!! $conversation->userA->username !!} (ID: {!! $conversation->userA->getId() !!})
                                                </a>
                                            </div>
                                        </div>
                                        <!-- /.widget-user-image -->
                                    </div>
                                    <div class="convo-tile {!! \UserConstants::selectableField('role')[$conversation->userB->roles[0]->id] !!}">
                                        <div class="convo-tile_text">
                                            <div class="username">
                                                <img class="profileImage" src="{!! \StorageHelper::profileImageUrl($conversation->userB, true) !!}" />
                                                <a href="{!! route('admin.' . \UserConstants::selectableField('role')[$conversation->userB->roles[0]->id] . 's.edit.get', ['id' => $conversation->userB->getId()]) !!}">
                                                    {!! $conversation->userB->username !!} (ID: {!! $conversation->userB->getId() !!})
                                                </a>
                                            </div>
                                        </div>
                                        <!-- /.widget-user-image -->
                                    </div>
                                    <div class="box-footer no-padding">
                                        <div class="text-summary text-center">
                                            @if($conversation->messages[0]->attachment)
                                                <i class="fa fa-fw fa-paperclip"></i>
                                            @endif
                                            @if($conversation->messages[0]->body)
                                                <em>"{!! $conversation->messages[0]->body !!}"</em>
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

        <?php $counter++; ?>
        @endforeach

    </div>



@endsection
