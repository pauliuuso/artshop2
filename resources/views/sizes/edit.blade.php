@extends("layouts/app")

@section("content")

<div class="row mt-7">

    <div class="col-12 text-center mb-5">
        <h2 class="text-uppercase">Edit size:</h2>
    </div>

    <div class="col-12 col-xl-6 mb-5">
        {!! Form::open(["action" => ["ArtworksController@updatesize", $size->id], "method" => "POST"]) !!}
        <div class="form-group">
            {{Form::label("width", "Width (cm):")}}
            {{Form::text("width", $size->width, ["class" => "form-control"])}}
        </div>
        <div class="form-group">
            {{Form::label("height", "Height (cm):")}}
            {{Form::text("height", $size->height, ["class" => "form-control"])}}
        </div>
        <div class="form-group">
            {{Form::label("price", "Price:")}}
            {{Form::number("price", $size->price, ["class" => "form-control", "placeholder" => ""])}}
        </div>
        <div class="form-group">
            {{Form::label("background", "Background:")}}
            {{Form::select("background", $backgroundIdsAndTitles, $selectedBackground, ["class" => "form-control", "id" => "background-selector", "placeholder" => "Select Background"])}}
        </div>
        <div class="row mb-4" id="background-list" >
            @foreach($backgrounds as $background)
                <div id="background-{{$background->id}}" class="col-12 background">
                    <img class="img-fluid" src="/storage/backgrounds/{{$background->background_name}}">
                </div>
            @endforeach
        </div>
        {{Form::hidden("_method", "PUT")}}
        {{Form::submit("Submit", ["class" => "btn btn-primary"])}}
        {!! Form::close() !!}

        @if(!Auth::guest() && Auth::user()->role == "admin")
            {!! Form::open(["action" => ["ArtworksController@deletesize", $size->id], "method" => "POST"]) !!}
                {{Form::hidden("_method", "DELETE")}}
                {{Form::submit("Delete", ["class" => "btn btn-danger mt-2"])}}
            {!! Form::close() !!}
        @endif

    </div>

    <div class="col-12 col-xl-6 mb-5 mt-2 text-center">
        <img class="img-fluid" src="/storage/artworks/{{$size->preview_name}}">
    </div>

</div>

<script>
    ShowSelectedBackground()
</script>

@endsection