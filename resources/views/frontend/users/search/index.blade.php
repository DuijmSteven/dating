@extends('frontend.layouts.default.layout')

@section('content')
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
                    @include('frontend.forms.helpers.error_message', ['field' => 'query'])
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
                    @include('frontend.forms.helpers.error_message', ['field' => 'username'])
                </div>
            </div>

            {{-- Prevents breaking when error on > xs viewports --}}
            <div class="col-xs-12"></div>

            <?php $counter = 0; ?>
            @foreach(\UserConstants::SELECTABLE_PROFILE_FIELDS as $field => $possibleOptions)
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="query">{!! ucfirst(str_replace('_', ' ', $field)) !!}</label>
                        <select name="{!! $field !!}"
                                id="{!! $field !!}"
                                class="form-control"
                        >
                            @foreach(array_merge(['any'], $possibleOptions) as $option)
                                <option value="{{ $option == 'any' ? null : $option }}"
                                        {{ (old($field) == $option) ? 'selected' : '' }}
                                >
                                    {{ ucfirst(str_replace('_', ' ', $option)) }}
                                </option>
                            @endforeach
                        </select>
                        @include('frontend.forms.helpers.error_message', ['field' => $field])
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
                   <button type="submit"
                           class="btn btn-default"
                   >
                       Submit</button>
               </div>
            </div>
        </div>
    </form>

@endsection