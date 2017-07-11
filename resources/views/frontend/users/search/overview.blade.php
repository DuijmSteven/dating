@extends('frontend.layouts.default.layout')

@section('content')
    <div class="Tile">
        <div class="Tile__heading">Search</div>
        <div class="Tile__body">
            <form method="post" action="{{ route('users.search.post') }}">
                {{ csrf_field() }}
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="query">Query</label>
                            <input type="text"
                                   id="query"
                                   name="query"
                                   class="form-control {!! $errors->has('query') ? 'form-error' : '' !!}"
                                   value="{{ old('query') }}"
                            >
                            @include('helpers.forms.error_message', ['field' => 'query'])
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="query">Username</label>
                            <input type="text"
                                   id="username"
                                   name="username"
                                   class="form-control"
                                   value="{{ old('username') }}"
                            >
                            @include('helpers.forms.error_message', ['field' => 'username'])
                        </div>
                    </div>

                    {{-- Prevents breaking when error on > xs viewports --}}
                    <div class="col-xs-12"></div>

                    <?php $counter = 0; ?>
                    @foreach(\UserConstants::selectableFields('peasant') as $field => $possibleOptions)
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="query">{!! ucfirst(str_replace('_', ' ', $field)) !!}</label>
                                <select name="{!! $field !!}"
                                        id="{!! $field !!}"
                                        class="form-control"
                                >
                                    <option value=""
                                            {!! old($field) == '' ? 'selected' : '' !!}
                                    ></option>
                                    @foreach($possibleOptions as $key => $value)
                                        <option value="{{ $key }}"
                                                {{ (old($field) === $key) ? 'selected' : '' }}
                                        >
                                            {{ ucfirst($value) }}
                                        </option>
                                    @endforeach
                                </select>
                                @include('helpers.forms.error_message', ['field' => $field])
                            </div>
                        </div>

                        {{-- Prevents breaking when error on > xs viewports --}}
                        @if($counter % 2)
                            <div class="col-xs-12"></div>
                        @endif
                        <?php $counter++; ?>
                    @endforeach
                    <div class="col-xs-12">
                       <div class="form-group text-right">
                           @include('frontend.components.button', [
                                'buttonContext' => 'form',
                                'buttonType' => 'submit',
                                'buttonState' => 'primary',
                                'buttonText' => 'POST'
                            ])
                       </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection