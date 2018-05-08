@extends("layouts/checkout")

@section("content")

<div class="row">

    <div class="col-12 col-md-6 col-lg-7 mt-9">
        <div class="row">
            <div class="col-12 col-lg-10 col-xl-9">
                <h4 class="text-uppercase main-title-sub">CHECKOUT</h4>
                <a class="text-underline" href="/get-cart">BACK TO SHOPPING CART</a>

                <div>
                    @if(Session::has("cart"))
                        {!! Form::open(["action" => "CheckoutController@postcheckoutaddress", "method" => "POST", "id" => "checkout-form"]) !!}
                
                        <div class="checkout-1 mt-6">
                            <p>CONTACT INFORMATION:</p>
                            <div class="form-group">
                                {{Form::text("name", $order->name, ["class" => "form-control", "placeholder" => "Name"])}}
                            </div>
                    
                            <div class="form-group">
                                {{Form::text("surname", $order->surname, ["class" => "form-control", "placeholder" => "Surname"])}}
                            </div>
    
                            <div class="form-group">
                                {{Form::email("email", $order->email, ["class" => "form-control", "placeholder" => "Email"])}}
                            </div>
                    
                            <p class="mt-6">SHIPPING ADDRESS:</p>
                            <div class="form-group">
                                {{Form::text("name_shipping", $order->name_shipping, ["class" => "form-control", "placeholder" => "Name"])}}
                            </div>
                    
                            <div class="form-group">
                                {{Form::text("surname_shipping", $order->surname_shipping, ["class" => "form-control", "placeholder" => "Surname"])}}
                            </div>
    
                            <div class="row">
                                <div class="form-group col-12 col-sm-8">
                                    {{Form::text("address", $order->address, ["class" => "form-control", "placeholder" => "Address"])}}
                                </div>
        
                                <div class="form-group col-12 col-sm-4">
                                    {{Form::text("apartment", $order->apartment, ["class" => "form-control", "placeholder" => "Apartment / Suite"])}}
                                </div>

                                <div class="form-group col-12 col-sm-8">
                                    {{Form::select("country", $countries, $order->country, ["class" => "col-12"])}}
                                </div>

                                <div class="form-group col-12 col-sm-4">
                                    {{Form::text("postal_code", $order->postal_code, ["class" => "form-control", "placeholder" => "Postal code"])}}
                                </div>
                            </div>
    
                            <div class="form-group">
                                {{Form::text("phone", $order->phone, ["class" => "form-control", "placeholder" => "Phone"])}}
                            </div>
    
                            <p class="m-0">SAVE THIS INFORMATION FOR THE NEXT TIME <input type="checkbox" id="save_info" class="m-0 ml-2 mb-3"></p>
                            <a class="text-underline" href="/get-cart">BACK TO SHOPPING CART</a>
    
                            <div class="row mt-4 mb-5">
                                <div class="col-12 text-right">
                                    {{Form::submit("Continue", ["class" => "artshop-button", "id" => "checkout-button"])}}
                                </div>
                            </div>

                        </div>

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

{{-- <script>

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
            name: $('#name').val()
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

</script> --}}

@endsection