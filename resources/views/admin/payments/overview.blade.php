@extends('admin.layouts.default.layout')


@section('content')

    <div class="row">

        <div class="col-xs-12">
            <div class="pagination__container text-right">
                {!! $payments->render() !!}
            </div>
        </div>

        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Payments (Total: <strong>{!! $payments->total() !!}</strong>)</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body table-responsive no-padding">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Transaction ID</th>
                                <th>User</th>
                                <th>Method</th>
                                <th>Amount</th>
                                <th>Discount</th>
                                <th>Previous completed payment</th>
                                <th>Is conversion</th>
                                <th>Item</th>
                                <th>Status</th>
                                <th class="no-wrap">Created at</th>
                                <th class="no-wrap">Updated at</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($payments as $payment)
                                <tr class="
                                        {{ $payment->getIsConversion() ? 'conversionPayment' : '' }}
                                        {{ $payment->getStatus() === \App\Payment::STATUS_COMPLETED ? 'completedPayment' : '' }}
                                        {{ $payment->getStatus() === \App\Payment::STATUS_STARTED ? 'startedPayment' : '' }}
                                    "
                                >
                                    <td>{!! $payment->getId() !!}</td>
                                    <td>{!! $payment->getTransactionId() !!}</td>
                                    <td>
                                        @if($payment->peasant)
                                            <strong>Username:</strong> {{ $payment->peasant->username }}<br>
                                            <strong>ID:</strong> <a href="{{ route('admin.peasants.edit.get', ['peasantId' => $payment->peasant->id]) }}">{{ $payment->peasant->id }}</a><br>

{{--                                            @if($payment->peasant->getLastOnlineAt())--}}
{{--                                                <div class="innerTableWidgetHeading"><strong>Activity</strong></div>--}}
{{--                                                <div class="innerTableWidgetBody">--}}
{{--                                                    <strong>Last active at</strong> {!! $payment->peasant->getLastOnlineAt()->tz('Europe/Amsterdam')->format('d-m-Y H:i:s') !!}--}}
{{--                                                    ({!! $payment->peasant->getLastOnlineAt()->tz('Europe/Amsterdam')->diffForHumans() !!})--}}
{{--                                                    <br>--}}

{{--                                                    <strong>Created at</strong> {!! $payment->peasant->getCreatedAt()->tz('Europe/Amsterdam')->format('d-m-Y H:i:s') !!}--}}
{{--                                                    ({!! $payment->peasant->getCreatedAt()->tz('Europe/Amsterdam')->diffForHumans() !!})--}}
{{--                                                    <br>--}}
{{--                                                </div>--}}
{{--                                            @endif--}}

{{--                                            @if(count($payment->peasant->completedPayments) > 0)--}}
{{--                                                <div class="innerTableWidgetHeading"><strong>Payments</strong></div>--}}
{{--                                                <div class="innerTableWidgetBody">--}}
{{--                                                    <strong># of payments</strong>: {{ count($payment->peasant->completedPayments) }} <br>--}}
{{--                                                    <strong>Latest Payment amount</strong>: &euro;{{ number_format($payment->peasant->completedPayments[0]->amount/ 100, 2) }} <br>--}}
{{--                                                    <strong>Latest Payment date</strong>: {{ $payment->peasant->completedPayments[0]->created_at->tz('Europe/Amsterdam')->format('d-m-Y H:i:s') }}--}}
{{--                                                    ({!! $payment->peasant->completedPayments[0]->created_at->tz('Europe/Amsterdam')->diffForHumans() !!})<br>--}}

{{--                                                    @if(count($payment->peasant->completedPayments) > 1)--}}
{{--                                                        <strong>Previous Payment amount</strong>: &euro;{{ number_format($payment->peasant->completedPayments[1]->amount/ 100, 2) }} <br>--}}
{{--                                                        <strong>Previous Payment date</strong>: {{ $payment->peasant->completedPayments[1]->created_at->tz('Europe/Amsterdam')->format('d-m-Y H:i:s') }}--}}
{{--                                                        ({!! $payment->peasant->completedPayments[1]->created_at->tz('Europe/Amsterdam')->diffForHumans() !!})<br>--}}
{{--                                                    @endif--}}

{{--                                                    <?php--}}
{{--                                                    $moneySpent = 0;--}}
{{--                                                    foreach ($payment->peasant->completedPayments as $payment) {--}}
{{--                                                        $moneySpent += $payment->amount;--}}
{{--                                                    }--}}
{{--                                                    ?>--}}

{{--                                                    <strong>Money spent</strong>: &euro;{{ number_format($moneySpent/ 100, 2) }} <br>--}}
{{--                                                </div>--}}
{{--                                            @endif--}}

{{--                                            @if($payment->peasant->affiliateTracking)--}}
{{--                                                <div class="innerTableWidgetHeading"><strong>Affiliate tracking</strong></div>--}}
{{--                                                <div class="innerTableWidgetBody">--}}
{{--                                                    <strong>Affiliate</strong> {{ $payment->peasant->affiliateTracking->getAffiliate() }}<br>--}}
{{--                                                    <strong>Click ID</strong> {{ $payment->peasant->affiliateTracking->getClickId() }}<br>--}}
{{--                                                    <strong>Media ID</strong> {{ $payment->peasant->affiliateTracking->getMediaId() }}<br>--}}
{{--                                                </div>--}}
{{--                                            @endif--}}
                                        @else
                                            The user does not exist
                                        @endif
                                    </td>
                                    <td>{{ ucfirst($payment->method) }}</td>
                                    <td>{{ number_format($payment->amount / 100, 2) }}</td>
                                    <td class="{{ $payment->getDiscountPercentage() ? 'paymentHasDiscount' : '' }}">{{ $payment->getDiscountPercentage() }}{{ $payment->getDiscountPercentage() ? '%' : '' }}</td>
                                    <td>{{ count($payment->peasant->payments) > 1 ? \Carbon\Carbon::parse($payment->peasant->payments[1]->created_at)->locale('en')->diffForHumans() : '' }}</td>
                                    <td>{{ $payment->getIsConversion() ? 'Yes' : 'No' }}</td>
                                    <td>{{ $payment->creditpack_id ? ucfirst($creditpackNamePerId[$payment->creditpack_id]) . ' Creditpack' : '' }}</td>
                                    <td>{{ \App\Helpers\PaymentsHelper::$statuses[$payment->status] }}</td>
                                    <td class="no-wrap">{{ $payment->getCreatedAt()->format('d-m-Y H:i:s') }}</td>
                                    <td class="no-wrap">{{ $payment->getUpdatedAt()->format('d-m-Y H:i:s') }}</td>
                                    <td class="action-buttons">
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
                {!! $payments->render() !!}
            </div>
        </div>

    </div>

@endsection
