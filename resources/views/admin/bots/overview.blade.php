@extends('admin.layouts.default.layout')



@section('content')

    <div class="row">

        @include('admin.users-search')

        <div class="col-xs-12">
            <div class="pagination__container text-right">
                {!! $bots->render() !!}
            </div>
        </div>

        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title pull-left"> (Total: <strong>{!! $bots->total() !!}</strong>)</h3>

                    @if($authenticatedUser->isAdmin())
                        <a href="{!! route('admin.bots.create.get') !!}" class="btn btn-success pull-right"><i
                                class="fa fa-fw fa-plus"></i>New Bot</a>
                    @elseif($authenticatedUser->isEditor())
                        <a href="{!! route('editors.bots.create.get') !!}" class="btn btn-success pull-right"><i
                                class="fa fa-fw fa-plus"></i>New Bot</a>
                    @endif
                </div>
                <!-- /.box-header -->
                <div class="box-body table-responsive no-padding">
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th>Bot data</th>
                            <th>Stats</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($bots as $bot)
                            <tr>
                                <td>
                                    <a href="{!! route($editBotRoute, ['botId' => $bot->getId()]) !!}">
                                        <img
                                            style="object-fit: cover; width: 70px; height: 70px"
                                            src="{!! \StorageHelper::profileImageUrl($bot, true) !!}"
                                            alt=""
                                        >
                                    </a>

                                    <div class="innerTableWidgetHeading"><strong>Profile data</strong></div>
                                    <div class="innerTableWidgetBody">
                                        <strong>ID</strong>:
                                            <a href="{!! route($editBotRoute, ['botId' => $bot->getId()]) !!}">
                                                {!! $bot->id !!}
                                            </a>
                                        <br>

                                        <strong>Too slutty for ads:</strong> {!! $bot->meta->getTooSluttyForAds() ? 'true' : 'false'!!} <br>

                                        <strong>{!! @trans('user_constants.username') !!}:</strong> {!! $bot->username !!}
                                        <br>
                                        <strong>{!! @trans('user_constants.age') !!}</strong> {!! $carbonNow->diffInYears($bot->meta->dob) !!}
                                        <br>
                                        <strong>Country code:</strong> {!! $bot->meta->country !!} <br>

                                    @foreach(\UserConstants::selectableFields('bot') as $fieldName => $a)
                                            @if(isset($bot->meta->{$fieldName}))
                                                <strong>{!! ucfirst(str_replace('_', ' ', $fieldName)) !!}:
                                                </strong> {!! @trans('user_constants.' . $fieldName . '.' . $bot->meta->{$fieldName}) !!}
                                                <br>
                                            @endif
                                        @endforeach

                                        @foreach(array_merge(\UserConstants::textFields('bot'), \UserConstants::textInputs('bot')) as $fieldName)
                                            @if(isset($bot->meta->{$fieldName}) && $bot->meta->{$fieldName} != '')
                                                <div
                                                    style="max-width: 250px; {!! $fieldName === 'about_me' ? 'white-space: normal' : '' !!}">
                                                    <strong>{!! @trans('user_constants.' . $fieldName) !!}:</strong>

                                                    @if($fieldName === 'about_me')
                                                        {{ substr($bot->meta->{$fieldName}, 0, 40) }}{{ strlen($bot->meta->{$fieldName}) > 41 ? '...' : '' }}
                                                    @else
                                                        {{ $bot->meta->{$fieldName} }}
                                                    @endif
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>

                                    <div class="innerTableWidgetHeading"><strong>Created by operator</strong></div>
                                    <div class="innerTableWidgetBody">
                                        <strong>ID:</strong>
                                            <a href="{!! route('admin.operators.edit.get', ['operatorId' => $bot->createdByOperator->getId()]) !!}">
                                                {{ $bot->createdByOperator->getId() }}
                                            </a>
                                        <br>

                                        <strong>Username:</strong>
                                            <a href="{!! route('admin.operators.edit.get', ['operatorId' => $bot->createdByOperator->getId()]) !!}">
                                                {{ $bot->createdByOperator->getUsername() }}
                                            </a>
                                        <br>
                                    </div>
                                </td>
                                <td class="no-wrap">
                                    <h5 class="statsHeading"><strong>Messages received</strong></h5>
                                    <div class="statsBody">
                                        <strong>All time:</strong> {{ $bot->messaged_count }} <br>
                                        <strong>Last month:</strong> {{ $bot->messaged_last_month_count }} <br>
                                        <strong>This month:</strong> {{ $bot->messaged_this_month_count }} <br>
                                        <strong>Last Week:</strong> {{ $bot->messaged_last_week_count }} <br>
                                        <strong>This week:</strong> {{ $bot->messaged_this_week_count }} <br>
                                        <strong>Yesterday:</strong> {{ $bot->messaged_yesterday_count }} <br>
                                        <strong>Today:</strong> {{ $bot->messaged_today_count }} <br>
                                    </div>

                                    <h5 class="statsHeading"><strong>Messages sent</strong></h5>
                                    <div class="statsBody">
                                        <strong>All time:</strong> {{ $bot->messages_count }} <br>
                                        <strong>Last month:</strong> {{ $bot->messages_last_month_count }} <br>
                                        <strong>This month:</strong> {{ $bot->messages_this_month_count }} <br>
                                        <strong>Last Week:</strong> {{ $bot->messages_last_week_count }} <br>
                                        <strong>This week:</strong> {{ $bot->messages_this_week_count }} <br>
                                        <strong>Yesterday:</strong> {{ $bot->messages_yesterday_count }} <br>
                                        <strong>Today:</strong> {{ $bot->messages_today_count }} <br>
                                    </div>

                                    <h5 class="statsHeading"><strong>Messages received/sent ratio (larger is better)</strong></h5>
                                    <div class="statsBody">
                                        <strong>All time:</strong> {{ $bot->messagedVsMessagesPercentage() }} ({{ $bot->messaged_count }} / {{ $bot->messages_count }}) <br>
                                        <strong>Last month:</strong> {{ $bot->messagedVsMessagesPercentageLastMonth() }} ({{ $bot->messaged_last_month_count }} / {{ $bot->messages_last_month_count }}) <br>
                                        <strong>This month:</strong> {{ $bot->messagedVsMessagesPercentageThisMonth() }} ({{ $bot->messaged_this_month_count }} / {{ $bot->messages_this_month_count }}) <br>
                                        <strong>Last Week:</strong> {{ $bot->messagedVsMessagesPercentageLastWeek() }} ({{ $bot->messaged_last_week_count }} / {{ $bot->messages_last_week_count }}) <br>
                                        <strong>This week:</strong> {{ $bot->messagedVsMessagesPercentageThisWeek() }} ({{ $bot->messaged_this_week_count }} / {{ $bot->messages_this_week_count }}) <br>
                                        <strong>Yesterday:</strong> {{ $bot->messagedVsMessagesPercentageYesterday() }} ({{ $bot->messaged_yesterday_count }} / {{ $bot->messages_yesterday_count }}) <br>
                                        <strong>Today:</strong> {{ $bot->messagedVsMessagesPercentageToday() }} ({{ $bot->messaged_today_count }} / {{ $bot->messages_today_count }}) <br>
                                    </div>

                                    <h5 class="statsHeading"><strong>Views</strong></h5>
                                    <div class="statsBody">
                                        <strong>All time:</strong> {{ $bot->views->count()  }} <br>
                                        <strong>Unique:</strong> {{ $bot->views->unique('viewer_id')->count() }}
                                    </div>
                                </td>

                                <td class="action-buttons">
                                    @if($authenticatedUser->isAdmin() || ($authenticatedUser->isEditor() && !$bot->active))
                                        <a href="{!! route($editBotRoute, ['botId' => $bot->getId()]) !!}"
                                           class="btn btn-default">Edit</a>
                                    @endif

                                    @if($authenticatedUser->isAdmin())
                                        <a href="{!! route('admin.conversations.bot.get', ['botId' => $bot->getId()]) !!}"
                                           class="btn btn-default">Conversations
                                            <b>({{ $bot->conversations_as_user_a_count + $bot->conversations_as_user_b_count }})</b></a>

                                        <a href="{!! route('admin.messages.bot', ['botId' => $bot->getId()]) !!}"
                                           class="btn btn-default">Messages sent/received
                                            <b>({{ $bot->messaged_count + $bot->messages_count }})</b></a>

                                        <a href="{!! route('admin.bot-messages.bot.get', ['botId' => $bot->getId()]) !!}"
                                           class="btn btn-default">Bot messages assigned
                                            <b>({{ $bot->bot_messages_count }})</b></a>

                                        <a href="{!! route('admin.bots.message-with-bot.get', ['botId' =>  $bot->getId(), 'onlyOnlinePeasants' => '0']) !!}"
                                           class="btn btn-default">Message peasant with bot</a>

                                        <a href="{!! route('admin.bots.message-with-bot.get', ['botId' => $bot->getId(), 'onlyOnlinePeasants' => '1']) !!}"
                                           class="btn btn-default">Message online peasant with bot</a>

                                        @if(!$bot->meta->getTooSluttyForAds())
                                            <a href="{!! route('admin.bots.set-too-slutty-for-ads', ['id' =>  $bot->getId(), 'tooSlutty' => '1']) !!}"
                                               class="btn btn-default">Set too slutty for ads</a>
                                        @else
                                            <a href="{!! route('admin.bots.set-too-slutty-for-ads', ['id' =>  $bot->getId(), 'tooSlutty' => '0']) !!}"
                                               class="btn btn-default">Set NOT too slutty for ads</a>
                                        @endif

                                        <form method="POST"
                                              action="{!! route('admin.users.destroy', ['userId' => $bot->id]) !!}">
                                            {!! csrf_field() !!}
                                            {!! method_field('DELETE') !!}
                                            <button type="submit"
                                                    class="btn btn-danger"
                                                    onclick="return confirm('Are you sure you want to delete this bot?')">
                                                Delete
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>

        <div class="col-xs-12">
            <div class="pagination__container text-right">
                {!! $bots->render() !!}
            </div>
        </div>

    </div>

@endsection
