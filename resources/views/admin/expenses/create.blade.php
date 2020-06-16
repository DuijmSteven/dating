@extends('admin.layouts.default.layout')


@section('content')

    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Create Expense</h3>
        </div>
        <!-- /.box-header -->
        <!-- form start -->
        <form role="form" method="POST" action="{!! route('admin.expenses.post') !!}" enctype="multipart/form-data">
            {!! csrf_field() !!}
            <div class="box-body">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="payee">Payee</label>
                            <select name="payee"
                                    id="payee"
                                    class="form-control"
                                    required
                            >
                                <option value="{{ \App\Expense::PAYEE_XPARTNERS }}" {!! (old('payee') === \App\Expense::PAYEE_XPARTNERS) ? 'selected' : '' !!}>
                                    {{ \App\Expense::payeeDescriptionPerId()[\App\Expense::PAYEE_XPARTNERS] }}
                                </option>
                                <option value="{{ \App\Expense::PAYEE_GOOGLE }}" {!! (old('payee') === \App\Expense::PAYEE_GOOGLE) ? 'selected' : '' !!}>
                                    {{ \App\Expense::payeeDescriptionPerId()[\App\Expense::PAYEE_GOOGLE] }}
                                </option>
                                <option value="{{ \App\Expense::PAYEE_OTHER }}" {!! (old('payee') === \App\Expense::PAYEE_OTHER) ? 'selected' : '' !!}>
                                    {{ \App\Expense::payeeDescriptionPerId()[\App\Expense::PAYEE_GOOGLE] }}
                                </option>
                            </select>
                            @if ($errors->has('payee'))
                                {!! $errors->first('payee', '<small class="form-error">:message</small>') !!}
                            @endif
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="type">Type</label>
                            <select name="type"
                                    id="type"
                                    class="form-control"
                                    required
                            >
                                <option value="{{ \App\Expense::TYPE_ADS }}" {!! (old('type') === \App\Expense::TYPE_ADS) ? 'selected' : '' !!}>
                                    {{ \App\Expense::typeDescriptionPerId()[\App\Expense::TYPE_ADS] }}
                                </option>
                                <option value="{{ \App\Expense::TYPE_INFRASTRUCTURE }}" {!! (old('type') === \App\Expense::TYPE_INFRASTRUCTURE) ? 'selected' : '' !!}>
                                    {{ \App\Expense::typeDescriptionPerId()[\App\Expense::TYPE_INFRASTRUCTURE] }}
                                </option>
                                <option value="{{ \App\Expense::TYPE_SALARY }}" {!! (old('type') === \App\Expense::TYPE_SALARY) ? 'selected' : '' !!}>
                                    {{ \App\Expense::typeDescriptionPerId()[\App\Expense::TYPE_SALARY] }}
                                </option>
                                <option value="{{ \App\Expense::TYPE_OTHER }}" {!! (old('type') === \App\Expense::TYPE_OTHER) ? 'selected' : '' !!}>
                                    {{ \App\Expense::typeDescriptionPerId()[\App\Expense::TYPE_OTHER] }}
                                </option>
                            </select>
                            @if ($errors->has('type'))
                                {!! $errors->first('type', '<small class="form-error">:message</small>') !!}
                            @endif
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>Takes/took place at:</label>
                            <div class="input-group date">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <input type="text"
                                       class="form-control pull-right datepicker__date"
                                       name="takes_place_at"
                                       value="{!! old('takes_place_at', \Carbon\Carbon::now()->tz('Europe/Amsterdam')->format('d-m-Y')) !!}"
                                >
                                @if ($errors->has('takes_place_at'))
                                    {!! $errors->first('takes_place_at', '<small class="form-error">:message</small>') !!}
                                @endif
                            </div>
                            <!-- /.input group -->
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="amount">Amount</label>
                            <input type="text"
                                   class="form-control"
                                   id="amount"
                                   name="amount"
                                   required
                                   value="{!! old('amount', '') !!}"
                            >
                            @if ($errors->has('amount'))
                                {!! $errors->first('amount', '<small class="form-error">:message</small>') !!}
                            @endif
                        </div>
                    </div>

                    <div class="col-xs-12">
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea class="form-control"
                                      id="description"
                                      name="description"
                                      rows="20"
                            ></textarea>
                            @if ($errors->has('description'))
                                {!! $errors->first('description', '<small class="form-error">:message</small>') !!}
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.box-body -->
            <div class="box-footer text-right">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </form>
    </div>

@endsection
