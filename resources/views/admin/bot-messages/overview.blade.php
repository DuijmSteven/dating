@extends('admin.layouts.default.layout')


@section('content')

    <div class="row">

        <div class="col-xs-12">
            <div class="pagination__container text-right">
                {!! $botMessages->render() !!}
            </div>
        </div>

        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <div class="pull-left">
                        <h3 class="box-title">Bot Messages (Total: <strong>{!! $botMessages->total() !!}</strong>)</h3>
                    </div>
                    <a class="pull-right btn btn-success" href="{{ route('admin.bot-messages.create') }}">Create bot message</a>
                </div>
                <!-- /.box-header -->
                <div class="box-body table-responsive no-padding">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Bot</th>
                                <th>Body</th>
                                <th>Status</th>
                                <th class="no-wrap">Created at</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($botMessages as $botMessage)
                                <tr>
                                    <td>{{ $botMessage->id }}</td>
                                    <td>
                                        @if($botMessage->bot)
                                            <a href="{!! \StorageHelper::profileImageUrl($botMessage->bot) !!}">
                                                <img
                                                    style="object-fit: cover; width: 70px; height: 70px"
                                                    src="{!! \StorageHelper::profileImageUrl($botMessage->bot, true) !!}"
                                                    alt=""
                                                >
                                            </a> <br>
                                            <strong>ID</strong>: <a href="{{ route('admin.bots.edit.get',  ['botId' => $botMessage->bot->getId()]) }}">{{ $botMessage->bot->getId() }}</a> <br>
                                            <strong>Username</strong>: <a href="{{ route('admin.bots.edit.get',  ['botId' => $botMessage->bot->getId()]) }}">{{ $botMessage->bot->getUsername() }}</a> <br>
                                        @else
                                            No bot is assigned
                                        @endif
                                    </td>
                                    <td>{{ $botMessage->body }}</td>
                                    <td>{{ $botMessage->status ? 'Active' : 'Inactive' }}</td>
                                    <td class="no-wrap">{{ $botMessage->created_at->format('d-m-Y H:i:s') }}</td>
                                    <td class="action-buttons">
                                        <a href="{{ route('admin.bot-messages.edit', ['botMessageId' => $botMessage->id]) }}"
                                           class="btn btn-default">
                                            Edit
                                        </a>
                                        <form method="POST" action="{{ route('admin.bot-messages.destroy', ['botMessageId' => $botMessage->id]) }}">
                                            {!! csrf_field() !!}
                                            {!! method_field('DELETE') !!}
                                            <button type="submit"
                                                    class="btn btn-danger"
                                                    onclick="return confirm('Are you sure you want to delete this bot message?')">
                                                Delete
                                            </button>
                                        </form>
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
                {!! $botMessages->render() !!}
            </div>
        </div>

    </div>

@endsection
