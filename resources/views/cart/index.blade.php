@extends("layouts/app")

@section("content")

<div class="row mt-5">
    <div class="col-12 mb-5">
        <h2>Your shopping cart:</h2>
    </div>

    @if(Session::has("cart"))
    <div class="col-12">
        <div class="row">
            @foreach($artworks as $artwork)

                <div class="col-12 mb-5">
                    <div class="row">

                        <div class="col-12">
                            <h2>{{ $artwork["artwork"]->title }}</h2>
                        </div>

                        <div class="col-6 col-xl-3">
                            <img id="artwork-image" class="img-fluid visibility-hidden" src="/storage/artworks/{{$artwork['artwork']->picture_name}}" onload="DisplayImage(this)"/>
                        </div>

                        <div class="col-6 col-xl-3">
                            <p>Quantity: {{ $artwork["count"] }}</p>
                            <p>Price: {{ $artwork["price"] }} €</p>
                            <p>Size: {{ $artwork["size"] }} cm</p>
                        </div>

                    </div>
                </div>

            @endforeach

            <div class="col-12 mb-5">
                <p>Total price: {{ $totalPrice }} €</p>
                <a class="button-a p-2" href="/checkout"><p>CHECKOUT</p></a>
            </div>

        </div>
    </div>
    @else
    <div class="col-12">
        <p>Your chart is empty!</p>
    </div>
    @endif


</div>

@endsection