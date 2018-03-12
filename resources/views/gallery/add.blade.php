@extends("layouts/app")

@section("content")

<div class="row mt-5">

    <div class="col-12 text-center mb-5">
        <h2 class="text-uppercase">Add new artwork:</h2>
    </div>

    <div class="col-12 col-xl-6 mb-5">
        {!! Form::open(["action" => "ArtworksController@store", "method" => "POST", "files" => true]) !!}
        <div class="form-group">
            {{Form::label("title", "Title:")}}
            {{Form::text("title", "", ["class" => "form-control", "placeholder" => ""])}}
        </div>
        <div class="form-group">
            {{Form::label("category", "Category:")}}
            {{Form::select("category", $categories, null, ["class" => "form-control"])}}
        </div>
        <div class="form-group">
            {{Form::label("author", "Author:")}}
            {{Form::select("author", $authors, null, ["class" => "form-control"])}}
        </div>
        <div class="form-group">
            {{Form::label("description", "Description:")}}
            {{Form::textarea("description", "", ["class" => "form-control ckeditor"])}}
        </div>
        <div class="form-group">
            {{Form::label("width", "Width (cm):")}}
            {{Form::text("width", "", ["class" => "form-control"])}}
        </div>
        <div class="form-group">
            {{Form::label("height", "Height (cm):")}}
            {{Form::text("height", "", ["class" => "form-control"])}}
        </div>
        <div class="form-group">
            {{Form::label("year", "Year:")}}
            {{Form::number("year", "", ["class" => "form-control", "placeholder" => ""])}}
        </div>
        <div class="form-group">
            {{Form::label("smallprice", "Small price:")}}
            {{Form::number("smallprice", "", ["class" => "form-control", "placeholder" => ""])}}
        </div>
        <div class="form-group">
            {{Form::label("mediumprice", "Medium price:")}}
            {{Form::number("mediumprice", "", ["class" => "form-control", "placeholder" => ""])}}
        </div>
        <div class="form-group">
            {{Form::label("bigprice", "Big price:")}}
            {{Form::number("bigprice", "", ["class" => "form-control", "placeholder" => ""])}}
        </div>
        <div class="form-group">
            {{Form::label("thumbnail", "Thumbnail:")}}
            {{Form::file("thumbnail", ["class" => "form-control"])}}
        </div>
        <div class="form-group">
            {{Form::label("picture", "Picture:")}}
            {{Form::file("picture", ["class" => "form-control"])}}
        </div>
        <div class="form-group">
            {{Form::label("background", "Background:")}}
            {{Form::select("background", $backgroundIdsAndTitles, "", ["class" => "form-control", "placeholder" => "Select Background"])}}
        </div>
        <div class="row mb-4">
            @foreach($backgrounds as $background)
                <div class="col-12 col-md-6 col-xl-4">
                    <img class="img-fluid" src="/storage/backgrounds/{{$background->background_name}}">
                    <p class="text-center">{{ $background->title }}</p>
                </div>
            @endforeach
        </div>
        {{Form::submit("Submit", ["class" => "btn btn-primary"])}}
        {!! Form::close() !!}
    </div>

</div>

@endsection