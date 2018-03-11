@extends("layouts/app")

@section("content")

<div class="row mt-5">

    <div class="col-12 text-center mb-5">
        <h2 class="text-uppercase">Add new background:</h2>
    </div>

    <div class="col-12 col-xl-6 mb-5">
        {!! Form::open(["action" => "BackgroundsController@store", "method" => "POST", "files" => true]) !!}
        <div class="form-group">
            {{Form::label("title", "Title:")}}
            {{Form::text("title", "", ["class" => "form-control", "placeholder" => ""])}}
        </div>
        <div class="form-group">
            {{Form::label("width", "Assumed width (cm):")}}
            {{Form::text("width", "", ["class" => "form-control"])}}
        </div>
        <div class="form-group">
            {{Form::label("height", "Assumed height (cm):")}}
            {{Form::text("height", "", ["class" => "form-control"])}}
        </div>
        <div class="form-group">
            {{Form::label("picture", "Picture:")}}
            {{Form::file("picture", ["class" => "form-control"])}}
        </div>
        {{Form::submit("Submit", ["class" => "btn btn-primary"])}}
        {!! Form::close() !!}
    </div>

    <div class="clearfix"></div>

    <div class="col-12">
        <div class="row">
            @if(count($backgrounds) > 0 && $backgrounds != null)

            @foreach($backgrounds as $background)
                <a class="col-12 col-xl-6 art-link" href="/backgrounds/edit/{{$background->id}}">
                    <i class="fas fa-edit background-edit-button"></i>
                    <div class="background-wrapper">
                        <img src="/storage/backgrounds/{{$background->background_name}}" class="p-0 m-0 art-image visibility-hidden" onload="ImageLoaded(this)" window:onresize="CenterImage(this)"/>
                    </div>
                    <div class="art-info pt-4 text-center mb-5">
                        <h3 class="text-uppercase">{{ $background->title }}</h3>
                    </div>
                </a>
            @endforeach

            @else
            <div class="col-12">
                <p>Nothing found!</p>
            </div>
            @endif
        </div>
    </div>


</div>

@endsection