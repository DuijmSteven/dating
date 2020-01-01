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
                                        <li><b>{{ round($creditpack->price/$creditpack->credits, 2) }}</b> {{ trans('credits.per_message') }}
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
                    <ul class="list-group mb-3 JS--paymentMethods">
                        <li class="list-group-item d-flex justify-content-between"
                            style="margin-bottom: 10px; flex-wrap: wrap;">
                            <div>
                                <label><input type="radio" name="paymentMethod" value="ideal" required> <span
                                        style="margin-left: 5px">iDeal</span></label>
                            </div>
                            <span class="text-muted"><img src="{{ asset('img/icons/ideal.png') }}"/></span>
                            <div class="break" style="flex-basis: 100%; height: 0;"></div>
                            <div class="JS--banksContainer" style="display: none; margin-top: 20px">
                                <div class="form-group form-inline" style="display: contents">
                                    Bank:
                                    <select class="form-control" id="bank" name="bank">
                                        <option value="ABNANL2A">ABN AMRO</option>
                                        <option value="ASNBNL21">ASN BANK</option>
                                        <option value="BUNQNL2A">BUNQ</option>
                                        <option value="INGBNL2A">ING</option>
                                        <option value="KNABNL2H">KNAB</option>
                                        <option value="RABONL2U">RABOBANK</option>
                                        <option value="RBRBNL21">REGIOBANK</option>
                                        <option value="SNSBNL2A">SNS Bank</option>
                                        <option value="TRIONL2U">Triodos Bank</option>
                                        <option value="FVLBNL22">van Lanschot</option>
                                    </select>
                                </div>
                            </div>
                        </li>
                        {{--<li class="list-group-item d-flex justify-content-between" style="margin-bottom: 10px">
                            <div>
                                <label><input type="radio" name="paymentMethod" value="credit"> <span
                                        style="margin-left: 5px">Visa/MasterCard</span></label>
                            </div>
                            <span class="text-muted"><img src="{{ asset('img/icons/credit-cards.png') }}"/></span>
                        </li>--}}
                        <li class="list-group-item d-flex justify-content-between">
                            <div>
                                <label><input type="radio" name="paymentMethod" value="paysafe"> <span
                                        style="margin-left: 5px">Paysafecard</span></label>
                            </div>
                            <span class="text-muted"><img src="{{ asset('img/icons/paysafecard.png') }}"/></span>
                        </li>
                    </ul>
                </div>
                <div class="col-md-6">
                    <h4>Cart</h4>
                    <ul class="list-group mb-3">
                        <li class="list-group-item d-flex justify-content-between">
                            <div>
                                <h6 class="my-0"><span class="cart-package">{{ $creditpacks[1]->name }}</span> package
                                </h6>
                                <small class="text-muted"><span
                                        class="cart-credits">{{ $creditpacks[1]->credits }}</span> credits</small>
                                <input name="creditpack_id" type="hidden" value="{{ $creditpacks[1]->id }}">
                            </div>
                            <span class="text-muted"><span
                                    class="cart-value">{{ $creditpacks[1]->price/100 }}</span>€</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Total (EUR)</span>
                            <strong><span class="cart-value">{{ $creditpacks[1]->price/100 }}</span>€</strong>
                        </li>
                    </ul>
                    <button class="btn btn-primary btn-lg btn-block" type="submit">Continue to checkout</button>
                </div>
            </form>
        </div>

        <div class="row mt-5 recent-container text-center currentMembers pricing-10">
            <h3>They are waiting for you...</h3>
            @foreach ($users as $user)
                <div class="col-lg-2 col-md-4 col-sm-12">
                    <a href="{{ route('users.show', ['username' => $user->getUsername()]) }}">
                        <div class="imageWrapper">
                            <img
                                src="{{ \StorageHelper::profileImageUrl($user, true) }}"
                                class="img-circle img-wide profileImage"
                                alt=""
                            >
                        </div>
                    </a>
                    <h5 class="small">{{ $user->username }}</h5>
                    <p class="offset-0">
                        <small class="text-muted">Age: {{ $user->meta->dob->diffInYears($carbonNow) }}
                            , {{ $user->meta->city }}</small>
                    </p>
                    <a href="{{ route('users.show', ['username' => $user->getUsername()])  }}"
                       class="btn btn-lg btn-white">More
                        Info</a>
                </div>
            @endforeach
        </div>
    </div>

@endsection
