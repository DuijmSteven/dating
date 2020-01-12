@extends('frontend.layouts.default.layout')

@section('content')

<div class="row">
    <div class="col-xs-12">
        <div class="Tile">
            <div class="Tile__heading">{{ @trans('contact.heading') }}</div>
            <div class="Tile__body">

                <table class="table table-striped">
                    <tbody>
                        <tr>
                            <td><span class="CompanyInfo__title">{{ @trans('contact.company') }}</span></td>
                            <td><span class="CompanyInfo__value">{!! config('company.name') !!}</span></td>
                        </tr>
                        <tr>
                            <td><span class="CompanyInfo__title">{{ @trans('contact.kvk_number') }}</span></td>
                            <td><span class="CompanyInfo__value">{!! config('company.kvk') !!}</span></td>
                        </tr>
                        <tr>
                            <td><span class="CompanyInfo__title">{{ @trans('contact.btw_number') }}</span></td>
                            <td><span class="CompanyInfo__value">{!! config('company.btw') !!}</span></td>
                        </tr>
                        <tr>
                            <td><span class="CompanyInfo__title">{{ @trans('contact.address') }}</span></td>
                            <td><span class="CompanyInfo__value">{!! config('company.address') !!}, {!! config('company.city') !!}</span></td>
                        </tr>
                        <tr>
                            <td><span class="CompanyInfo__title">{{ @trans('contact.support_email') }}</span></td>
                            <td><span class="CompanyInfo__value">{!! config('company.info_email') !!}</span></td>
                        </tr>
                    </tbody>
                </table>

{{--                <ul>--}}
{{--                    <li><strong>{{ @trans('contact.company') }}</strong>: {!! config('company.name') !!}</li>--}}
{{--                    <li><strong>{{ @trans('contact.kvk_number') }}</strong>: {!! config('company.kvk') !!}</li>--}}
{{--                    <li><strong>{{ @trans('contact.btw_number') }}</strong>: {!! config('company.btw') !!}</li>--}}
{{--                    <li><strong>{{ @trans('contact.address') }}</strong>: {!! config('company.address') !!}, {!! config('company.city') !!}</li>--}}
{{--                    <li><strong>{{ @trans('contact.support_email') }}</strong>: {!! config('company.email') !!}</li>--}}
{{--                </ul>--}}
            </div>
        </div>

        <div class="Tile">
            <div class="Tile__heading">{{ @trans('contact.send_a_message') }}</div>
            <div class="Tile__body">
                <form  method="post" action="{{ route('contact.post') }}" class="form" role="form">
                    @csrf

                    <div class="form-group {{ $errors->has('email') ? ' has-error' : '' }}">
                        <label for="email">{{ @trans('contact.email') }}</label>
                        <input type="email"
                               name="email"
                               id="email"
                               value="{{ old('email') }}"
                               class="form-control">
                        @if ($errors->has('email'))
                            <span class="help-block">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                        @endif
                    </div>
                    <div class="form-group {{ $errors->has('name') ? ' has-error' : '' }}">
                        <label for="name">{{ @trans('contact.name') }}</label>
                        <input type="name"
                               name="name"
                               id="name"
                               value="{{ old('name') }}"
                               class="form-control">
                        @if ($errors->has('name'))
                            <span class="help-block">
                                <strong>{{ $errors->first('name') }}</strong>
                            </span>
                        @endif
                    </div>
                    <div class="form-group {{ $errors->has('subject') ? ' has-error' : '' }}">
                        <label for="subject">{{ @trans('contact.subject') }}</label>
                        <input type="text"
                               name="subject"
                               id="subject"
                               value="{{ old('subject') }}"
                               class="form-control">
                        @if ($errors->has('subject'))
                            <span class="help-block">
                                <strong>{{ $errors->first('subject') }}</strong>
                            </span>
                        @endif
                    </div>
                    <div class="form-group {{ $errors->has('body') ? ' has-error' : '' }}">
                        <label for="body">{{ @trans('contact.message') }}</label>
                        <textarea rows="7"
                               name="body"
                               id="body"
                               class="form-control"
                        >
                            {{ old('body') }}
                        </textarea>
                        @if ($errors->has('body'))
                            <span class="help-block">
                                <strong>{{ $errors->first('body') }}</strong>
                            </span>
                        @endif
                    </div>
                    <div class="form-group text-right">
                        <button type="submit"
                                class="btn btn-default">
                            {{ @trans('contact.send') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
