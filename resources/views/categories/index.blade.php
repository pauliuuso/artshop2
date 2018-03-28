@extends("layouts/app")

@section("content")

<div class="row mt-7">
    <div class="col-12 mb-2 mb-sm-5 main-title">
        <h1 class="marvel">CATEGORIES</h1>
    </div>

    <div class="col-12 col-lg-6">

        {!! Form::open(["action" => "CategoriesController@store", "method" => "POST", "class" => "mb-5"]) !!}
            <div class="form-group">
                {{Form::label("name", "Category name:")}}
                {{Form::text("name", "", ["class" => "form-control", "placeholder" => ""])}}
            </div>
        {{Form::submit("Submit", ["class" => "artshop-button mt-5"])}}
        {!! Form::close() !!}


        @if(count($categories) > 0)

        @foreach($categories as $category)
            <div class="category bg-success mb-2">
                {{$category->name}}
                <a href="/category/edit/{{$category->id}}">
                    <i class="fas fa-edit"></i>
                </a>
            </div>
        @endforeach
    
        @else
            <p>Nothing found!</p>
        @endif
    </div>

</div>

@endsection