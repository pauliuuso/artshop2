<div class="col-12 col-md-6 col-lg-5 checkout-right pl-5">
        <div class="row">

            <div class="col-12 mt-9">

                <h4 class="text-uppercase main-title-sub">ITEMS</h4>
                <a class="text-underline" href="/get-cart">BACK TO SHOPPING CART</a>

                <div class="row mt-5">
                    <div class="col-6">
                        ITEM
                    </div>
                    <div class="col-6 text-right">
                        SUMMARY
                    </div>
                </div>
                <div class="purchase-beam"></div>

            </div>

            <div class="col-12">
                <div class="row">
                    @if(Session::has("cart"))

                        @foreach($artworks as $index => $artwork)
    
                            <div class="col-4 mt-3">
                                <div class="count-circle">{{ $artwork["count"] }}</div>
                                <img class="visibility-hidden size-full" src="/storage/artworks/{{$artwork['artwork']->picture_name}}" onload="DisplayImage(this)">
                                <p class="m-0 mt-2">{{ $artwork["artwork"]->getAuthor->name }} {{ $artwork["artwork"]->getAuthor->surname }}</p>
                                <p>{{ $artwork["artwork"]->title }}, {{ $artwork["artwork"]->year }}</p>
                            </div>
                            <div class="col-8 mt-3 text-right">
                                <p>Paper: Quality paper</p>
                                <p>Frame: No frame</p>
                                <p>Size: {{ $artwork["size"] }} cm.</p>
                                <p>Price: {{ $artwork["price"] }} €</p>
                            </div>
    
                        @endforeach
                    
                    @endif
                </div>
            </div>

            <div class="col-12 mt-2 mb-5">

                <div class="purchase-beam"></div>

                <div class="row mt-4">
                    <div class="col-6">
                        <b>SUBTOTAL</b>
                    </div>
                    <div class="col-6 text-right">
                        <b>{{ Session::get("cart")->totalPrice }} €</b>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-6">
                        <b>SHIPPING</b>
                    </div>
                    <div class="col-6 text-right">
                        <b>4.99 €</b>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-6">
                        <b>GRANDTOTAL</b>
                    </div>
                    <div class="col-6 text-right">
                        <b>{{ $artwork["price"] + 4.99 }} €</b>
                    </div>
                </div>

            </div>

        </div>
    </div>