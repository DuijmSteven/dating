@extends('admin.layouts.default.layout')


@section('content')

    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Edit Bot Message</h3>
        </div>
        <!-- /.box-header -->
        <!-- form start -->
        <form role="form" method="POST" action="{!! route('admin.bot-messages.update', ['botMessageId' => $botMessage->id]) !!}" enctype="multipart/form-data">
            {!! csrf_field() !!}
            {!! method_field('PUT') !!}
            <div class="box-body">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="status">Status</label>
                            <select name="status"
                                    id="status"
                                    class="form-control"
                                    required
                            >
                                <option value="1" {!! ($botMessage->getStatus() === 1) ? 'selected' : '' !!}>Active</option>
                                <option value="0" {!! ($botMessage->getStatus() === 0) ? 'selected' : '' !!}>Inactive</option>
                            </select>
                            @if ($errors->has('status'))
                                {!! $errors->first('status', '<small class="form-error">:message</small>') !!}
                            @endif
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="usage_type">Usage type</label>
                            <select name="usage_type"
                                    id="usage_type"
                                    class="form-control"
                                    required
                            >
                                <option value="{{ \App\BotMessage::USAGE_TYPE_NORMAL_CHAT }}" {!! ($botMessage->getUsageType() === \App\BotMessage::USAGE_TYPE_NORMAL_CHAT) ? 'selected' : '' !!}>Normal chat</option>
                                <option value="{{ \App\BotMessage::USAGE_TYPE_PUBLIC_CHAT }}" {!! ($botMessage->getUsageType() === \App\BotMessage::USAGE_TYPE_PUBLIC_CHAT) ? 'selected' : '' !!}>Public chat</option>
                            </select>
                            @if ($errors->has('usage_type'))
                                {!! $errors->first('usage_type', '<small class="form-error">:message</small>') !!}
                            @endif
                        </div>
                    </div>
                    <div class="col-xs-12">
                        <div class="form-group">
                            <label for="body">Body</label>
                            <textarea class="form-control"
                                      id="body"
                                      name="body"
                                      required
                                      rows="20"
                            >{!! $botMessage->getBody() !!}</textarea>
                            @if ($errors->has('body'))
                                {!! $errors->first('body', '<small class="form-error">:message</small>') !!}
                            @endif
                        </div>
                    </div>
                    <div class="col-xs-12">
                        <div class="form-group">
                            <label for="body">Bot</label>

                            <div class="botMessageBots">
                                @foreach($bots as $bot)
                                    <div class="botMessageBot">
                                        <img class="botMessageBotImage"
                                             src="{!! \StorageHelper::profileImageUrl($bot, true) !!}"
                                             alt="bot image"
                                        >
                                        (ID :<a href="{{ route('admin.bots.edit.get', ['botId' => $bot->getId()]) }}">{!! $bot->getId() !!}</a>)
                                        <span class="js-fillBotData botUsername" style="margin-left: 10px; margin-right: 10px">
                                            {!! $bot->username !!}
                                        </span>

                                        <a
                                            href="{{ route('admin.bot-messages.bot.get', ['botId' => $bot->getId()]) }}"
                                            style="margin-left: 15px; margin-right: 15px"
                                        >
                                            <strong>assigned messages</strong>: {{ $bot->bot_messages_count }}
                                        </a>

                                        <label style="cursor: pointer;">
                                            <input
                                                type="radio"
                                                name="bot_id"
                                                value="{{ $bot->getId() }}"
                                                {{ ($botMessage->getBotId() === $bot->getId()) ? 'checked' : '' }}
                                            >
                                        </label>
                                    </div>
                                @endforeach
                            </div>

                            @if ($errors->has('bot_id'))
                                {!! $errors->first('bot_id', '<small class="form-error">:message</small>') !!}
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.box-body -->
            <div class="box-footer text-right">
                <button type="submit" class="btn btn-primary">Update Bot Message</button>
            </div>
        </form>
    </div>

@endsection
