@extends("layouts/app")

@section("content")

<div class="row mt-7">

    <div class="col-12 text-center mb-5">
        <h2 class="text-uppercase">Add new size:</h2>
    </div>

    <div class="col-12 col-xl-6 mb-5">
        {!! Form::open(["action" => ["ArtworksController@createsize", $artworkId], "method" => "POST"]) !!}
        <div class="form-group">
            {{Form::label("width", "Width (cm):")}}
            {{Form::text("width", "", ["class" => "form-control"])}}
        </div>
        <div class="form-group">
            {{Form::label("height", "Height (cm):")}}
            {{Form::text("height", "", ["class" => "form-control"])}}
        </div>
        <div class="form-group">
            {{Form::label("price", "Price:")}}
            {{Form::number("price", "", ["class" => "form-control", "placeholder" => ""])}}
        </div>
        <div class="form-group">
            {{Form::label("background", "Background:")}}
            {{Form::select("background", $backgroundIdsAndTitles, 0, ["class" => "form-control", "id" => "background-selector", "placeholder" => "Select Background"])}}
        </div>
        <div class="row mb-4" id="background-list" >
            @foreach($backgrounds as $background)
                <div id="background-{{$background->id}}" class="col-12 background">
                    <img class="img-fluid" src="/storage/backgrounds/{{$background->background_name}}">
                </div>
            @endforeach
        </div>
        {{Form::submit("Submit", ["class" => "btn btn-primary"])}}
        {!! Form::close() !!}
    </div>

</div>

<script>
    ShowSelectedBackground()
</script>

@endsection