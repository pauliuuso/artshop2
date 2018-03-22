@extends("layouts/app")

@section("content")

<div class="row cart-wrapper">

    <div class="col-12">
        <!-- <a class="float-right btn btn-danger {{!Session::has('cart') ? 'visibility-hidden' : ''}}" href="/remove-all-from-cart">Clear all</a> -->
        <h4 class="main-title-sub cart">SHOPPING CART&nbsp;&nbsp;</h4>
        <h4 class="main-title-sub total">TOTAL: {{Session::has("cart") ? $totalCount : '0'}}</h4>
    </div>

    <div class="col-12 mb-5">
        <a href="/"><p class="text-underline">BACK TO SHOP</p></a>
    </div>

    <div class="col-12 d-none d-md-block">
        <div class="row">
            <div class="col-12 col-md-6 cart-orders-item">
                <p>ITEM</p>
            </div>

            <div class="col-12 col-md-2 cart-orders-item">
                <p>YOUR OPTIONS</p>
            </div>

            <div class="col-12 col-md-2 cart-orders-item text-right">
                <p>QUANTITY</p>
            </div>

            <div class="col-12 col-md-2 cart-orders-item text-right">
                <p>ITEM PRICE</p>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="col-12 cart-orders-underline mb-3"></div>
    </div>

    @if(Session::has("cart"))

        @foreach($artworks as $index => $artwork)
            <div class="col-12">
                <div class="row">

                    <div class="col-12 col-md-6 cart-item-first">
                        <div class="size-half mt-2 mb-2">
                            <img id="artwork-image" class="visibility-hidden size-full" src="/storage/artworks/{{$artwork['artwork']->picture_name}}" onload="DisplayImage(this)"/>
                        </div>
                        <div class="size-half pl-4 mt-2">
                            <p>{{ $artwork["artwork"]->getAuthor->name }} {{ $artwork["artwork"]->getAuthor->surname }}</p>
                            <p>{{ $artwork["artwork"]->title }}, {{ $artwork["artwork"]->year }}</p>
                        </div>
                    </div>

                    <div class="col-12 col-md-2">
                        <p>Paper: Quality paper</p>
                        <p>Frame: No frame</p>
                        <p>Size: {{$artwork["size"]}}</p>
                    </div>

                    <div class="col-12 col-md-2 text-right">
                        <p><span class="d-md-none">Count: </span> {{ $artwork["count"] }}</p>
                    </div>

                    <div class="col-12 col-md-2 text-right">
                        <p><span class="d-md-none">Price: </span> {{ $artwork["price"] }} €</p>
                    </div>

                </div>
            </div>
            <div class="col-12 mb-4">
                <a href="/remove-from-cart/{{$index}}">
                    <p class="text-underline text-right">Delete</p>
                </a>
            </div>
        @endforeach

    @else
        <div class="col-12">
            <p>Your chart is empty!</p>
        </div>
    @endif

    <div class="col-12">
        <div class="col-12 cart-orders-underline mb-3"></div>
    </div>

    <div class="col-12 text-right mb-5">
        <p>TOTAL PRICE: {{Session::has("cart") ? $totalPrice : '0'}} €</p>
        <a class="customization-select-option-low" href="/checkout"><p class="p-0 m-0">CHECKOUT</p></a>
    </div>

</div>

@endsection