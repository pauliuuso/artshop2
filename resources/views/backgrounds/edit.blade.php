@extends("layouts/app")

@section("content")

<div class="row mt-5">

    <div class="col-12 text-center mb-5">
        <h2 class="text-uppercase">Edit background:</h2>
    </div>

    <div class="col-12 col-xl-6 mb-5">
        {!! Form::open(["action" => ["BackgroundsController@update", $background->id], "method" => "POST", "files" => true]) !!}
        <div class="form-group">
            {{Form::label("title", "Title:")}}
            {{Form::text("title", $background->title, ["class" => "form-control", "placeholder" => ""])}}
        </div>
        <div class="form-group">
            {{Form::label("width", "Width (cm):")}}
            {{Form::text("width", "$background->width", ["class" => "form-control"])}}
        </div>
        <div class="form-group">
            {{Form::label("height", "Height (cm):")}}
            {{Form::text("height", "$background->height", ["class" => "form-control"])}}
        </div>
        <div class="form-group">
            {{Form::label("picture", "Picture:")}}
            {{Form::file("picture", ["class" => "form-control"])}}
        </div>
        {{Form::hidden("_method", "PUT")}}
        {{Form::submit("Submit", ["class" => "btn btn-primary"])}}
        {!! Form::close() !!}
    </div>

</div>

@endsection