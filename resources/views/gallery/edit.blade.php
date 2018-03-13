@extends("layouts/app")

@section("content")

<div class="row mt-5">

    <div class="col-12 text-center mb-5">
        <h2 class="text-uppercase">Edit artwork:</h2>
    </div>

    <div class="col-12 col-xl-6 mb-5">
        {!! Form::open(["action" => ["ArtworksController@update", $artwork->id], "method" => "POST", "files" => true]) !!}
        <div class="form-group">
            {{Form::label("title", "Title:")}}
            {{Form::text("title", $artwork->title, ["class" => "form-control", "placeholder" => ""])}}
        </div>
        <div class="form-group">
            {{Form::label("category", "Category:")}}
            {{Form::select("category", $categories, $category, ["class" => "form-control"])}}
        </div>
        <div class="form-group">
            {{Form::label("author", "Author:")}}
            {{Form::select("author", $authors, $author, ["class" => "form-control"])}}
        </div>
        <div class="form-group">
            {{Form::label("description", "Description:")}}
            {{Form::textarea("description", $artwork->description, ["class" => "form-control ckeditor"])}}
        </div>
        <div class="form-group">
            {{Form::label("width", "Width (cm):")}}
            {{Form::text("width", "$artwork->width", ["class" => "form-control"])}}
        </div>
        <div class="form-group">
            {{Form::label("height", "Height (cm):")}}
            {{Form::text("height", "$artwork->height", ["class" => "form-control"])}}
        </div>
        <div class="form-group">
            {{Form::label("year", "Year:")}}
            {{Form::number("year", $artwork->year, ["class" => "form-control", "placeholder" => ""])}}
        </div>
        <div class="form-group">
            {{Form::label("smallprice", "Small price:")}}
            {{Form::number("smallprice", $artwork->smallprice, ["class" => "form-control", "placeholder" => ""])}}
        </div>
        <div class="form-group">
            {{Form::label("mediumprice", "Medium price:")}}
            {{Form::number("mediumprice", $artwork->mediumprice, ["class" => "form-control", "placeholder" => ""])}}
        </div>
        <div class="form-group">
            {{Form::label("bigprice", "Big price:")}}
            {{Form::number("bigprice", $artwork->bigprice, ["class" => "form-control", "placeholder" => ""])}}
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
    </div>

</div>

<script type="text/javascript">
    ShowSelectedBackground();
</script>

@endsection