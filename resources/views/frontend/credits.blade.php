@extends('frontend.layouts.default.layout')

@section('content')
    <h4>Select a package</h4>
    <div class="pricing-3">
        <div>
            <div class="row">
                <div class="col-md-4">
                    <div class="block block-pricing">
                        <div class="table">
                            <h6 class="category">Small</h6>
                            <h1 class="block-caption"><small>€</small><span>29</span></h1>
                            <ul>
                                <li><b class="package-credits">150</b> credits</li>
                                <li><b>1,27</b> per message</li>
                            </ul> <a href="#" class="btn btn-rose btn-round">Select</a> </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="block block-pricing block-raised">
                        <div class="table table-rose">
                            <h6 class="category">Medium</h6>
                            <h1 class="block-caption"><small>€</small><span>49</span></h1>
                            <ul>
                                <li><b class="package-credits">300</b> credits</li>
                                <li><b>1,10</b> per message</li>
                            </ul> <a href="#" class="btn btn-white btn-round">Select</a> </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="block block-pricing">
                        <div class="table">
                            <h6 class="category">Large</h6>
                            <h1 class="block-caption"><small>€</small><span>69</span></h1>
                            <ul>
                                <li><b class="package-credits">500</b> credits</li>
                                <li><b>0,99</b> per message</li>
                            </ul> <a href="#" class="btn btn-rose btn-round">Select</a> </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row pricing-10">
        <form method="POST" action="">
            {{ csrf_field() }}
            <div class="col-md-6">
                <h4>Payment methods</h4>
                <ul class="list-group mb-3">
                    <li class="list-group-item d-flex justify-content-between lh-condensed" style="margin-bottom: 10px">
                        <div>
                            <label><input type="radio" name="payment-method" value="ideal"> <span style="margin-left: 5px">iDeal</span></label>
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
                            <h6 class="my-0"><span class="cart-package">Medium</span> package</h6>
                            <small class="text-muted"><span class="cart-credits">300</span> credits</small>
                        </div>
                        <span class="text-muted"><span class="cart-value">49</span>€</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <span>Total (EUR)</span>
                        <strong><span class="cart-value">49</span>€</strong>
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
                    <a href="{{ route('users.show', ['userId' => $user->getId()]) }}" class="imageWrapper">
                        <img
                                src="{{ \StorageHelper::profileImageUrl($user) }}"
                                class="img-circle img-wide"
                                alt=""
                        >
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
