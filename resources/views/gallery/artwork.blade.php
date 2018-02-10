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

    <div class="col-12 gallery-title mb-5">
        <h2 class="text-uppercase">{{$artwork->title}}</h2>
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


</div>

@endsection