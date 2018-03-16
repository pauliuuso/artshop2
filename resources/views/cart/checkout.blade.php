@extends("layouts/app")

@section("content")

<div class="row mt-5">
    <div class="col-12 mb-5">
        <h2>Checkout:</h2>
        <h4>Total count {{ $totalPrice }} €</h4>
    </div>

    @if(Session::has("cart"))
    <div class="col-12 col-md-6">
        {!! Form::open(["action" => "ArtworksController@checkout", "method" => "POST"]) !!}

        <div class="form-group">
            {{Form::label("name", "Name:")}}
            {{Form::text("name", "", ["class" => "form-control", "placeholder" => ""])}}
        </div>

        <div class="form-group">
            {{Form::label("surname", "Surname:")}}
            {{Form::text("surname", "", ["class" => "form-control", "placeholder" => ""])}}
        </div>

        <div class="form-group">
            {{Form::label("address", "Address:")}}
            {{Form::text("address", "", ["class" => "form-control", "placeholder" => ""])}}
        </div>

        <div class="form-group">
            {{Form::label("card-number", "Credit card number:")}}
            {{Form::text("card-number", "", ["class" => "form-control", "placeholder" => ""])}}
        </div>

        <div class="form-group">
            {{Form::label("expiration-month", "Card expiration month:")}}
            {{Form::text("expiration-month", "", ["class" => "form-control", "placeholder" => ""])}}
        </div>

        <div class="form-group">
            {{Form::label("expiration-year", "Card expiration year:")}}
            {{Form::text("expiration-year", "", ["class" => "form-control", "placeholder" => ""])}}
        </div>

        <div class="form-group">
            {{Form::label("cvc", "CVC:")}}
            {{Form::text("cvc", "", ["class" => "form-control", "placeholder" => ""])}}
        </div>

        {{Form::submit("Buy now", ["class" => "btn btn-primary"])}}
        {!! Form::close() !!}
    </div>
    @else
    <div class="col-12">
        <p>Your chart is empty!</p>
    </div>
    @endif


</div>

@endsection