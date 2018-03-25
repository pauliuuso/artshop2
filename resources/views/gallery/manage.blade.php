@extends("layouts/app")

@section("content")

<div class="row mt-7">
    <div class="col-12 mb-5">
        <h2>Manage artworks:</h2>
    </div>

    <div class="col-12 col-lg-6">
        @if(count($artworks) > 0)

            @foreach($artworks as $artwork)
            <div class="row category bg-success mb-2">
                <div class="col-12">
                    <span class="text-uppercase">{{$artwork->title}}</span> by {{$artwork->getAuthor->name}} {{$artwork->getAuthor->surname}}
                    <a href="/artwork/edit/{{$artwork->id}}">
                        <i class="fas fa-edit"></i>
                    </a>
                </div>
                <div class="col-12">
                    <img class="manage-image" src="/storage/artworks/{{$artwork->thumbnail_name}}">
                </div>
            </div>
            @endforeach

        @else
                <p>Nothing found!</p>
        @endif
    </div>


</div>

@endsection