@extends('admin.layouts.default.layout')


@section('content')

    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Create Invoice</h3>
        </div>
        <!-- /.box-header -->
        <!-- form start -->
        <form role="form" method="POST" action="{{ route('admin.invoices.post') }}" enctype="multipart/form-data">
            {!! csrf_field() !!}
            <div class="box-body">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="sequenceNumber">Sequence number</label>
                            <input type="text"
                                   class="form-control"
                                   id="sequenceNumber"
                                   name="sequenceNumber"
                                   required
                                   value="{{ old('sequenceNumber', '') }}"
                            >
                            </input>
                            @if ($errors->has('sequenceNumber'))
                                {!! $errors->first('sequenceNumber', '<small class="form-error">:message</small>') !!}
                            @endif
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="employeeId">Operator/Editor</label>
                            <select name="employeeId"
                                    id="employeeId"
                                    class="form-control"
                                    required
                            >
                                <option value=""></option>

                                @foreach($employees as $employee)
                                    <option value="{{ $employee->getId() }}" {!! (old('employeeId') === $employee->getId()) ? 'selected' : '' !!}>
                                        (ID: {{ $employee->getId() }}) - (Role: {{ $employee->roles[0]->name }}) - {{ $employee->username }}
                                    </option>
                                @endforeach
                            </select>
                            @if ($errors->has('employeeId'))
                                {!! $errors->first('employeeId', '<small class="form-error">:message</small>') !!}
                            @endif
                        </div>
                    </div>

                    <div class="col-sm-12">
                        <hr>

                        <h4>Date range</h4>

                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>After:</label>
                                    <div class="input-group date">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                        <input type="text"
                                               class="form-control pull-right datepicker__date defaultToPresent"
                                               name="fromDate"
                                               value="{{ old('fromDate') ? old('fromDate') : '' }}"
                                        >
                                        @if ($errors->has('fromDate'))
                                            {!! $errors->first('fromDate', '<small class="form-error">:message</small>') !!}
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Before:</label>
                                    <div class="input-group date">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                        <input type="text"
                                               class="form-control pull-right datepicker__date defaultToPresent"
                                               name="untilDate"
                                               value="{{ old('untilDate') ? old('untilDate') : '' }}"
                                        >
                                        @if ($errors->has('untilDate'))
                                            {!! $errors->first('untilDate', '<small class="form-error">:message</small>') !!}
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr>
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
