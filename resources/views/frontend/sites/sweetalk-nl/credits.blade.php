@extends('frontend.layouts.default.layout')

@section('content')
    <div class="credits-page-content">
        <h4 class="stepHeading"><span class="step">1</span><span class="stepTitle">{{ trans(config('app.directory_name') . '/credits.select_package') }}</span></h4>
        <div class="pricing-3">
            <div class="creditsPacks">
                <div class="row">
                    @foreach($creditpacks as $creditpack)
                        @if($creditpack->id !== 4 || $authenticatedUser->isAdmin() || config('app.env') === 'staging')
                            <div class="col-xs-12 {{ $authenticatedUser->getHasNewPricing() ? 'col-md-5ths' : 'col-md-3' }}">
                                <div data-creditpack-id="{{ $creditpack->id }}"
                                     class="block block-pricing {{ $loop->iteration == 3 && $authenticatedUser->getHasNewPricing() ? 'block-raised' : '' }} {{ $loop->iteration == 2 && !$authenticatedUser->getHasNewPricing() ? 'block-raised' : '' }} JS--creditpack"
                                >
                                    <div class="table {{ $loop->iteration == 3 && $authenticatedUser->getHasNewPricing() ? 'table-rose' : '' }} {{ $loop->iteration == 2 && !$authenticatedUser->getHasNewPricing() ? 'table-rose' : '' }}">
                                        <h6 class="category">{{ $creditpack->name }}</h6>
                                        <h1 class="block-caption {{ $authenticatedUser->getHasNewPricing() ? 'newPricing' : '' }}"><b class="package-credits">{{ $creditpack->credits }}</b> {{ trans(config('app.directory_name') . '/credits.credits') }}
                                        </h1>
                                        <ul>
                                            <li>
                                                <small>€</small>
                                                <span class="{{ !$authenticatedUser->getDiscountPercentage() ? 'JS--price' : 'normalPrice' }}">
                                                    {{ number_format($creditpack->price / 100, 2, ',', '.') }}
                                                </span>

                                                @if($authenticatedUser->getDiscountPercentage())
                                                    <span class="JS--price discountPrice">{{ number_format((1 - $authenticatedUser->getDiscountPercentage() / 100) * $creditpack->price / 100, 2, ',', '.') }}</span>
                                                @endif
                                            </li>
                                            <li>
                                                {{ ucfirst(trans(config('app.directory_name') . '/credits.per_message')) }}:
                                                <b>
                                                    &euro;
                                                    <span class="{{ !$authenticatedUser->getDiscountPercentage() ? '' : 'normalPrice' }}">{{ number_format($creditpack->price/$creditpack->credits / 100, 2, ',', '.') }}</span>

                                                    @if($authenticatedUser->getDiscountPercentage())
                                                        <span class="discountPrice">{{ number_format((1 - $authenticatedUser->getDiscountPercentage() / 100)*$creditpack->price/$creditpack->credits / 100, 2, ',', '.') }}</span>
                                                    @endif
                                                </b>
                                            </li>
                                        </ul>

                                        <a
                                            href="#"
                                            class="btn {{ $loop->iteration == 3 && $authenticatedUser->getHasNewPricing() ? 'btn-white' : 'btn-rose' }} {{ $loop->iteration == 2 && !$authenticatedUser->getHasNewPricing() ? 'btn-white' : 'btn-rose' }} btn-round JS--prevent-default__click"
                                        >
                                            @if($creditpack->id === \App\Creditpack::MEDIUM)
                                                {{ trans(config('app.directory_name') . '/credits.i_choose') }} medium
                                            @else
                                                {{ trans(config('app.directory_name') . '/credits.further') }}
                                            @endif
                                        </a>

                                        @if(!$authenticatedUser->getHasNewPricing() && $creditpack->id === 2 || $authenticatedUser->getHasNewPricing() && $creditpack->id === 8)
                                            <div class="usp-label most-popular {{ $authenticatedUser->getHasNewPricing() ? 'newPricing' : '' }}">
                                                {{ trans(config('app.directory_name') . '/credits.most_popular') }}
                                            </div>
                                        @elseif(!$authenticatedUser->getHasNewPricing() && $creditpack->id === 5 || $authenticatedUser->getHasNewPricing() && $creditpack->id === 10)
                                            <div class="usp-label best-value {{ $authenticatedUser->getHasNewPricing() ? 'newPricing' : '' }}">
                                                {{ trans(config('app.directory_name') . '/credits.best_value') }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
        <div class="row pricing-10">
            <form method="post" action="{{ route('credits.store') }}">
                {{ csrf_field() }}

                <div class="col-xs-12 col-md-12">
                    <h4 class="JS--paymentMethods__title stepHeading"><span class="step">2</span><span class="stepTitle">{{ trans(config('app.directory_name') . '/credits.select') }}</span></h4>
                    <ul class="list-group mb-3 JS--paymentMethods paymentMethodsList">
                        <li class=" Tile list-group-item d-flex justify-content-between paymentMethodsListItem JS--paymentMethodListItem"
                            style="margin-bottom: 10px; flex-wrap: wrap;">
                            <label class="paymentMethodsLabel">
                                <input type="radio" name="paymentMethod" value="ideal" required>
                                <span style="margin-left: 5px">iDeal</span>
                            </label>
                            <span class="paymentMethodLogo">
                                <img src="{{ asset('img/icons/ideal_logo.svg') }}"/>
                            </span>
                            <div class="break" style="flex-basis: 100%; height: 0;"></div>
                            <div class="JS--banksContainer" style="display: none; margin-top: 20px">
                                <div class="form-group form-inline" style="display: contents">
                                    Bank:
                                    <select class="form-control" id="bank" name="bank">
                                        {{ \App\Helpers\PaymentsHelper::banksSelectOptions() }}
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
                        <li class="Tile list-group-item d-flex justify-content-between paymentMethodsListItem JS--paymentMethodListItem" style="margin-bottom: 10px">
                            <label class="paymentMethodsLabel">
                                <input type="radio" name="paymentMethod" value="bancontact">
                                <span style="margin-left: 5px">Bancontact</span>
                            </label>
                            <span class="paymentMethodLogo"><img src="{{ asset('img/icons/bancontact_logo.svg') }}"/></span>
                        </li>
                        <li class="Tile list-group-item d-flex justify-content-between paymentMethodsListItem JS--paymentMethodListItem">
                            <label class="paymentMethodsLabel">
                                <input type="radio" name="paymentMethod" value="paysafe">
                                <span style="margin-left: 5px">Paysafecard</span>
                            </label>
                            <span class="paymentMethodLogo"><img src="{{ asset('img/icons/paysafecard_logo.svg') }}"/></span>
                        </li>
                    </ul>
                </div>
                <div class="col-xs-12 col-md-12">
                    <h4 class="stepHeading"><span class="step">3</span><span class="stepTitle JS--finalizePaymentTitle">{{ trans(config('app.directory_name') . '/credits.cart') }}</span></h4>
                    <ul class="Tile list-group mb-3">
                        <li class="list-group-item d-flex justify-content-between">
                            <div>
                                <h6 class="my-0"><span class="cart-package">{{ $creditpacks[$authenticatedUser->getHasNewPricing() ? 2 : 1]->name }}</span> {{ trans(config('app.directory_name') . '/credits.package') }}
                                </h6>
                                <small class="text-muted"><span
                                        class="cart-credits">{{ $creditpacks[$authenticatedUser->getHasNewPricing() ? 2 : 1]->credits }}</span> {{ trans(config('app.directory_name') . '/credits.credits') }}</small>
                                <input name="creditpack_id" type="hidden" value="{{ $creditpacks[$authenticatedUser->getHasNewPricing() ? 2 : 1]->id }}">
                            </div>
                            <span class="text-muted"><span
                                    class="cart-value">&euro;{{ number_format($creditpacks[$authenticatedUser->getHasNewPricing() ? 2 : 1]->price / 100, 2, ',', '.') }}</span></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span>{{ trans(config('app.directory_name') . '/credits.total') }}:</span>
                            <strong><span class="cart-value">&euro;{{ number_format($creditpacks[$authenticatedUser->getHasNewPricing() ? 2 : 1]->price / 100, 2, ',', '.') }}</span></strong>
                        </li>
                    </ul>
                    <button class="Button btn btn-primary btn-lg btn-block" type="submit">
                        <i class="material-icons paymentButtonCheck">
                            check_circle
                        </i>
                        {{ trans(config('app.directory_name') . '/credits.to_checkout') }}
                    </button>
                </div>
            </form>
        </div>

        <div class="row mt-5 recent-container text-center currentMembers pricing-10">
            @foreach ($users as $user)
                <div class="col-xs-12 col-sm-6 col-md-4 col-lg-2">
                    <a href="{{ route('users.show', ['username' => $user->getUsername()]) }}">
                        <img
                            src="{{ $user->profileImageUrlThumb }}"
                            class="profileImage"
                            alt=""
                        >
                    </a>
                    <a href="{{ route('users.show', ['username' => trim($user->getUsername())]) }}" style="text-decoration: none">
                        <h5 class="small" style="margin-bottom: 0">{{ $user->username }}{{ $user->meta->dob ? ', ' . $user->meta->dob->diffInYears($carbonNow) : '' }}</h5>
                    </a>

                    <p class="offset-0" style="white-space: nowrap; line-height: 18px; overflow: hidden; text-overflow: ellipsis">
                        <small class="text-muted">{{ $user->meta->city }}</small>
                    </p>
                    {{--                    <a href="{{ route('users.show', ['username' => $user->getUsername()])  }}"--}}
                    {{--                       class="btn btn-lg btn-white">{{ trans(config('app.directory_name') . '/credits.more_info') }}</a>--}}
                </div>
            @endforeach
        </div>
    </div>

@endsection
