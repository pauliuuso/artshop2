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
            {{Form::select("category", ["1" => "Oil", "2" => "Watercolor"], null, ["class" => "form-control"])}}
        </div>
        <div class="form-group">
            {{Form::label("author", "Author:")}}
            {{Form::select("author", ["1" => "Paulius", "2" => "Urte"], null, ["class" => "form-control"])}}
        </div>
        <div class="form-group">
            {{Form::label("description", "Description:")}}
            {{Form::textarea("description", "", ["class" => "form-control"])}}
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
        {{Form::submit("Submit", ["class" => "btn btn-primary"])}}
        {!! Form::close() !!}
    </div>

</div>

@endsection