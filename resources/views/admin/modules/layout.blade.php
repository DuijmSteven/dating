@extends('admin.layouts.default.layout')


@section('content')

    <div class="row">
        <div class="col-xs-12">
            <form method="post" action="{!! route('admin.modules.layout.update')!!}"
                  enctype="application/x-www-form-urlencoded">
                {{ csrf_field() }}
                @foreach($views as $view)
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title">
                                <strong>Name</strong>: {{ $view->getName() }}<br>
                                <strong>Route Name</strong>: {{ $view->getRouteName() }}
                            </h3>
                        </div>
                        <div class="box-body">
                                <div class="box-body table-responsive no-padding">
                                    <table class="table table-hover">
                                        <thead>
                                        <tr>
                                            <th>View</th>
                                            <th>Layout Parts</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td width="200">
                                                    <strong>Name</strong>: {{ $view->getName() }}<br>
                                                    <strong>Route Name</strong>: {{ $view->getRouteName() }}
                                                </td>
                                                <td>
                                                    <div class="col-xs-12">
                                                    <div class="row">
                                                        @foreach($layoutParts as $layoutPart)
                                                            @php
                                                                $layoutPartIsActive = in_array($layoutPart->getName(), $view->layoutParts->pluck('name')->toArray());
                                                            @endphp
                                                            <div class="col-sm-6">
                                                                <div class="box box-warning with-border">
                                                                    <div class="box-header with-border">
                                                                        <h3 class="box-title">{{ ucfirst(str_replace('-', ' ', $layoutPart->getName())) }}
                                                                        </h3>

                                                                        <div class="box-tools pull-right">
                                                                            <label>
                                                                                <input type="checkbox"
                                                                                       data-toggle="toggle"
                                                                                       name="views[{{ $view->getId() }}][{{ $layoutPart->getId() }}][active]"
                                                                                        {{ $layoutPartIsActive ? 'checked' : '' }}
                                                                                >
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="box-body">
                                                                        <ol>
                                                                        @foreach($modules as $module)
                                                                            @php
                                                                                $moduleIsActive = in_array(
                                                                                    $module->getId(),
                                                                                    $view->moduleInstances->where('layout_part_id', $layoutPart->getId())->pluck('module_id')->toArray()
                                                                                );
                                                                            @endphp
                                                                            <li>
                                                                                <div class="checkbox">
                                                                                    <label>
                                                                                        <input type="checkbox"
                                                                                               name="views[{{ $view->getId() }}][{{ $layoutPart->getId() }}][{{ $module->getId() }}][active]"
                                                                                                {{ $moduleIsActive ? 'checked' : '' }}
                                                                                        >
                                                                                        {{ $module->getName() }}
                                                                                    </label>
                                                                                </div>
                                                                                <div>
                                                                                    @php
                                                                                        $priority = '';
                                                                                        if ($moduleIsActive) {
                                                                                            $priority = $view->moduleInstances
                                                                                                    ->where('layout_part_id', $layoutPart->getId())
                                                                                                    ->where('module_id', $module->getId())
                                                                                                    ->pluck('priority')[0];
                                                                                        }
                                                                                    @endphp
                                                                                    Priority:
                                                                                    <input type="number"
                                                                                           name="views[{{ $view->getId() }}][{{ $layoutPart->getId() }}][{{ $module->getId() }}][priority]"
                                                                                           value="{{ $priority }}"
                                                                                    >
                                                                                </div>
                                                                            </li>
                                                                        @endforeach
                                                                        </ol>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                    </div>
                                                </td>
                                            </tr>

                                        </tbody>
                                    </table>
                                </div>
                                <div class="form-group">
                                    <div class="text-right">
                                        <button type="submit"
                                                class="btn btn-primary">
                                            Update Layout
                                        </button>
                                    </div>
                                </div>

                        </div>
                    </div>
                @endforeach
            </form>
        </div>
    </div>

@endsection
