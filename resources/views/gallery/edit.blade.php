@extends("layouts/app")

@section("content")

<div class="row mt-7">

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
        <div class="row mb-3">
            <div class="col-12">
                <p>Sizes:</p> 
                @foreach($artwork->getSizes as $size)
                    <a class="customization-select-option mr-1" href="/edit-size/{{$size->id}}">
                        <p>{{$size->width}}x{{$size->height}}</p>
                        <p>{{$size->price}} €</p>
                    </a>
                @endforeach
                <a href="/new-size/{{$artwork->id}}">
                    <i class="fas fa-plus fs-40 ml-2"></i>
                </a>
            </div>
        </div>
        <div class="form-group">
            {{Form::label("description", "Description:")}}
            {{Form::textarea("description", $artwork->description, ["class" => "form-control ckeditor"])}}
        </div>
        <div class="form-group">
            {{Form::label("year", "Year:")}}
            {{Form::number("year", $artwork->year, ["class" => "form-control", "placeholder" => ""])}}
        </div>
        <div class="form-group">
            {{Form::label("thumbnail", "Thumbnail:")}}
            {{Form::file("thumbnail", ["class" => "form-control"])}}
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