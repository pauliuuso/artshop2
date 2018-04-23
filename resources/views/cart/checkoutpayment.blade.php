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
                    {!! Form::open(["action" => "ArtworksController@completecheckout", "method" => "POST", "id" => "checkout-form", "onsubmit" => "event.preventDefault();", "novalidate" => "novalidate"]) !!}

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

                            <p class="mt-6">CREDIT CARD INFORMATION:</p>

                            {{Form::select("card_type", ["Maestro" => "Maestro", "Visa" => "Visa"], "", ["class" => "col-12"])}}

                            {{Form::text("name_credit", "", ["class" => "form-control", "placeholder" => "Name"])}}

                            {{Form::text("surname_credit", "", ["class" => "form-control", "placeholder" => "Surname"])}}

                            {{Form::text("card-number", "", ["class" => "form-control", "placeholder" => "Card Number e.g 4242 4242 4242 4242"])}}

                            <div class="row">
                                <div class="col-6">
                                    {{Form::text("card-expiry-month", "", ["class" => "form-control", "placeholder" => "Expiration month"])}}
                                </div>
                        
                                <div class="col-6">
                                    {{Form::text("card-expiry-year", "", ["class" => "form-control", "placeholder" => "Expiration year"])}}
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-6">
                                    {{Form::text("card-cvc", "", ["class" => "form-control", "placeholder" => "CVC"])}}
                                </div>
                            </div>

                            <div class="row mt-4 mb-5">
                                <div class="col-6 text-left pt-3">
                                    <a href="/checkoutaddress" class="artshop-button-a">BACK</a>
                                </div>
                                <div class="col-6 text-right">
                                    {{Form::submit("PURCHASE", ["class" => "artshop-button mb-2", "id" => "checkout-button"])}}
                                    <p class="text-small">THIS IS THE FINAL STEP</p>
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

<script>

    Stripe.setPublishableKey('pk_test_E3REl5rEcxp7FJePFQkyyjpA');

    var $form = $("#checkout-form");

    $form.submit(function(event)
    {

        $("#charge-error").addClass("hidden");
        $("#checkout-button").prop("disabled", true);

        Stripe.card.createToken
        ({
            number: $('#card-number').val(),
            cvc: $('#card-cvc').val(),
            exp_month: $('#card-expiry-month').val(),
            exp_year: $('#card-expiry-year').val(),
            name: $('#name_credit').val()
        }, stripeResponseHandler);

        return false;
    });

    function stripeResponseHandler(status, response)
    {

        if(response.error)
        {
            $("#charge-error").removeClass("hidden");
            $("#charge-error").html(response.error.message);
            $("#checkout-button").prop("disabled", false);
        }
        else
        {
            var token = response.id;
            $form.append($("<input type='hidden' name='stripeToken'>").val(token));
            $form.get(0).submit();
        }

    }

</script>

@endsection