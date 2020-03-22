
@extends('admin.layouts.default.layout')



@section('content')

    <div class="row">

{{--        <div class="col-xs-12">--}}
{{--            <div class="pagination__container text-right">--}}
{{--                {!! $bots->render() !!}--}}
{{--            </div>--}}
{{--        </div>--}}

        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Bots (Total: <strong>{!! $bots->count() !!}</strong>)</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body table-responsive no-padding">
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Profile image</th>
                            <th>Views</th>
                            <th>User data</th>
                            <th>Meta data</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($bots as $bot)
                            <tr>
                                <td>{!! $bot->id !!}</td>
                                <td>
                                    <a href="">
                                        <img width="120" src="{!! \StorageHelper::profileImageUrl($bot, true) !!}"
                                             alt="">
                                    </a>
                                </td>
                                <td>
                                    {{ $bot->views->count() }}
                                </td>
                                <td>
                                    <strong>{!! @trans('user_constants.username') !!}:</strong> {!! $bot->username !!} <br>
                                    <strong>{!! @trans('user_constants.age') !!}</strong> {!! $carbonNow->diffInYears($bot->meta->dob) !!} <br>
                                </td>
                                <td>
                                    @foreach(\UserConstants::selectableFields('peasant') as $fieldName => $a)
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
                                    <a href="{!! route('admin.peasants.edit.get', [$bot->id]) !!}" class="btn btn-default">Edit</a>

                                    <a href="{!! route('admin.bots.message-with-bot.get', ['botId' => $bot->id, 'onlyOnlinePeasants' => '0']) !!}" class="btn btn-default">Message peasant with bot</a>
                                    <a href="{!! route('admin.bots.message-with-bot.get', ['botId' => $bot->id, 'onlyOnlinePeasants' => '1']) !!}" class="btn btn-default">Message online peasant with bot</a>

                                    <form method="POST" action="{!! route('admin.users.destroy', ['userId' => $bot->id]) !!}">
                                        {!! csrf_field() !!}
                                        {!! method_field('DELETE') !!}
                                        <button type="submit"
                                                class="btn btn-danger"
                                                onclick="return confirm('Are you sure you want to delete this peasant?')">
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

{{--        <div class="col-xs-12">--}}
{{--            <div class="pagination__container text-right">--}}
{{--                {!! $bots->render() !!}--}}
{{--            </div>--}}
{{--        </div>--}}

    </div>

@endsection
