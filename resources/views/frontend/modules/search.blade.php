<div class="Tile Search">
    <div class="Tile__heading Search__heading">
        Search
    </div>
    <div class="Tile__body Search__body">
        <form method="GET" action="{{ route('users.search.form.get') }}">
            {{ csrf_field() }}
            <div class="form-group">
                <label for="">City</label>
                <input type="text"
                       autocomplete="off"
                       class="JS--Search__autoCompleteCites form-control"
                       name="city"
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
                        <option value="{{ $key }}">{{ ucfirst($value) }}</option>
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