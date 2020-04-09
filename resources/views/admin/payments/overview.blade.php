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
                                <th>Item</th>
                                <th>Status</th>
                                <th class="no-wrap">Created at</th>
                                <th class="no-wrap">Updated at</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($payments as $payment)
                                <tr>
                                    <td>{!! $payment->getId() !!}</td>
                                    <td>{!! $payment->getTransactionId() !!}</td>
                                    <td>
                                        @if($payment->peasant)
                                            <strong>Username:</strong> {{ $payment->peasant->username }}<br>
                                            <strong>ID:</strong> <a href="{{ route('admin.peasants.edit.get', ['peasantId' => $payment->peasant->id]) }}">{{ $payment->peasant->id }}</a><br>
                                        @else
                                            The user does not exist
                                        @endif
                                    </td>
                                    <td>{{ ucfirst($payment->method) }}</td>
                                    <td>{{ $payment->creditpack_id ? ucfirst($creditpackNamePerId[$payment->creditpack_id]) . ' Creditpack' : '' }}</td>
                                    <td>{{ \App\Helpers\PaymentsHelper::$statuses[$payment->status] }}</td>
                                    <td class="no-wrap">{{ $payment->created_at->format('d-m-Y H:i:s') }}</td>
                                    <td class="no-wrap">{{ $payment->updated_at->format('d-m-Y H:i:s') }}</td>
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
