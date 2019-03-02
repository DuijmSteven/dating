<div class="Tile Search">
    <div class="Tile__heading Search__heading">
        Search
    </div>
    <div class="Tile__body Search__body">
        <form method="GET" action="{{ route('users.search.form.get') }}">
            {{ csrf_field() }}
            <div class="form-group">
                <label for="city">{!! @trans('user_constants.city') !!}</label>
                <input type="text"
                       class="js-autoCompleteCites form-control"
                       name="city"
                >
                <input type="hidden"
                       name="lat"
                       class="js-hiddenLatInput"
                >
                <input type="hidden"
                       name="lng"
                       class="js-hiddenLngInput"
                >
            </div>
            <div class="form-group">
                <label for="">Age</label>
                <select name="age" class="form-control">
                    @foreach(\UserConstants::getAgeGroups() as $key => $value)
                        <option value="{{ $key }}">{{ $value }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="">Body type</label>
                <select name="body_type" class="form-control">
                    @foreach(\UserConstants::selectableField('body_type') as $key => $value)
                        <option value="{{ $key }}">{{ @trans('user_constants.body_type.' . $key) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="">Height</label>
                <select name="height" class="form-control">
                    @foreach(\UserConstants::selectableField('height') as $key => $value)
                        <option value="{{ $key }}">{{ @trans('user_constants.height.' . $key) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="text-right">
                @include('frontend.components.button', [
                    'buttonContext' => 'form',
                    'buttonType' => 'submit',
                    'buttonState' => 'primary',
                    'buttonText' => 'SEARCH'
                ])
            </div>
        </form>
    </div>
</div>