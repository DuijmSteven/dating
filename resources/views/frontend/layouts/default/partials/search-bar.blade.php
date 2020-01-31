<div class="SearchBar JS--SearchBar hidden {{ count($errors) ? 'with-errors' : '' }}">
    <form method="POST" action="{{ route('users.search.form.get') }}">
        {{ csrf_field() }}
        <div class="form-group city {{ $errors->has('city_name') ? ' has-error' : '' }}">
            <label for="city">{!! @trans('user_constants.city') !!}</label>
            <input type="text"
                   class="JS--autoCompleteCites JS--bar form-control"
                   name="city_name"
                   value="{!! old('city_name') ?? Session::get('searchParameters')['city_name'] !!}"
            >
            @if ($errors->has('city'))
                <span class="help-block">
                    <strong>{{ $errors->first('city_name') }}</strong>
                </span>
            @endif
            <input type="hidden"
                   name="lat"
                   class="js-hiddenLatInput"
            >
            <input type="hidden"
                   name="lng"
                   class="js-hiddenLngInput"
            >
        </div>
        <div class="form-group radius hidden JS--radiusSearchInput">
            <label for="">{!! @trans('search.distance') !!}</label>
            <select name="radius" class="form-control">
                @foreach(\UserConstants::getRadiuses() as $radius)
                    <option
                        {{ Session::get('searchParameters')['radius'] == $radius ? 'selected' : ''}}
                        value="{{ $radius }}">{{ $radius }}km
                    </option>
                @endforeach
            </select>
        </div>
        <div class="form-group age">
            <label for="">{!! @trans('user_constants.age') !!}</label>
            <select name="age" class="form-control">
                <option value="">{!! @trans('search.all') !!}</option>
                @foreach(\UserConstants::getAgeGroups() as $key => $value)
                    <option
                        {{ Session::get('searchParameters')['age'] == $key ? 'selected' : ''}}
                        value="{{ $key }}">{{ $value }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="form-group bodyType">
            <label for="">{{ trans('user_constants.labels.body_type') }}</label>
            <select name="body_type" class="form-control">
                <option value="">{!! @trans('search.all') !!}</option>
                @foreach(\UserConstants::selectableField('body_type') as $key => $value)
                    <option
                        {{ Session::get('searchParameters')['body_type'] == $key ? 'selected' : ''}}
                        value="{{ $key }}">{{ @trans('user_constants.body_type.' . $key) }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="form-group height">
            <label for="">{{ trans('user_constants.labels.height') }}</label>
            <select name="height" class="form-control">
                <option value="">{!! @trans('search.all') !!}</option>
                @foreach(\UserConstants::selectableField('height') as $key => $value)
                    <option
                        {{ Session::get('searchParameters')['height'] == $key ? 'selected' : ''}}
                        value="{{ $key }}">{{ @trans('user_constants.height.' . $key) }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="form-group submit text-right">
            <label for="">&nbsp; </label>

            @include('frontend.components.button', [
                'buttonContext' => 'form',
                'buttonType' => 'submit',
                'buttonState' => 'default',
                'buttonText' => '<i class="material-icons">
                                    search
                                </i>',
                'buttonClasses' => 'Button-fw'
            ])
        </div>
    </form>
</div>