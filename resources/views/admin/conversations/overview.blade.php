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
                                <th>Created at</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($conversations as $conversation)
                                @php
                                    $userA = $conversation['user_a'];
                                    $userB = $conversation['user_b'];
                                @endphp
                                <tr>
                                    <td>{!! $conversation['id'] !!}</td>
                                    <td>
                                        <a href="{{ route('operator-platform.conversations.show', ['id' => $conversation['id']]) }}">
                                            <img width="80"
                                                 src="{{ $userA['profile_image_url'] }}"
                                                 alt="User A profile image"><br>
                                        </a>
                                        <b>ID</b>:
                                            @php
                                                $userRoleName = \UserConstants::selectableField('role')[$userA['role']];
                                                $routeName = 'admin.' . $userRoleName . 's.edit.get';
                                            @endphp
                                            <a href="{{ route($routeName,['id' => $userA['id']]) }}">
                                                {{ $userA['id'] }}
                                            </a> <br>
                                        <b>Username</b>: {{ $userA['username'] }} <br>
                                        <b>Gender</b>: {{ @trans('user_constants')['gender'][$userA['meta']['gender']] }} <br>
                                        <b>Role</b>: {{ @trans('user_constants')['role'][$userA['role']] }} <br>
                                    </td>
                                    <td>
                                        <a href="{{ route('operator-platform.conversations.show', ['id' => $conversation['id']]) }}">
                                            <img width="80"
                                                 src="{{ $userB['profile_image_url'] }}"
                                                 alt="User B profile image"><br>
                                        </a>
                                        <b>ID</b>:
                                            @php
                                                $userRoleName = \UserConstants::selectableField('role')[$userB['role']];
                                                $routeName = 'admin.' . $userRoleName . 's.edit.get';
                                            @endphp
                                            <a href="{{ route($routeName,['id' => $userB['id']]) }}">
                                                {{ $userB['id'] }}
                                            </a> <br>
                                        <b>Username</b>: {{ $userB['username'] }} <br>
                                        <b>Gender</b>: {{ @trans('user_constants')['gender'][$userB['meta']['gender']] }}<br>
                                        <b>Role</b>: {{ @trans('user_constants')['role'][$userB['role']] }}<br>
                                    </td>
                                    <td>{{ $conversation['created_at']->format('d-m-Y H:i:s') }}</td>
                                    <td class="action-buttons">
                                        <a href="{!! route('operator-platform.conversations.show', [$conversation['id']]) !!}" class="btn btn-default">View</a>

                                        <form method="POST" action="{{ route('admin.conversations.destroy', ['conversationId' => $conversation['id']]) }}">
                                            {!! csrf_field() !!}
                                            {!! method_field('DELETE') !!}
                                            <button type="submit"
                                                    class="btn btn-danger"
                                                    onclick="confirm('Are you sure you want to delete this bot?')">
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
