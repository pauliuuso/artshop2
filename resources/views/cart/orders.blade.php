@extends("layouts/app")

@section("content")

<div class="row mt-7">
    <div class="col-12 mb-2 mb-sm-5 main-title">
        <h1 class="marvel">ORDERS</h1>
    </div>


    @if(count($orders) > 0 && $orders != null)

        @foreach($orders as $order)
            <div class="col-12 mb-2 pt-2 pb-2 order">
                <p>Customer: {{$order->name}} {{$order->surname}} ({{$order->created_at}})</p>
                <p>Address: {{$order->address}}</p>
                <p>Payment id: <a href="https://dashboard.stripe.com/test/payments/{{$order->payment_id}}">{{$order->payment_id}}</a></p>

                @foreach($order->cart->artworks as $artwork)

                    <div class="row mb-5">
                        <div class="col-6 col-xl-3">
                            <img class="img-fluid visibility-hidden" src="/storage/artworks/{{$artwork['artwork']->picture_name}}" onload="DisplayImage(this)"/>
                        </div>
                        <div class="col-6">
                            <p>Title: {{$artwork["artwork"]->title}}</p>
                            <p>Quantity: {{ $artwork["count"] }}</p>
                            <p>Price: {{ $artwork["price"] }} €</p>
                            <p>Size: {{ $artwork["size"] }} cm</p>
                        </div>
                    </div>

                @endforeach

                <p>Total price: {{$order->cart->getFullPrice()}} €</p>

            </div>
        @endforeach
        
        {{$orders->links()}}

    @else
        <div class="col-12">
            <p>Nothing found!</p>
        </div>
    @endif


</div>

@endsection