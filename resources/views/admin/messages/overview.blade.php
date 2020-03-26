@extends('admin.layouts.default.layout')


@section('content')

    <div class="row">

        <div class="col-xs-12">
            <div class="pagination__container text-right">
                {!! $messages->render() !!}
            </div>
        </div>

        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Messages (Total: <strong>{!! $messages->total() !!}</strong>)</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body table-responsive no-padding">
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Conversation ID</th>
                            <th>Sender</th>
                            <th>Recipient</th>
                            <th>Body</th>
                            <th>Attachment</th>
                            <th>Created at</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($messages as $message)
                            <tr>
                                <td>
                                    {{ $message->getId() }}

                                    @if($message->deleted_at)
                                        <br><span style="color: red; font-weight: bold">DELETED</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('operator-platform.conversations.show', ['conversationId' => $message->conversation->getId()]) }}">{{ $message->conversation->getId() }}</a>

                                    @if($message->conversation->deleted_at)
                                        <br><span style="color: red; font-weight: bold">DELETED</span>
                                    @endif
                                </td>
                                <td>
                                    @if($message->sender)
                                        <a href="{{ route('operator-platform.conversations.show', ['id' => $message->sender->getId()]) }}">
                                            <img style="height: 80px; width: 80px; object-fit: cover"
                                                 src="{{ \App\Helpers\StorageHelper::profileImageUrl($message->sender) }}"
                                                 alt="Sender profile image"><br>
                                        </a>
                                        <b>ID</b>:
                                        @if(count($message->sender->roles) > 0)
                                            @php
                                                if ($message->sender->isAdmin()) {
                                                    $userRoleName = 'peasant';
                                                } else {
                                                    $userRoleName = \UserConstants::selectableField('role')[$message->sender->roles[0]->getId()];
                                                }

                                                $routeName = 'admin.' . $userRoleName . 's.edit.get';
                                            @endphp
                                            <a href="{{ route($routeName,['id' => $message->sender->getId()]) }}">
                                                {{ $message->sender->getId() }}
                                            </a>
                                        @else
                                            {{ $message->sender->getId() }}
                                        @endif
                                        <br>
                                        <b>Username</b>: {{ $message->sender->username }} <br>
                                        <b>Gender</b>: {{ @trans('user_constants')['gender'][$message->sender->meta->gender] }} <br>

                                        @if(count($message->sender->roles) > 0)
                                            <b>Role</b>: {{ @trans('user_constants')['role'][$message->sender->roles[0]->getId()] }}
                                        @else
                                            <b>The user has no role set</b>
                                        @endif
                                        <br>
                                    @else
                                        <span style="font-weight: bold; color: red">User does not exist</span>
                                    @endif
                                </td>
                                <td>
                                    @if($message->recipient)
                                        <a href="{{ route('operator-platform.conversations.show', ['id' => $message->recipient->getId()]) }}">
                                            <img style="height: 80px; width: 80px; object-fit: cover"
                                                 src="{{ \App\Helpers\StorageHelper::profileImageUrl($message->recipient) }}"
                                                 alt="Recipient profile image"><br>
                                        </a>
                                        <b>ID</b>:
                                        @if(count($message->recipient->roles) > 0)
                                            @php
                                                if ($message->recipient->isAdmin()) {
                                                    $userRoleName = 'peasant';
                                                } else {
                                                    $userRoleName = \UserConstants::selectableField('role')[$message->recipient->roles[0]->getId()];
                                                }

                                                $routeName = 'admin.' . $userRoleName . 's.edit.get';
                                            @endphp
                                            <a href="{{ route($routeName,['id' => $message->recipient->getId()]) }}">
                                                {{ $message->recipient->getId() }}
                                            </a>
                                        @else
                                            {{ $message->recipient->getId() }}
                                        @endif
                                        <br>
                                        <b>Username</b>: {{ $message->recipient->username }} <br>
                                        <b>Gender</b>: {{ @trans('user_constants')['gender'][$message->recipient->meta->gender] }}
                                        <br>

                                        @if(count($message->recipient->roles) > 0)
                                            <b>Role</b>: {{ @trans('user_constants')['role'][$message->recipient->roles[0]->getId()] }}
                                        @else
                                            <b>The user has no role set</b>
                                        @endif
                                        <br>
                                    @else
                                        <span style="font-weight: bold; color: red">User does not exist</span>
                                    @endif
                                </td>
                                <td>
                                    <div style="max-width: 240px; white-space: normal">
                                        {{ $message->getBody() }}
                                    </div>
                                </td>
                                <td>
                                    @if($message->has_attachment)
                                        <div>
                                            <img height="100" src="{!! \StorageHelper::messageAttachmentUrl(
                                                    $message->conversation->getId(),
                                                    $message->attachment->filename
                                                ) !!}"
                                                 alt="">
                                        </div>
                                    @endif
                                </td>
                                <td>{{ $message->created_at->format('d-m-Y H:i:s') }}</td>
                                <td class="action-buttons">
                                    <form method="POST"
                                          action="{{ route('admin.messages.destroy', ['messageId' => $message->getId()]) }}">
                                        {!! csrf_field() !!}
                                        {!! method_field('DELETE') !!}
                                        <button type="submit"
                                                class="btn btn-danger"
                                                onclick="return confirm('Are you sure you want to delete this message?')">
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
                {!! $messages->render() !!}
            </div>
        </div>

    </div>

@endsection
