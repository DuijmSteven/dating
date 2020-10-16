@extends('admin.layouts.default.layout')


@section('content')

    <div class="row">

        <div class="col-xs-12">
            <div class="pagination__container text-right">
                {!! $conversations->render() !!}
            </div>
        </div>

        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Conversations (Total: <strong>{!! $conversations->total() !!}</strong>)</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body table-responsive no-padding">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>User A</th>
                                <th>User B</th>
                                <th>Conversation data</th>
                                <th>Created at</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($conversations as $conversation)
                                @php
                                    $userA = $conversation->userA;
                                    $userB = $conversation->userB;
                                @endphp
                                <tr>
                                    <td>
                                        {!! $conversation->id !!}
                                        @if($conversation->deleted_at)
                                            <br>
                                            <span style="color: red; font-weight: bold">DELETED</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($userA)
                                            <a href="{{ route('operator-platform.conversations.show', ['conversationId' => $conversation->id]) }}">
                                                <img width="80"
                                                     src="{{ \App\Helpers\StorageHelper::profileImageUrl($userA) }}"
                                                     alt="User A profile image"><br>
                                            </a>
                                            <b>ID</b>:
                                                @php
                                                    if ($userA->isAdmin()) {
                                                        $userRoleName = 'peasant';
                                                    } else {
                                                        $userRoleName = \UserConstants::selectableField('role')[$userA->roles[0]->id];
                                                    }

                                                    $routeName = 'admin.' . $userRoleName . 's.edit.get';
                                                @endphp
                                                <a href="{{ route($routeName,[$userRoleName . 'Id' => $userA->id]) }}">
                                                    {{ $userA->id }}
                                                </a> <br>
                                            <b>Username</b>: {{ $userA->username }} <br>
                                            <b>Gender</b>: {{ @trans('user_constants')['gender'][$userA->meta->gender] }} <br>
                                            <b>Looking for gender</b>: {{ @trans('user_constants')['looking_for_gender'][$userA->meta->looking_for_gender] }} <br>
                                            <b>Role</b>: {{ @trans('user_constants')['role'][$userA->roles[0]->id] }} <br>
                                        @else
                                            <span style="font-weight: bold; color: red">User does not exist</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($userB)
                                            <a href="{{ route('operator-platform.conversations.show', ['conversationId' => $conversation->id]) }}">
                                                <img width="80"
                                                     src="{{ \App\Helpers\StorageHelper::profileImageUrl($userB) }}"
                                                     alt="User B profile image"><br>
                                            </a>
                                            <b>ID</b>:
                                                @php
                                                  if ($userB->isAdmin()) {
                                                       $userRoleName = 'peasant';
                                                   } else {
                                                       $userRoleName = \UserConstants::selectableField('role')[$userB->roles[0]->id];
                                                   }
                                                   $userRoleName = \UserConstants::selectableField('role')[$userB->roles[0]->id];
                                                   $routeName = 'admin.' . $userRoleName . 's.edit.get';
                                                @endphp
                                                <a href="{{ route($routeName,[$userRoleName . 'Id' => $userB->id]) }}">
                                                    {{ $userB->id }}
                                                </a> <br>
                                            <b>Username</b>: {{ $userB->username }} <br>
                                            <b>Gender</b>: {{ @trans('user_constants')['gender'][$userB->meta->gender] }}<br>
                                            <b>Looking for gender</b>: {{ @trans('user_constants')['looking_for_gender'][$userB->meta->looking_for_gender] }} <br>
                                            <b>Role</b>: {{ @trans('user_constants')['role'][$userB->roles[0]->id] }}<br>
                                        @else
                                            <span style="font-weight: bold; color: red">User does not exist</span>
                                        @endif
                                    </td>
                                    <td>
                                        <b>Replyable</b>: {{ $conversation->getReplyableAt() ? $conversation->getReplyableAt()->tz('Europe/Amsterdam') . ' (' . $conversation->getReplyableAt()->tz('Europe/Amsterdam')->diffForHumans() . ')' : 'No' }} <br>
                                    </td>
                                    <td>{{ $conversation->getCreatedAt()->format('d-m-Y H:i:s') }}</td>
                                    <td class="action-buttons">
                                        @if($userA && $userB)
                                            <a href="{!! route('operator-platform.conversations.show', ['conversationId' => $conversation->getId()]) !!}" class="btn btn-default">View (<b>{{ $conversation->messages_count }}</b> messages)</a>
                                        @endif

                                        @if($conversation->getReplyableAt())
                                            <a href="{!! route('admin.conversations.set-unreplyable', ['conversationId' => $conversation->getId()]) !!}" class="btn btn-default">Make unreplyable</a>
                                        @endif

                                        <form method="POST" action="{{ route('admin.conversations.destroy', ['conversationId' => $conversation->getId()]) }}">
                                            {!! csrf_field() !!}
                                            {!! method_field('DELETE') !!}
                                            <button type="submit"
                                                    class="btn btn-danger"
                                                    onclick="return confirm('Are you sure you want to delete this conversation?')">
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
                {!! $conversations->render() !!}
            </div>
        </div>

    </div>

@endsection
