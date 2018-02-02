@extends("layouts/app")

@section("content")

<div class="row mt-5">
    <div class="col-12 gallery-title mb-5">
        <h1 class="marvel">GALLERY</h1>
    </div>

    @if(count($artworks) > 0)

        @foreach($artworks as $artwork)
            <a class="col-12 col-md-6 col-lg-4 col-xl-3 art-link" href="/artwork/show/{{$artwork->id}}">
                <div class="art-wrapper">
                    <img src="/storage/artworks/{{$artwork->thumbnail_name}}" class="p-0 m-0 art-image"/>
                </div>
                <div class="art-info pt-4 text-center mb-5">
                    <h3 class="text-uppercase">{{$artwork->title}}</h3>
                    <p class="text-uppercase">{{ $authors[$artwork->author_id] }}</p>
                </div>
            </a>
        @endforeach
        
        {{$artworks->links()}}

    @else
            <p>Nothing found!</p>
    @endif


</div>

@endsection