@extends("layouts/app")

@section("content")

<div class="row mt-5">
    <div class="col-12 mb-5">
        <h2>Checkout:</h2>
        <h4>Total count {{ $totalPrice }} €</h4>
    </div>

    @if(Session::has("cart"))
    <div class="col-12 col-md-6">
        {!! Form::open(["action" => "ArtworksController@checkout", "method" => "POST", "id" => "checkout-form", "onsubmit" => "event.preventDefault();"]) !!}

        <div class="form-group">
            {{Form::label("name", "Name:")}}
            {{Form::text("name", "", ["class" => "form-control", "placeholder" => "", "required" => true])}}
        </div>

        <div class="form-group">
            {{Form::label("surname", "Surname:")}}
            {{Form::text("surname", "", ["class" => "form-control", "placeholder" => "", "required" => true])}}
        </div>

        <div class="form-group">
            {{Form::label("address", "Address:")}}
            {{Form::text("address", "", ["class" => "form-control", "placeholder" => "", "required" => true])}}
        </div>

        <div class="form-group">
            {{Form::label("card-number", "Credit card number:")}}
            {{Form::text("card-number", "", ["class" => "form-control", "placeholder" => "", "required" => true])}}
        </div>

        <div class="form-group">
            {{Form::label("card-expiry-month", "Card expiration month:")}}
            {{Form::text("card-expiry-month", "", ["class" => "form-control", "placeholder" => "", "required" => true])}}
        </div>

        <div class="form-group">
            {{Form::label("card-expiry-year", "Card expiration year:")}}
            {{Form::text("card-expiry-year", "", ["class" => "form-control", "placeholder" => "", "required" => true])}}
        </div>

        <div class="form-group">
            {{Form::label("card-cvc", "CVC:")}}
            {{Form::text("card-cvc", "", ["class" => "form-control", "placeholder" => "", "required" => true])}}
        </div>

        {{Form::submit("Buy now", ["class" => "btn btn-primary", "id" => "checkout-button"])}}

        <p id="charge-error" class="text-danger {{ !Session::has('error') ? 'hidden' : '' }}">
            {{ Session::get("error") }}
        </p>

        {!! Form::close() !!}
    </div>
    @else
    <div class="col-12">
        <p>Your chart is empty!</p>
    </div>
    @endif


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

</script>

@endsection