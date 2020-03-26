<div class="col-xs-12">
    <div class="box box-success collapsed-box">
        <div class="box-header with-border">
            <h3 class="box-title">Search</h3>

            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                        class="fa fa-minus"></i></button>
            </div>
        </div>
        <div class="box-body" style="">
            <div class="row">
                <form method="POST" action="{{ route('admin.users.search.post') }}">
                    {{ csrf_field() }}

                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="role_id">Role</label>
                            <select name="role_id" id="role_id" class="form-control">
                                <option value="2" {{ old('role_id') == 2 ? 'selected' : '' }}>Peasant</option>
                                <option value="3" {{ old('role_id') == 3 ? 'selected' : '' }}>Bot</option>
                                <option value="4" {{ old('role_id') == 4 ? 'selected' : '' }}>Operator</option>
                                <option value="5" {{ old('role_id') == 5 ? 'selected' : '' }}>Editor</option>
                            </select>
                            @include('helpers.forms.error_message', ['field' => 'age'])
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="query">Query</label>
                            <input type="text"
                                   autocomplete="off"
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
                                   autocomplete="off"
                                   name="username"
                                   class="form-control"
                                   value="{{ old('username') }}"
                            >
                            @include('helpers.forms.error_message', ['field' => 'username'])
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="age">Age</label>
                            <select name="age" id="age" class="form-control">
                                <option value=""
                                    {!! old('age') == '' ? 'selected' : '' !!}
                                ></option>
                                @foreach(\UserConstants::getAgeGroups() as $key => $value)
                                    <option value="{{ $key }}"
                                        {{ (old('age') === $key) ? 'selected' : '' }}
                                    >
                                        {{ $value }}
                                    </option>
                                @endforeach
                            </select>
                            @include('helpers.forms.error_message', ['field' => 'age'])
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="city">City</label>
                            <input type="text"
                                   id="city"
                                   autocomplete="off"
                                   class="JS--Search__autoCompleteCites form-control"
                                   name="city"
                            >
                            @include('helpers.forms.error_message', ['field' => 'city'])
                        </div>
                    </div>

                    <?php $counter = 0; ?>
                    @foreach(\UserConstants::selectableFields('peasant') as $field => $possibleFieldOptions)
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label
                                    for="{!! $field !!}">{!! ucfirst(str_replace('_', ' ', $field)) !!}</label>
                                <select name="{!! $field !!}"
                                        id="{!! $field !!}"
                                        class="form-control"
                                >
                                    <option value=""
                                        {!! old($field) == '' ? 'selected' : '' !!}
                                    ></option>
                                    @foreach($possibleFieldOptions as $key => $value)
                                        <option value="{{ $key }}"
                                            {{ (old($field) === $key) ? 'selected' : '' }}
                                        >
                                            {{ ucfirst(str_replace('_', ' ', $value)) }}
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
                        <div class="text-right">
                            @include('frontend.components.button', [
                                 'buttonContext' => 'form',
                                 'buttonType' => 'submit',
                                 'buttonState' => 'primary',
                                 'buttonText' => 'SEARCH'
                             ])
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>