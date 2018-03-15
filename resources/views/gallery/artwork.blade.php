@extends("layouts/app")

@section("content")

<div class="row mt-5 mb-5">

    <div class="col-12">
        @if(!Auth::guest() && Auth::user()->role == "admin")
            {!! Form::open(["action" => ["ArtworksController@destroy", $artwork->id], "method" => "POST"]) !!}
                <a href="/artwork/edit/{{$artwork->id}}" class="btn btn-success">Edit</a>
                {{Form::hidden("_method", "DELETE")}}
                {{Form::submit("Delete", ["class" => "btn btn-danger"])}}
            {!! Form::close() !!}
        @endif
    </div>

    <div class="col-12 gallery-title">
        <h1 class="marvel text-uppercase">{{$artwork->title}}</h1>
        <h4 class="text-uppercase gallery-title-author">BY {{$artwork->getAuthor->name}} {{$artwork->getAuthor->surname}}</h4>
    </div>

    <div class="col-12 col-md-6 mb-4 order-10 artwork-image-wrapper">
        <img id="artwork-image" class="img-fluid visibility-hidden" src="/storage/artworks/{{$artwork->picture_name}}" onload="ArtworkPreviewLoaded(this)"/>
    </div>

    <div class="col-12 col-md-6 text-justify order-12 order-md-11">
        <p class="text-uppercase text-underline">DESCRIPTION</p>
        {!!$artwork->description!!}

        <div class="row mt-2">
            <div class="col-12 text-justify">
                <p class="text-uppercase text-underline">AUTHOR BIO</p>
                <p>{!! $artwork->getAuthor->description !!}</p>
            </div>
        </div>
    </div>

    <div class="col-12 col-md-6 order-11 order-md-12">
        <div class="row">
            <a class="col-6 art-link artwork-selector mb-3" onclick="SelectPreview('{{$artwork->picture_name}}', this)">
                <div class="art-wrapper">
                    <img src="/storage/artworks/{{$artwork->picture_name}}" class="p-0 m-0 preview-selector-image visibility-hidden" onload="PreviewSelectorLoaded(this)"/>
                </div>
            </a>
            <a class="col-6 art-link artwork-selector mb-3" onclick="SelectPreview('{{$artwork->preview_name}}', this)">
                <div class="art-wrapper">
                    <img src="/storage/artworks/{{$artwork->preview_name}}" class="p-0 m-0 preview-selector-image visibility-hidden" onload="PreviewSelectorLoaded(this)"/>
                </div>
            </a>
        </div>
    </div>

</div>

<div class="row mb-5">
    <div class="col-12 mb-5">
        <h2 class="text-uppercase">PURCHASE</h2>
        <div class="col-12 purchase-beam"></div>
    </div>

    <div class="col-12 col-md-6 mb-5">
        <div class="artwork-frame">
            <img id="artwork-image" class="img-fluid visibility-hidden" src="/storage/artworks/{{$artwork->picture_name}}" onload="DisplayImage(this)"/>
        </div>
    </div>

    <div class="col-12 col-md-6 mb-5">

        <div class="mb-5">
            <p class="text-underline">CUSTOMIZING OPTIONS</p>
            <p>You can choose frame, material and print size.</p>
        </div>

        <div class="customization-option">PRINT SIZE:</div>
        <div class="mb-5">
            <a class="customization-select-option artwork-selected-option" onclick="SelectArtworkSize('{{ $artwork->width }} x {{ $artwork->height }}', this, 'small')"><p class="p-0 m-0">{{ $artwork->width }} x {{ $artwork->height }}</p></a>
            <a class="customization-select-option" onclick="SelectArtworkSize('{{ $artwork->width * 2 }} x {{ $artwork->height * 2 }}', this, 'medium')"><p class="p-0 m-0">{{ $artwork->width * 2 }} x {{ $artwork->height * 2 }}</p></a>
            <a class="customization-select-option" onclick="SelectArtworkSize('{{ $artwork->width * 3 }} x {{ $artwork->height * 3}}', this, 'big')"><p class="p-0 m-0">{{ $artwork->width * 3 }} x {{ $artwork->height * 3 }}</p></a>
        </div>

        <div class="customization-option">MATERIAL:</div>
        <div class="mb-5">
            <a class="customization-select-option artwork-selected-option" onclick="SelectPaperMaterial('QUALITY PAPER', this)"><p class="p-0 m-0">QUALITY PAPER</p></a>
            <a class="customization-select-option" onclick="SelectPaperMaterial('FOAM BOARD', this)"><p class="p-0 m-0">FOAM BOARD</p></a>
        </div>

        <div class="customization-option">Frame:</div>
        <div class="mb-5">
            <a class="customization-select-option artwork-selected-option" onclick="SelectFrame('NO FRAME', this)"><p class="p-0 m-0">NO FRAME</p></a>
            <a class="customization-select-option" onclick="SelectFrame('FRAME', this)"><p class="p-0 m-0">FRAME</p></a>
        </div>

        <div class="customization-option">Quantity:</div>
        <div class="mb-5">
            <div class="customization-select-option">
                <input id="quantity-input" type="text" value="1" onchange="QuantityChanged()">
                <div class="customization-option pointer p-1" onclick="QuantityChanged()">OK</div>
                <p id="quantity-error" class="text-danger p-0 m-0"></p>
            </div>
        </div>

        <div class="mb-5">
            <p class="text-underline">SUMMARY</p>
            <p>NAME: <span>{{$artwork->title}}</span></p>
            <p>SIZE: <span id="selected-artwork-size">{{ $artwork->width }} x {{ $artwork->height }}</span></p>
            <p>MATERIAL: <span id="selected-material">QUALITY PAPER</span></p>
            <p>FRAME: <span id="frame">NO FRAME</span></p>
            <p>QUANTITY: <span id="quantity">1</span></p>
            <p>TOTAL PRICE: <span id="total-price"></span> €</p>
        </div>

        <input type="hidden" value="{{ csrf_token() }}" id="_token" name="_token" />
        <input type="hidden" value="small" id="artwork-size" name="artwork-size" />
        <input type="hidden" value="{{ $artwork->id }}" id="artwork-id" name="artwork-id" />
        <input type="hidden" value="NO FRAME" id="frame" name="frame" />

    </div>
</div>

<script>

    $(".artwork-selector").addClass("artwork-selector-inactive");
    $(".artwork-selector").first().removeClass("artwork-selector-inactive");
    GetArtworkPrice();

</script>

@endsection