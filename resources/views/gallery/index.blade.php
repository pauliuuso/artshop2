@extends("layouts/app")

@section("content")

<div class="row mt-5">
    <div class="col-12 gallery-title mb-5">
        <h1 class="marvel">GALLERY</h1>
    </div>

    <div class="col-12 sort">
        <div class="row">
            <div class="col-12 mb-2">
                <div class="sort-option">SORT BY:</div>
                <a href="/" class="sort-option">
                    <div @if ($filter == "") class="text-underline" @endif>ALL</div>
                </a>
                <a href="/gallery/kind/@if($firstCategory != null){{$firstCategory->id}}@endif" class="sort-option">
                    <div @if ($filter == "kind") class="text-underline" @endif>KIND</div>
                </a>
                <a href="/gallery/artist/@if($firstArtist != null){{$firstArtist->id}}@endif" class="sort-option">
                    <div @if ($filter == "artist") class="text-underline" @endif>ARTIST</div>
                </a>
                <div class="sort-option">YEAR</div>
            </div>
        </div>
    </div>

    <div class="col-12 mb-5 sort">
    @if($categories != null)

        @foreach($categories as $category)
                <a href="/gallery/kind/{{$category->id}}" class="sort-option-secondary">
                    <div class="text-uppercase @if($category->id == $sortId) text-underline @endif"><p>{{$category->name}}</p></div>
                </a>
        @endforeach

    @endif

    @if($authors != null)

            @foreach($authors as $author)
                    <a href="/gallery/artist/{{$author->id}}" class="sort-option-secondary">
                        <div class="text-uppercase @if($author->id == $sortId) text-underline @endif"><p>{{$author->name}} {{$author->surname}}</p></div>
                    </a>
            @endforeach

    @endif
    </div>

    @if(count($artworks) > 0 && $artworks != null)

        @foreach($artworks as $artwork)
            <a class="col-12 col-md-6 col-lg-4 art-link" href="/artwork/show/{{$artwork->id}}">
                <div class="art-wrapper">
                    <img src="/storage/artworks/{{$artwork->thumbnail_name}}" class="p-0 m-0 art-image visibility-hidden" onload="ImageLoaded(this)"/>
                </div>
                <div class="art-info pt-4 text-center mb-5">
                    <h3 class="text-uppercase">{{ $artwork->title }}</h3>
                    <p class="text-uppercase">{{ $artwork->getAuthor->name . " " . $artwork->getAuthor->surname }}</p>
                </div>
            </a>
        @endforeach
        
        {{$artworks->links()}}

    @else
        <div class="col-12">
            <p>Nothing found!</p>
        </div>
    @endif


</div>

@endsection