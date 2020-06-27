@extends('admin.layouts.default.layout')


@section('content')

    <div class="row">

        <div class="col-xs-12">
            <div class="pagination__container text-right">
                {!! $publicChatItems->render() !!}
            </div>
        </div>

        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Messages (Total: <strong>{!! $publicChatItems->total() !!}</strong>)</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body table-responsive no-padding">
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Type</th>
                            <th>Sender</th>
                            <th>Operator</th>
                            <th>Body</th>
                            <th>Created at</th>
                            <th>Published at</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($publicChatItems as $publicChatItem)
                            <tr>
                                <td>
                                    {{ $publicChatItem->getId() }}

                                    @if($publicChatItem->deleted_at)
                                        <br><span style="color: red; font-weight: bold">SOFT DELETED</span>
                                    @endif
                                </td>
                                <td>
                                    {{ \App\PublicChatItem::typeDescriptionPerId()[$publicChatItem->getType()] }}
                                </td>
                                <td>
                                    @if($publicChatItem->sender)
                                        <a href="{{ route('admin.peasants.edit.get', ['peasantId' => $publicChatItem->sender->getId()]) }}">
                                            <img style="height: 80px; width: 80px; object-fit: cover"
                                                 src="{{ \App\Helpers\StorageHelper::profileImageUrl($publicChatItem->sender, true) }}"
                                                 alt="Sender profile image"><br>
                                        </a>
                                        <b>ID</b>:
                                        @if(count($publicChatItem->sender->roles) > 0)
                                            @php
                                                if ($publicChatItem->sender->isAdmin()) {
                                                    $userRoleName = 'peasant';
                                                } else {
                                                    $userRoleName = \UserConstants::selectableField('role')[$publicChatItem->sender->roles[0]->getId()];
                                                }

                                                $routeName = 'admin.' . $userRoleName . 's.edit.get';
                                            @endphp
                                            <a href="{{ route($routeName,[$userRoleName . 'Id' => $publicChatItem->sender->getId()]) }}">
                                                {{ $publicChatItem->sender->getId() }}
                                            </a>
                                        @else
                                            {{ $publicChatItem->sender->getId() }}
                                        @endif
                                        <br>
                                        <b>Username</b>: {{ $publicChatItem->sender->username }} <br>
                                        <b>Gender</b>: {{ @trans('user_constants')['gender'][$publicChatItem->sender->meta->gender] }} <br>

                                        @if(count($publicChatItem->sender->roles) > 0)
                                            <b>Role</b>: {{ @trans('user_constants')['role'][$publicChatItem->sender->roles[0]->getId()] }}
                                        @else
                                            <b>The user has no role set</b>
                                        @endif
                                        <br>
                                    @else
                                        <span style="font-weight: bold; color: red">User does not exist</span>
                                    @endif
                                </td>
                                <td>
                                    @if($publicChatItem->operator)
                                        <a href="{{ route('admin.operators.edit.get', ['peasantId' => $publicChatItem->operator->getId()]) }}">
                                            <img style="height: 80px; width: 80px; object-fit: cover"
                                                 src="{{ \App\Helpers\StorageHelper::profileImageUrl($publicChatItem->operator, true) }}"
                                                 alt="Sender profile image"><br>
                                        </a>
                                        <b>ID</b>:
                                            <a href="{{ route('admin.operators.edit.get',[$userRoleName . 'Id' => $publicChatItem->operator->getId()]) }}">
                                                {{ $publicChatItem->operator->getId() }}
                                            </a>
                                        <br>
                                        <b>Username</b>: {{ $publicChatItem->sender->username }} <br>
                                        <br>
                                    @endif
                                </td>
                                <td>
                                    <div style="max-width: 240px; white-space: normal">
                                        {!! $publicChatItem->getBody() !!}
                                    </div>
                                </td>
                                <td>{{ $publicChatItem->getCreatedAt()->format('d-m-Y H:i:s') }}</td>
                                <td>{{ $publicChatItem->getPublishedAt()->format('d-m-Y H:i:s') }}</td>
                                <td class="action-buttons">
                                    <form method="POST"
                                          action="{{ route(    'admin.public-chat-items.destroy', ['chatItemId' => $publicChatItem->getId()]) }}">
                                        {!! csrf_field() !!}
                                        {!! method_field('DELETE') !!}
                                        <button type="submit"
                                                class="btn btn-danger"
                                                onclick="return confirm('Are you sure you want to delete this public chat item?')">
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
                {!! $publicChatItems->render() !!}
            </div>
        </div>

    </div>

@endsection
