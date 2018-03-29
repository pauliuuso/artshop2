@extends("layouts/app")

@section("content")

<div class="row mt-3 mt-7">
    <div class="col-12 mb-2 mb-sm-5 main-title">
        <h1 class="marvel">GALLERY</h1>
    </div>

    <div class="col-12 sort d-none d-sm-block">
        <div class="row">
            <div class="col-12 mb-2">
                <div class="sort-option">SORT BY:</div>
                <a href="/" class="sort-option">
                    <div @if ($filter == "") class="text-underline" @endif>ALL</div>
                </a>
                <a class="sort-option" onclick="ToogleOptionDesktop('category-list-desktop')">
                    <div @if ($filter == "kind") class="text-underline" @endif>KIND</div>
                </a>
                <a class="sort-option" onclick="ToogleOptionDesktop('author-list-desktop')">
                    <div @if ($filter == "artist") class="text-underline" @endif>ARTIST</div>
                </a>
                <a class="sort-option" onclick="ToogleOptionDesktop('year-list-desktop')">
                    <div @if ($filter == "year") class="text-underline" @endif>YEAR</div>
                </a>
            </div>
        </div>
    </div>
    
    <div class="col-12 mb-5 sort d-none d-sm-block">
        @if($categories != null)
            <span class="category-list-desktop desktop-sort-option animated d-none">
                @foreach($categories as $category)
                    <a href="/gallery/kind/{{$category->id}}" class="sort-option-secondary">
                        <div class="text-uppercase @if($category->id == $sortId) text-underline @endif"><p>{{$category->name}}</p></div>
                    </a>
                @endforeach
            </span>
        @endif

        @if($authors != null)
            <span class="author-list-desktop desktop-sort-option animated d-none">
                @foreach($authors as $author)
                    <a href="/gallery/artist/{{$author->id}}" class="sort-option-secondary">
                        <div class="text-uppercase @if($author->id == $sortId) text-underline @endif"><p>{{$author->name}} {{$author->surname}}</p></div>
                    </a>
                @endforeach
            </span>
        @endif

        <span class="year-list-desktop desktop-sort-option animated d-none">
            <a href="/gallery/artist/{{$author->id}}" class="sort-option-secondary">
                <div><p>Year</p></div>
            </a>
        </span>
    </div>

    <div class="col-12 mt-3 mb-2 mobile-sort d-sm-none">
        <div class="text-center title pointer mb-4" onclick="ToogleSort()">
            <p class="mb-1">SORT ITEMS</p>
            <i class="fas fa-chevron-down pointer scroll-down animated"></i>
        </div>
        <div id="mobile-sort-links" class="pl-5">

            <div class="mobile-links-area">
                <a href="/" class="sort-option title">
                    <div @if ($filter == "") class="text-underline title" @endif>ALL</div>
                </a>

                <a class="sort-option title" onclick="ToogleOptions(this)">
                    <div @if ($filter == "kind") class="text-underline title pointer" @endif>KIND</div>
                </a>
                <div class="option-list ml-4">

                    @if($categories != null)
                        <span>

                        @foreach($categories as $category)
                            <a href="/gallery/kind/{{$category->id}}" class="sort-option-secondary">
                                <div class="text-uppercase @if($category->id == $sortId) text-underline @endif"><p class="m-0">{{$category->name}}</p></div>
                            </a>
                        @endforeach

                        </span>
                    @endif

                </div>

                <a class="sort-option title" onclick="ToogleOptions(this)">
                    <div @if ($filter == "artist") class="text-underline title" @endif>ARTIST</div>
                </a>

                <div class="option-list ml-4">
                    @if($authors != null)
                        <span>

                        @foreach($authors as $author)
                            <a href="/gallery/artist/{{$author->id}}" class="sort-option-secondary">
                                <div class="text-uppercase @if($author->id == $sortId) text-underline @endif"><p>{{$author->name}} {{$author->surname}}</p></div>
                            </a>
                        @endforeach

                        </span>
                    @endif
                </div>

                <div class="sort-option title">YEAR</div>
            </div>

            <span class="scroll-up text-center title pointer animated d-none" onclick="ToogleSort()">
                <i class="fas fa-chevron-up"></i>
                <p>Close</p>
            </span>

        </div>
    </div>

    @if(count($artworks) > 0 && $artworks != null)
        <div class="col-12 artwork-list">
            <div class="row">
                @foreach($artworks as $artwork)
                    <a class="offset-1 offset-sm-0 col-10 col-sm-6 col-lg-4 art-link" href="/artwork/show/{{$artwork->id}}">
                        <div class="art-wrapper">
                            <img src="/storage/artworks/{{$artwork->thumbnail_name}}" class="art-image visibility-hidden" onload="ImageLoaded(this)"/>
                        </div>
                        <div class="art-info pt-4 text-center mb-5">
                            <h3 class="text-uppercase">{{ $artwork->title }}</h3>
                            <p class="text-uppercase">{{ $artwork->getAuthor->name . " " . $artwork->getAuthor->surname }}</p>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>


        
        <div class="col-12 text-center">
            {{$artworks->links()}}
        </div>

    @else
        <div class="col-12">
            <p>Nothing found!</p>
        </div>
    @endif


</div>

@endsection