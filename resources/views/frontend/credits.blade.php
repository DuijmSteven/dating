@extends('frontend.layouts.default.layout')

@section('content')
    <h4>Select a package</h4>
    <div class="pricing-3">
        <div>
            <div class="row">

                @foreach($creditpacks as $creditpack)
                    <div class="col-md-4">
                        <div class="block block-pricing {{ $loop->iteration == 2 ? 'block-raised' : '' }}">
                            <div class="table {{ $loop->iteration == 2 ? 'table-rose' : '' }}">
                                <h6 class="category">{{ $creditpack->name }}</h6>
                                <h1 class="block-caption"><small>€</small><span>{{ $creditpack->price/100 }}</span></h1>
                                <ul>
                                    <li><b class="package-credits">{{ $creditpack->credits }}</b> credits</li>
                                    <li><b>{{ round($creditpack->price/$creditpack->credits, 2) }}</b> per message</li>
                                </ul>

                                <a
                                    href="#"
                                    class="btn {{ $loop->iteration == 2 ? 'btn-white' : 'btn-rose' }} btn-round JS--prevent-default__click"
                                >
                                    Select
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach

            </div>
        </div>
    </div>
    <div class="row pricing-10">
        <form method="POST" action="">
            {{ csrf_field() }}
            <div class="col-md-6">
                <h4>Payment methods</h4>
                <ul class="list-group mb-3 JS--paymentMethods">
                    <li class="list-group-item d-flex justify-content-between lh-condensed" style="margin-bottom: 10px">
                        <div>
                            <label for=""><input type="radio" name="payment-method" value="ideal"> <span style="margin-left: 5px">iDeal</span></label>
                        </div>
                        <span class="text-muted"><img src="{{ asset('img/icons/ideal.png') }}" /></span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between lh-condensed" style="margin-bottom: 10px">
                        <div>
                            <label><input type="radio" name="payment-method" value="credit-card"> <span style="margin-left: 5px">Visa/MasterCard</span></label>
                        </div>
                        <span class="text-muted"><img src="{{ asset('img/icons/credit-cards.png') }}" /></span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between lh-condensed">
                        <div>
                            <label><input type="radio" name="payment-method" value="paysafecard"> <span style="margin-left: 5px">Paysafecard</span></label>
                        </div>
                        <span class="text-muted"><img src="{{ asset('img/icons/paysafecard.png') }}" /></span>
                    </li>
                </ul>
            </div>
            <div class="col-md-6">
                <h4>Cart</h4>
                <ul class="list-group mb-3">
                    <li class="list-group-item d-flex justify-content-between lh-condensed">
                        <div>
                            <h6 class="my-0"><span class="cart-package">{{ $creditpacks[1]->name }}</span> package</h6>
                            <small class="text-muted"><span class="cart-credits">{{ $creditpacks[1]->credits }}</span> credits</small>
                        </div>
                        <span class="text-muted"><span class="cart-value">{{ $creditpacks[1]->price/100 }}</span>€</span>
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

    <div class="container recent-container text-center currentMembers pricing-10">
        <h3>They are waiting for you...</h3>
        <div class="row mt-5">
            @foreach ($users as $user)
                <div class="col-lg-2 col-md-4 col-sm-12">
                    <a href="{{ route('users.show', ['userId' => $user->getId()]) }}">
                        <div class="imageWrapper">
                            <img
                                src="{{ \StorageHelper::profileImageUrl($user) }}"
                                class="img-circle img-wide profileImage"
                                alt=""
                            >
                        </div>
                    </a>
                    <h5 class="small">{{ ucfirst($user->username) }}</h5>
                    <p class="offset-0">
                        <small class="text-muted">Age: {{ $user->meta->dob->diffInYears($carbonNow) }}
                            , {{ $user->meta->city }}</small>
                    </p>
                    <a href="{{ route('users.show', ['userId' => $user->getId()])  }}" class="btn btn-primary btn-lg btn-light">More
                        Info</a>
                </div>
            @endforeach
        </div>
    </div>

@endsection
