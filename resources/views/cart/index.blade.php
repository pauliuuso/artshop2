@extends("layouts/app")

@section("content")

<div class="row mt-5">
    <div class="col-12 mb-5">
        <a class="float-right btn btn-danger {{!Session::has('cart') ? 'visibility-hidden' : ''}}" href="/remove-all-from-cart">Clear all</a>
        <h2>Your shopping cart:</h2>
    </div>

    @if(Session::has("cart"))
        @foreach($artworks as $index => $artwork)

        <div class="col-12 mb-5">
            <div class="row">

                <div class="col-12">
                    <a href="/remove-from-cart/{{$index}}">
                        <i class="fas fa-times cart-remove-item"></i>
                    </a>
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
    @else
    <div class="col-12">
        <p>Your chart is empty!</p>
    </div>
    @endif


</div>

@endsection