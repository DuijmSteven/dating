
@extends('admin.layouts.default.layout')



@section('content')

    <div class="row">

        @include('admin.users-search')

        <div class="col-xs-12">
            <div class="pagination__container text-right">
                {!! $editors->render() !!}
            </div>
        </div>

        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Editors (Total: <strong>{!! $editors->total() !!}</strong>)</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body table-responsive no-padding">
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th>Editor Data</th>
                            <th>Stats</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($editors as $editor)
                            <tr>
                                <td>
                                    <a href="{!! route('admin.editors.edit.get', ['editorId' => $editor->id]) !!}">
                                        <img
                                            style="object-fit: cover; width: 70px; height: 70px"
                                            src="{!! \StorageHelper::profileImageUrl($editor, true) !!}"
                                            alt=""
                                        >
                                    </a>

                                    <div class="innerTableWidgetHeading"><strong>Editor Data</strong></div>
                                    <div class="innerTableWidgetBody">
                                        <strong>ID</strong>:
                                            <a href="{!! route('admin.editors.edit.get', ['editorId' => $editor->id]) !!}">
                                                {!! $editor->getId() !!}
                                            </a>
                                        <br>

                                        <strong>{!! trans(config('app.directory_name') . '/user_constants.email') !!}:</strong> {!! $editor->email !!} <br>
                                        <strong>{!! trans(config('app.directory_name') . '/user_constants.username') !!}:</strong> {!! $editor->username !!} <br>
                                        <strong>{!! trans(config('app.directory_name') . '/user_constants.age') !!}</strong> {!! $carbonNow->diffInYears($editor->meta->dob) !!} <br>
                                    </div>
                                </td>
                                <td>
                                    <h5 class="statsHeading"><strong>Created bots</strong></h5>
                                    <div class="statsBody">
                                        <strong>All time:</strong> {!! $editor->created_bots_count !!} <br>
                                        <strong>Last month:</strong> {!! $editor->created_bots_last_month_count !!} <br>
                                        <strong>This month:</strong> {!! $editor->created_bots_this_month_count !!} <br>
                                        <strong>Last week:</strong> {!! $editor->created_bots_last_week_count !!} <br>
                                        <strong>This week:</strong> {!! $editor->created_bots_this_week_count !!} <br>
                                        <strong>Yesterday:</strong> {!! $editor->created_bots_yesterday_count !!} <br>
                                        <strong>Today:</strong> {!! $editor->created_bots_today_count !!} <br>

                                    </div>
                                </td>
                                <td class="action-buttons">
                                    <a href="{!! route('admin.editors.edit.get', ['editorId' => $editor->id]) !!}" class="btn btn-default">Edit</a>
                                    <a href="{!! route('admin.editors.created-bots.overview', [$editor->getId()]) !!}" class="btn btn-default">Created bots <b>({{ $editor->created_bots_count }})</b></a>

                                    <form method="POST" action="{!! route('admin.users.destroy', ['userId' => $editor->getId()]) !!}">
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
                {!! $editors->render() !!}
            </div>
        </div>

    </div>

@endsection
