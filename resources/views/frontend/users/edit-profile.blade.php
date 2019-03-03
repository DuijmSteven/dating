@extends('frontend.layouts.default.layout')


@section('content')
    <div class="Tile EditProfile JS--Edit-Profile">
        <div class="Tile__heading">Edit profile</div>
        <div class="Tile__body">
            <form role="form" class="searchForm" method="POST" action="{!! route('admin.peasants.update', ['id' => $user->id]) !!}"
                  enctype="multipart/form-data">
                {!! csrf_field() !!}
                {!! method_field('PUT') !!}
                <div class="box-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="username">Username</label>
                                <input type="text"
                                       class="form-control"
                                       id="username"
                                       name="username"
                                       required
                                       value="{!! $user->username !!}"
                                >
                                @if ($errors->has('username'))
                                    {!! $errors->first('username', '<small class="form-error">:message</small>') !!}
                                @endif
                            </div>
                        </div>

                        <div class="col-xs-12">
                            <hr/>
                        </div>

{{--                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Date of birth:</label>
                                <div class="input-group date">
                                    <div class="input-group-addon">
                                        <i class="material-icons material-icon-calendar">
                                            calendar_today
                                        </i>
                                    </div>
                                    <input type="text"
                                           class="form-control pull-right datepicker__date JS--datepicker__date"
                                           name="dob"
                                           value="{!! $user->meta->dob->format('d-m-Y') !!}"
                                    >
                                    @if ($errors->has('dob'))
                                        {!! $errors->first('dob', '<small class="form-error">:message</small>') !!}
                                    @endif
                                </div>
                                <!-- /.input group -->
                            </div>
                        </div>--}}

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="password">City</label>
                                <input type="text"
                                       class="JS--autoCompleteCites form-control"
                                       name="city"
                                       value="{!! ucfirst($user->meta->city) !!}"
                                >
                                <input type="hidden"
                                       name="lat"
                                       class="js-hiddenLatInput"
                                >
                                <input type="hidden"
                                       name="lng"
                                       class="js-hiddenLngInput"
                                >
                                @if ($errors->has('city'))
                                    {!! $errors->first('city', '<small class="form-error">:message</small>') !!}
                                @endif
                            </div>
                        </div>
                        <?php $counter = 0; ?>
                        @foreach(\UserConstants::selectableFields('peasant') as $field => $possibleOptions)
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="{!! $field !!}">{!! ucfirst(str_replace('_', ' ', $field)) !!}</label>
                                    <select name="{!! $field !!}"
                                            id="{!! $field !!}"
                                            class="form-control"
                                    >
                                        <option value=""
                                                {!! old($field) == '' ? 'selected' : '' !!}
                                        ></option>
                                        @foreach($possibleOptions as $key => $value)
                                            <option value="{!! $key == '' ? null : $key !!}"
                                                    {!! ($user->meta[$field] === $key) ? 'selected' : '' !!}
                                            >
                                                {!! $value !!}
                                            </option>
                                        @endforeach
                                    </select>
                                    @include('helpers.forms.error_message', ['field' => $field])
                                </div>
                            </div>
                            {{--Prevents breaking when error on > xs viewports--}}
                            @if($counter % 2)
                                <div class="col-xs-12"></div>
                            @endif
                            <?php $counter++; ?>
                        @endforeach

                        <div class="col-xs-12">
                            <hr/>
                        </div>

                        <?php $counter = 0; ?>
                        @foreach(\UserConstants::textFields('peasant') as $field)
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="{!! $field !!}">{!! ucfirst(str_replace('_', ' ', $field)) !!}</label>
                                    <textarea name="{!! $field !!}"
                                              id="{!! $field !!}"
                                              class="form-control"
                                              cols="30"
                                              rows="10"
                                    >{!! $user->meta[$field] !!}</textarea>
                                    @include('helpers.forms.error_message', ['field' => $field])
                                </div>
                            </div>
                            @if($counter % 2)
                                <div class="col-xs-12"></div>
                            @endif
                            <?php $counter++; ?>
                        @endforeach

                        <div class="col-xs-12">
                            <hr/>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="user_images">Gallery Images</label>
                                <input type="file" class="form-control" id="user_images" name="user_images[]" multiple>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="text-right">
                                @include('frontend.components.button', [
                                    'buttonContext' => 'form',
                                    'buttonType' => 'submit',
                                    'buttonState' => 'primary',
                                    'buttonText' => 'Aanpasen'
                                ])
                            </div>
                        </div>
                    </div>
                </div>
            </form>

        </div>
    </div>
@endsection