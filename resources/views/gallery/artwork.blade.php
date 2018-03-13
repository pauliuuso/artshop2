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

    <div class="col-12 col-md-6 mb-3">
        <img class="img-fluid visibility-hidden" src="/storage/artworks/{{$artwork->picture_name}}" onload="ImageLoaded(this)"/>
    </div>

    <div class="col-12 col-md-6 text-justify">
        <p class="text-uppercase text-underline">DESCRIPTION</p>
        {!!$artwork->description!!}

        <div class="row mt-2">
            <div class="col-12 text-justify">
                <p class="text-uppercase text-underline">AUTHOR BIO</p>
                <p>{!! $artwork->getAuthor->description !!}</p>
            </div>
        </div>
    </div>

    <div class="col-12 col-md-6">
        <div class="row">
            <a class="col-12 col-md-6 col-lg-4 art-link mb-3">
                <div class="art-wrapper">
                    <img src="/storage/artworks/{{$artwork->picture_name}}" class="p-0 m-0 art-image visibility-hidden" onload="ImageLoaded(this)" window:onresize="CenterImage(this)"/>
                </div>
            </a>
            <a class="col-12 col-md-6 col-lg-4 art-link mb-3">
                <div class="art-wrapper">
                    <img src="/storage/artworks/{{$artwork->preview_name}}" class="p-0 m-0 art-image visibility-hidden" onload="ImageLoaded(this)" window:onresize="CenterImage(this)"/>
                </div>
            </a>
        </div>
    </div>


</div>

@endsection