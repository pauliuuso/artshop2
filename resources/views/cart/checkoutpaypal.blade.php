@extends("layouts/checkout")

@section("content")

<div class="row">

    <div class="col-12 col-md-6 col-lg-7 mt-9">
        <div class="row">
            <div class="col-12 col-lg-10 col-xl-9">
                <h4 class="text-uppercase main-title-sub">CHECKOUT</h4>
                <a class="text-underline" href="/get-cart">BACK TO SHOPPING CART</a>
                <p>Order id: {{ Session::get("cart")->orderId}}</p>

                <div>
                    @if(Session::has("cart"))
                    {!! Form::open(["action" => "ArtworksController@completecheckoutpaypal", "method" => "POST", "id" => "checkout-form", "novalidate" => "novalidate"]) !!}

                        <div class="checkout-2 mt-6">

                            <p>PAYMENT METHOD:</p>

                            <a href="/checkoutpayment/paypal">
                                <div class="payment-method mb-2 p-2 {{$type == 'paypal' ? 'active' : ''}}">
                                    PAYPAL
                                </div>
                            </a>

                            <a href="/checkoutpayment/transfer">
                                <div class="payment-method mb-2 p-2 {{$type == 'transfer' ? 'active' : ''}}">
                                    BANK TRANSFER
                                </div>
                            </a>

                            <a href="/checkoutpayment/credit">
                                <div class="payment-method mb-2 p-2 {{$type == 'credit' ? 'active' : ''}}">
                                    CREDIT CARD
                                </div>
                            </a>

                            <p class="mt-6">PayPal</p>
                            <p>Total: {{ Session::get('cart')->totalPrice + 4.99 }}</p>

                            {{Form::hidden("price", Session::get('cart')->totalPrice)}}

                            {{Form::hidden("payment_type", $type)}}

                            <div class="row mt-4 mb-5">
                                <div class="col-6 text-left pt-3">
                                    <a href="/checkoutaddress" class="artshop-button-a">BACK</a>
                                </div>
                                <div class="col-6 text-right">
                                    {{Form::submit("PURCHASE", ["class" => "artshop-button mb-2", "id" => "checkout-button"])}}
                                    <p class="text-small">YOU WILL BE REDIRECTED TO PAYPAL</p>
                                </div>
                            </div>

                        </div>
                    
                        <p id="charge-error" class="text-danger {{ !Session::has('error') ? 'hidden' : '' }}">
                            {{ Session::get("error") }}
                        </p>
            
                    {!! Form::close() !!}
                    @else
                    <div class="col-12">
                        <p>Your chart is empty!</p>
                    </div>
                    @endif
                </div>
                
            </div>
        </div>
    </div>

    @include("inc/checkoutright")

</div>

@endsection