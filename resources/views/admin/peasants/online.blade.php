
@extends('admin.layouts.default.layout')



@section('content')

    <div class="row">

{{--        <div class="col-xs-12">--}}
{{--            <div class="pagination__container text-right">--}}
{{--                {!! $peasants->render() !!}--}}
{{--            </div>--}}
{{--        </div>--}}

        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Peasants (Total: <strong>{!! $peasants->count() !!}</strong>)</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body table-responsive no-padding">
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Profile image</th>
                            <th>Credits && Payments</th>
                            <th>User data</th>
                            <th>Message data</th>
                            <th>Meta data</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($peasants as $peasant)
                            <tr>
                                <td>{!! $peasant->id !!}</td>
                                <td>
                                    <a href="">
                                        <img width="120" src="{!! \StorageHelper::profileImageUrl($peasant, true) !!}"
                                             alt="">
                                    </a>
                                </td>
                                <td class="no-wrap">
                                    <strong>Credits</strong>: {{ $peasant->account->getCredits() }} <br>

                                    @if(count($peasant->completedPayments) > 0)
                                        <strong># of payments</strong>: {{ count($peasant->completedPayments) }} <br>
                                        <strong>Last Payment amount</strong>: &euro;{{ number_format($peasant->completedPayments[0]->amount/ 100, 2) }} <br>
                                        <strong>Last Payment date</strong>: {{ $peasant->completedPayments[0]->created_at->format('d-m-Y H:i:s') }} <br>

                                        <?php
                                        $moneySpent = 0;
                                        foreach ($peasant->completedPayments as $payment) {
                                            $moneySpent += $payment->amount;
                                        }
                                        ?>

                                        <strong>Money spent</strong>: &euro;{{ number_format($moneySpent/ 100, 2) }} <br>
                                    @endif
                                </td>
                                <td class="no-wrap">
                                    <strong>{!! @trans('user_constants.email') !!}:</strong> {!! $peasant->email !!} <br>
                                    <strong>{!! @trans('user_constants.username') !!}:</strong> {!! $peasant->username !!} <br>
                                    <strong>{!! @trans('user_constants.age') !!}</strong> {!! $carbonNow->diffInYears($peasant->meta->dob) !!} <br>
                                    <strong>Created at</strong> {!! $peasant->getCreatedAt() !!} <br>
                                    @if($peasant->getLastOnlineAt())
                                        <strong>Last online at</strong> {!! $peasant->getLastOnlineAt()->tz('Europe/Amsterdam') !!} <br>
                                        <strong>Last online in days</strong> {!! $peasant->getLastOnlineAt()->tz('Europe/Amsterdam')->diffInDays($carbonNow->tz('Europe/Amsterdam')) !!} <br>
                                    @endif
                                </td>
                                <td class="no-wrap">
                                    <strong>All time:</strong> {{ $peasant->messages_count }} <br>
                                    <strong>Last month:</strong> {{ $peasant->messages_last_month_count }} <br>
                                    <strong>This month:</strong> {{ $peasant->messages_this_month_count }} <br>
                                    <strong>Last Week:</strong> {{ $peasant->messages_last_week_count }} <br>
                                    <strong>This week:</strong> {{ $peasant->messages_this_week_count }} <br>
                                    <strong>Yesterday:</strong> {{ $peasant->messages_yesterday_count }} <br>
                                    <strong>Today:</strong> {{ $peasant->messages_today_count }} <br>
                                </td>
                                <td class="no-wrap">
                                    @foreach(\UserConstants::selectableFields('peasant') as $fieldName => $a)
                                        @if(isset($peasant->meta->{$fieldName}))
                                            <strong>{!! ucfirst(str_replace('_', ' ', $fieldName)) !!}:
                                            </strong> {!! @trans('user_constants.' . $fieldName . '.' . $peasant->meta->{$fieldName}) !!} <br>
                                        @endif
                                    @endforeach

                                        @foreach(array_merge(\UserConstants::textFields('peasant'), \UserConstants::textInputs('peasant')) as $fieldName)
                                            @if(isset($peasant->meta->{$fieldName}) && $peasant->meta->{$fieldName} != '')
                                                <div style="max-width: 250px; {!! $fieldName === 'about_me' ? 'white-space: normal' : '' !!}">
                                                    <strong>{!! @trans('user_constants.' . $fieldName) !!}:</strong>

                                                    @if($fieldName === 'about_me')
                                                        {{ substr($peasant->meta->{$fieldName}, 0, 40) }}{{ strlen($peasant->meta->{$fieldName}) > 41 ? '...' : '' }}
                                                    @else
                                                        {{ $peasant->meta->{$fieldName} }}
                                                    @endif
                                                </div>
                                            @endif
                                        @endforeach
                                </td>
                                <td class="action-buttons">
                                    <a href="{!! route('admin.peasants.edit.get', [$peasant->getId()]) !!}" class="btn btn-default">Edit</a>
                                    <a href="{!! route('admin.conversations.peasant.get', ['peasantId' => $peasant->getId()]) !!}" class="btn btn-default">Conversations</a>
                                    <a href="{!! route('admin.peasants.message-as-bot.get', ['peasantId' => $peasant->getId(), 'onlyOnlineBots' => '0']) !!}" class="btn btn-default">Message as a bot</a>
                                    <a href="{!! route('admin.peasants.message-as-bot.get', ['peasantId' => $peasant->getId(), 'onlyOnlineBots' => '1']) !!}" class="btn btn-default">Message user as online bot</a>

                                    <form method="POST" action="{!! route('admin.users.destroy', ['userId' => $peasant->getId()]) !!}">
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
{{--                {!! $peasants->render() !!}--}}
{{--            </div>--}}
{{--        </div>--}}

    </div>

@endsection
