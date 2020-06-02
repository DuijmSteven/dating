@extends('admin.layouts.default.layout')


@section('content')

    <div class="row">

        <div class="col-xs-12">
            <div class="pagination__container text-right">
                {!! $expenses->render() !!}
            </div>
        </div>

        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <div class="pull-left">
                        <h3 class="box-title">Articles (Total: <strong>{!! $expenses->total() !!}</strong>)</h3>
                    </div>
                    <a class="pull-right btn btn-success" href="{{ route('admin.expenses.create') }}">Create expense</a>
                </div>
                <!-- /.box-header -->
                <div class="box-body table-responsive no-padding">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Payee</th>
                                <th>Type</th>
                                <th>Amount (&euro;)</th>
                                <th>Description</th>
                                <th class="no-wrap">Takes/took place at</th>
                                <th class="no-wrap">Created at</th>
                                <th class="no-wrap">Updated at</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($expenses as $expense)
                                <tr>
                                    <td>{!! $expense->id !!}</td>
                                    <td>{{ \App\Expense::payeeDescriptionPerId()[$expense->getPayee()] }}</td>
                                    <td>{{ \App\Expense::typeDescriptionPerId()[$expense->getType()] }}</td>
                                    <td>{!! $expense->amountInEuro !!}</td>
                                    <td>{!! $expense->getDescription() !!}</td>
                                    <td class="no-wrap">
                                        {{ $expense->getTakesPlaceAt()->tz('Europe/Amsterdam')->format('d-m-Y') }}
                                        <br>
                                        ({!! $expense->getTakesPlaceAt()->tz('Europe/Amsterdam')->diffForHumans() !!})
                                    </td>
                                    <td class="no-wrap">
                                        {{ $expense->getCreatedAt()->tz('Europe/Amsterdam')->format('d-m-Y H:i:s') }}
                                        <br>
                                        ({!! $expense->getCreatedAt()->tz('Europe/Amsterdam')->diffForHumans() !!})
                                    </td>
                                    <td class="no-wrap">
                                        {{ $expense->getUpdatedAt()->tz('Europe/Amsterdam')->format('d-m-Y H:i:s') }}
                                        <br>
                                        ({!! $expense->getUpdatedAt()->tz('Europe/Amsterdam')->diffForHumans() !!})
                                    </td>
                                    <td class="action-buttons">
                                        <a href="{{ route('admin.expenses.edit', ['expenseId' => $expense->id]) }}"
                                           class="btn btn-default">
                                            Edit
                                        </a>
                                        <form method="POST" action="{{ route('admin.expenses.destroy', ['expenseId' => $expense->getId()]) }}">
                                            {!! csrf_field() !!}
                                            {!! method_field('DELETE') !!}
                                            <button type="submit"
                                                    class="btn btn-danger"
                                                    onclick="return confirm('Are you sure you want to delete this expense?')">
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
                {!! $expenses->render() !!}
            </div>
        </div>

    </div>

@endsection
