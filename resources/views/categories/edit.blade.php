@extends("layouts/app")

@section("content")

<div class="row mt-7">
    <div class="col-12 mb-2 mb-sm-5 main-title">
        <h1 class="marvel">EDIT CATEGORY</h1>
    </div>

    <div class="col-12 col-lg-6">

        {!! Form::open(["action" => ["CategoriesController@update", $category->id], "method" => "POST", "class" => "mb-1"]) !!}
            <div class="form-group">
                {{Form::label("name", "Category name:")}}
                {{Form::text("name", $category->name, ["class" => "form-control", "placeholder" => ""])}}
            </div>
            {{Form::hidden("_method", "PUT")}}
            {{Form::submit("Submit", ["class" => "artshop-button mt-5"])}}
        {!! Form::close() !!}

        {!! Form::open(["action" => ["CategoriesController@destroy", $category->id], "method" => "POST"]) !!}
            {{Form::hidden("_method", "DELETE")}}
            {{Form::submit("Delete", ["class" => "artshop-button red"])}}
        {!! Form::close() !!}

    </div>

</div>

@endsection