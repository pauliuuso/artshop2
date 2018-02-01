@extends("layouts/app")

@section("content")

<div class="row mt-5">
    <div class="col-12 mb-5">
        <h2>Categories:</h2>
    </div>

    <div class="col-12 col-lg-6">

        {!! Form::open(["action" => "CategoriesController@store", "method" => "POST", "class" => "mb-5"]) !!}
            <div class="form-group">
                {{Form::label("name", "Category name:")}}
                {{Form::text("name", "", ["class" => "form-control", "placeholder" => ""])}}
            </div>
        {{Form::submit("Submit", ["class" => "btn btn-primary"])}}
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