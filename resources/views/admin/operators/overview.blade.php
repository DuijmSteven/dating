
@extends('admin.layouts.default.layout')



@section('content')

    <div class="row">

        @include('admin.users-search')

        <div class="col-xs-12">
            <div class="pagination__container text-right">
                {!! $operators->render() !!}
            </div>
        </div>

        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Operators (Total: <strong>{!! $operators->total() !!}</strong>)</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body table-responsive no-padding">
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th>Operator data</th>
                            <th>Stats</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($operators as $operator)
                            <tr>
                                <td>
                                    <a href="{!! route('admin.operators.edit.get', ['operatorId' => $operator->id]) !!}">
                                        <img
                                            style="object-fit: cover; width: 70px; height: 70px"
                                            src="{!! \StorageHelper::profileImageUrl($operator, true) !!}"
                                            alt=""
                                        >
                                    </a>

                                    <div class="innerTableWidgetHeading"><strong>Operator data</strong></div>
                                    <div class="innerTableWidgetBody">
                                        <strong>ID</strong>:
                                            <a href="{!! route('admin.operators.edit.get', ['operatorId' => $operator->id]) !!}">
                                                {!! $operator->getId() !!}
                                            </a>
                                        <br>

                                        <strong>{!! @trans('user_constants.email') !!}:</strong> {!! $operator->email !!} <br>
                                        <strong>{!! @trans('user_constants.username') !!}:</strong> {!! $operator->username !!} <br>
                                        <strong>{!! @trans('user_constants.age') !!}</strong> {!! $carbonNow->diffInYears($operator->meta->dob) !!} <br>
                                    </div>
                                </td>
                                <td>
                                    <h5 class="statsHeading"><strong>Normal messages</strong></h5>
                                    <div class="statsBody">
                                        <strong>All time:</strong> {!! $operator->operator_messages_count !!} <br>
                                        <strong>Last month:</strong> {!! $operator->operator_messages_last_month_count !!} <br>
                                        <strong>This month:</strong> {!! $operator->operator_messages_this_month_count !!} <br>
                                        <strong>Last week:</strong> {!! $operator->operator_messages_last_week_count !!} <br>
                                        <strong>This week:</strong> {!! $operator->operator_messages_this_week_count !!} <br>
                                        <strong>Yesterday:</strong> {!! $operator->operator_messages_yesterday_count !!} <br>
                                        <strong>Today:</strong> {!! $operator->operator_messages_today_count !!} <br>
                                    </div>

                                    <h5 class="statsHeading"><strong>Stopped conversation messages</strong></h5>
                                    <div class="statsBody">
                                        <strong>All time:</strong> {!! $operator->operator_messages_to_stopped_conversations_count !!} <br>
                                        <strong>Last month:</strong> {!! $operator->operator_messages_to_stopped_conversations_last_month_count !!} <br>
                                        <strong>This month:</strong> {!! $operator->operator_messages_to_stopped_conversations_this_month_count !!} <br>
                                        <strong>Last week:</strong> {!! $operator->operator_messages_to_stopped_conversations_last_week_count !!} <br>
                                        <strong>This week:</strong> {!! $operator->operator_messages_to_stopped_conversations_this_week_count !!} <br>
                                        <strong>Yesterday:</strong> {!! $operator->operator_messages_to_stopped_conversations_yesterday_count !!} <br>
                                        <strong>Today:</strong> {!! $operator->operator_messages_to_stopped_conversations_today_count !!} <br>
                                    </div>
                                </td>
                                <td class="action-buttons">
                                    <a href="{!! route('admin.operators.edit.get', ['operatorId' => $operator->id]) !!}" class="btn btn-default">Edit</a>
                                    <a href="{!! route('admin.conversations.with-operator', ['operatorId' => $operator->getId()]) !!}" class="btn btn-default">Conversations
{{--                                        <b>({{ $operator->conversationsAsOperator()->withTrashed()->count() }})</b>--}}
                                    </a>


                                    <a href="{!! route('admin.operators.messages.overview', ['operatorId' => $operator->getId()]) !!}" class="btn btn-default">Sent messages <b>({{ $operator->operator_messages_count }})</b></a>
                                    <form method="POST" action="{!! route('admin.users.destroy', ['userId' => $operator->getId()]) !!}">
                                        {!! csrf_field() !!}
                                        {!! method_field('DELETE') !!}
                                        <button type="submit"
                                                class="btn btn-danger"
                                                onclick="return confirm('Are you sure you want to delete this operator?')">
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
                {!! $operators->render() !!}
            </div>
        </div>

    </div>

@endsection
