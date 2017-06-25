@extends('admin.layouts.default.layout')


@section('content')

    <div class="row">
        <div class="col-xs-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">{{ ucfirst(str_replace('-', ' ', $layoutPart->name)) }} Modules</h3>
                </div>

                <div class="box-body">
                    <form method="post" action="{!! route('admin.layout-parts.modules.update', ['layoutPartId' => $layoutPart->id])!!}">
                        {{ csrf_field() }}
                        <div class="box-body table-responsive no-padding">
                            <table class="table table-hover">
                                <thead>
                                <tr>
                                    <th>Module</th>
                                    <th>Active on {{ ucfirst(str_replace('-', ' ', $layoutPart->name)) }}</th>
                                    <th>Priority</th>
                                </tr>
                                </thead>
                                <tbody>
                                    @foreach($modules as $module)
                                        @php
                                            $moduleIsActive = $module->isActiveOnLayoutPart($layoutPart->name);

                                            /** @var \App\Module $module */
                                        @endphp
                                        <tr>
                                            <td>
                                                {!! ucfirst(str_replace('-', ' ', $module->name)) !!}
                                            </td>
                                            <td>
                                                <div class="checkbox">
                                                    <label>
                                                        <input type="checkbox"
                                                               name="modules[{!! $module->id !!}][active]"
                                                               {!! $moduleIsActive ? 'checked' : '' !!}
                                                        >
                                                        {!! $moduleIsActive ? 'Yes' : 'No' !!}
                                                    </label>
                                                </div>
                                            </td>
                                            <td>
                                                <input type="number"
                                                       name="modules[{!! $module->id !!}][priority]"
                                                       value="{!! $moduleIsActive ? $module->layoutParts[0]->pivot->priority : '' !!}"
                                                >
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="form-group">
                            <div class="text-right">
                                <button type="submit"
                                        class="btn btn-primary">
                                    Update {{ ucfirst(str_replace('-', ' ', $layoutPart->name)) }} modules
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
