@extends('frontend.layouts.default.layout')

@section('content')
    <div class="credits-page-content">
        <h4>{{ trans('credits.select_package') }}</h4>
        <div class="pricing-3">
            <div>
                <div class="row">
                    @foreach($creditpacks as $creditpack)
                        <div class="col-md-4">
                            <div data-creditpack-id="{{ $creditpack->id }}"
                                 class="block block-pricing {{ $loop->iteration == 2 ? 'block-raised' : '' }} JS--creditpack"
                            >
                                <div class="table {{ $loop->iteration == 2 ? 'table-rose' : '' }}">
                                    <h6 class="category">{{ $creditpack->name }}</h6>
                                    <h1 class="block-caption"><small>€</small><span>{{ $creditpack->price/100 }}</span>
                                    </h1>
                                    <ul>
                                        <li><b class="package-credits">{{ $creditpack->credits }}</b> {{ trans('credits.credits') }}</li>
                                        <li><b>&euro;{{ round($creditpack->price/$creditpack->credits / 100, 2) }}</b> {{ trans('credits.per_message') }}
                                        </li>
                                    </ul>

                                    <a
                                        href="#"
                                        class="btn {{ $loop->iteration == 2 ? 'btn-white' : 'btn-rose' }} btn-round JS--prevent-default__click"
                                    >
                                        {{ trans('credits.select') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="row pricing-10">
            <form method="post" action="{{ route('credits.store') }}">
                {{ csrf_field() }}
                <div class="col-md-6">
                    <h4>{{ trans('credits.select') }}</h4>
                    <ul class="list-group mb-3 JS--paymentMethods paymentMethodsList">
                        <li class="list-group-item d-flex justify-content-between"
                            style="margin-bottom: 10px; flex-wrap: wrap;">
                            <label class="paymentMethodsLabel">
                                <input type="radio" name="paymentMethod" value="ideal" required>
                                <span style="margin-left: 5px">iDeal</span>
                            </label>
                            <span class="paymentMethodLogo">
                                <img style="height: 29px" src="{{ asset('img/icons/ideal_logo.svg') }}"/>
                            </span>
                            <div class="break" style="flex-basis: 100%; height: 0;"></div>
                            <div class="JS--banksContainer" style="display: none; margin-top: 20px">
                                <div class="form-group form-inline" style="display: contents">
                                    Bank:
                                    <select class="form-control" id="bank" name="bank">
                                        <?php readfile("https://transaction.digiwallet.nl/ideal/getissuers?ver=4&format=html"); ?>
                                    </select>
                                </div>
                            </div>
                        </li>
                        {{--<li class="list-group-item d-flex justify-content-between" style="margin-bottom: 10px">
                            <label class="paymentMethodsLabel">
                                <input type="radio" name="paymentMethod" value="credit">
                                <span style="margin-left: 5px">Visa/MasterCard</span>
                            </label>
                            <span class="paymentMethodLogo"><img src="{{ asset('img/icons/credit-cards.png') }}"/></span>
                        </li>--}}
                        <li class="list-group-item d-flex justify-content-between">
                            <label class="paymentMethodsLabel">
                                <input type="radio" name="paymentMethod" value="paysafe">
                                <span style="margin-left: 5px">Paysafecard</span>
                            </label>
                            <span class="paymentMethodLogo"><img style="height: 29px" src="{{ asset('img/icons/paysafecard_logo.svg') }}"/></span>
                        </li>
                    </ul>
                </div>
                <div class="col-md-6">
                    <h4>&euro; {{ @trans('credits.cart') }}</h4>
                    <ul class="list-group mb-3">
                        <li class="list-group-item d-flex justify-content-between">
                            <div>
                                <h6 class="my-0"><span class="cart-package">{{ $creditpacks[1]->name }}</span> {{ @trans('credits.package') }}
                                </h6>
                                <small class="text-muted"><span
                                        class="cart-credits">{{ $creditpacks[1]->credits }}</span> {{ @trans('credits.credits') }}</small>
                                <input name="creditpack_id" type="hidden" value="{{ $creditpacks[1]->id }}">
                            </div>
                            <span class="text-muted"><span
                                    class="cart-value">{{ $creditpacks[1]->price/100 }}</span>€</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span>{{ @trans('credits.total') }}:</span>
                            <strong><span class="cart-value">{{ $creditpacks[1]->price/100 }}</span>€</strong>
                        </li>
                    </ul>
                    <button class="btn btn-primary btn-lg btn-block" type="submit">{{ @trans('credits.to_checkout') }}</button>
                </div>
            </form>
        </div>

        <div class="row mt-5 recent-container text-center currentMembers pricing-10">
            <h3 style="margin-bottom: 30px">{{ @trans('credits.they_are_waiting') }}</h3>
            @foreach ($users as $user)
                <div class="col-xs-12 col-sm-6 col-md-4 col-lg-2">
                    <a href="{{ route('users.show', ['username' => $user->getUsername()]) }}">
                        <img
                            src="{{ \StorageHelper::profileImageUrl($user, true) }}"
                            class="profileImage"
                            alt=""
                        >
                    </a>
                    <a href="{{ route('users.show', ['username' => $user->getUsername()]) }}" style="text-decoration: none">
                        <h5 class="small" style="margin-bottom: 0">{{ $user->username }}</h5>
                    </a>

                    <p class="offset-0" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis">
                        <small class="text-muted">{{ @trans('credits.age') }}: {{ $user->meta->dob->diffInYears($carbonNow) }}, {{ $user->meta->city }}</small>
                    </p>
{{--                    <a href="{{ route('users.show', ['username' => $user->getUsername()])  }}"--}}
{{--                       class="btn btn-lg btn-white">{{ @trans('credits.more_info') }}</a>--}}
                </div>
            @endforeach
        </div>
    </div>

@endsection
