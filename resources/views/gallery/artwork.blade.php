@extends("layouts/app")

@section("content")

<div class="row mt-5">

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

<script>

    $(".artwork-selector").addClass("artwork-selector-inactive");
    $(".artwork-selector").first().removeClass("artwork-selector-inactive");

    function ArtworkPreviewLoaded(image)
    {
        $(image).removeClass("visibility-hidden animated fadeIn");
        $(image).addClass("animated fadeIn");
        var wrapper = image.parentElement;
        $(wrapper).height($(image).height());
    }

    function SelectPreview(pictureName, element)
    {
        $("#artwork-image").attr("src", "/storage/artworks/" + pictureName);
        $(".artwork-selector").addClass("artwork-selector-inactive");
        $(element).removeClass("artwork-selector-inactive");
    }

</script>

@endsection