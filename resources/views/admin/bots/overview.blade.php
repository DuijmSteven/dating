
@extends('admin.layouts.default.layout')



@section('content')

    <div class="row">

        <div class="col-xs-12">
            <div class="pagination__container text-right">
                {!! $bots->render() !!}
            </div>
        </div>

        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title pull-left"> (Total: <strong>{!! $bots->total() !!}</strong>)</h3>
                    <a href="{!! route('admin.bots.create.get') !!}" class="btn btn-success pull-right"><i class="fa fa-fw fa-plus"></i>New Bot</a>
                </div>
                <!-- /.box-header -->
                <div class="box-body table-responsive no-padding">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Profile image</th>
                                <th>Stats</th>
                                <th>Bot data</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($bots as $bot)
                                <tr>
                                    <td>{!! $bot->id !!}</td>
                                    <td>
                                        <a href="{!! \StorageHelper::profileImageUrl($bot) !!}">
                                            <img width="120" src="{!! \StorageHelper::profileImageUrl($bot, true) !!}" alt="">
                                        </a>
                                    </td>
                                    <td class="no-wrap">
                                        <h5 class="statsHeading"><strong>Messages</strong></h5>
                                        <div class="statsBody">
                                            <strong>All time:</strong> {{ $bot->messaged_count }} <br>
                                            <strong>Last month:</strong> {{ $bot->messaged_last_month_count }} <br>
                                            <strong>This month:</strong> {{ $bot->messaged_this_month_count }} <br>
                                            <strong>Last Week:</strong> {{ $bot->messaged_last_week_count }} <br>
                                            <strong>This week:</strong> {{ $bot->messaged_this_week_count }} <br>
                                            <strong>Yesterday:</strong> {{ $bot->messaged_yesterday_count }} <br>
                                            <strong>Today:</strong> {{ $bot->messaged_today_count }} <br>
                                        </div>

                                        <h5 class="statsHeading"><strong>Views</strong></h5>
                                        <div class="statsBody">
                                            <strong>All time:</strong> {{ $bot->views->count()  }} <br>
                                            <strong>Unique:</strong> {{ $bot->uniqueViews()->get()->count() }}
                                        </div>
                                    </td>

                                    <td>
                                        <strong>{!! @trans('user_constants.username') !!}:</strong> {!! $bot->username !!} <br>
                                        <strong>{!! @trans('user_constants.age') !!}</strong> {!! $carbonNow->diffInYears($bot->meta->dob) !!} <br>

                                        @foreach(\UserConstants::selectableFields('bot') as $fieldName => $a)
                                            @if(isset($bot->meta->{$fieldName}))
                                                <strong>{!! ucfirst(str_replace('_', ' ', $fieldName)) !!}:
                                                </strong> {!! @trans('user_constants.' . $fieldName . '.' . $bot->meta->{$fieldName}) !!} <br>
                                            @endif
                                        @endforeach

                                        @foreach(array_merge(\UserConstants::textFields('bot'), \UserConstants::textInputs('bot')) as $fieldName)
                                            @if(isset($bot->meta->{$fieldName}) && $bot->meta->{$fieldName} != '')
                                                <div style="max-width: 250px; {!! $fieldName === 'about_me' ? 'white-space: normal' : '' !!}">
                                                    <strong>{!! @trans('user_constants.' . $fieldName) !!}:</strong>

                                                    @if($fieldName === 'about_me')
                                                        {{ substr($bot->meta->{$fieldName}, 0, 40) }}{{ strlen($bot->meta->{$fieldName}) > 41 ? '...' : '' }}
                                                    @else
                                                        {{ $bot->meta->{$fieldName} }}
                                                    @endif
                                                </div>
                                            @endif
                                        @endforeach
                                    </td>
                                    <td class="action-buttons">
                                        @if($authenticatedUser->isAdmin() || $authenticatedUser->isEditor() && in_array($bot->getId(), $authenticatedUser->createdBotIds) && !$bot->active)
                                            <a href="{!! route('admin.bots.edit.get', [$bot->getId()]) !!}" class="btn btn-default">Edit</a>
                                        @endif

                                        @if($authenticatedUser->isAdmin())
                                            <a href="{!! route('admin.conversations.bot.get', ['botId' => $bot->getId()]) !!}" class="btn btn-default">Conversations</a>
                                            <a href="{!! route('admin.bots.message-with-bot.get', ['botId' =>  $bot->getId(), 'onlyOnlinePeasants' => '0']) !!}" class="btn btn-default">Message peasant with bot</a>
                                            <a href="{!! route('admin.bots.message-with-bot.get', ['botId' => $bot->getId(), 'onlyOnlinePeasants' => '1']) !!}" class="btn btn-default">Message online peasant with bot</a>

                                            <form method="POST" action="{!! route('admin.users.destroy', ['userId' => $bot->id]) !!}">
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
