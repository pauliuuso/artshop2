@extends("layouts/app")

@section("content")

<div class="row mt-7">

    <div class="col-12 mb-2 mb-sm-5 main-title">
        <h1 class="marvel">ARTISTS</h1>
    </div>

    @foreach($users as $user)

    <div class="col-12">
        <div class="row artist-card">
            <div class="col-10 col-md-3 col-lg-3 col-xl-2 offset-1 offset-md-0 mb-5">
                <img class="size-full" src="/storage/users/{{$user->picture_name}}">
            </div>
            <div class="col-12 col-md-9 col-lg-9 col-xl-10 pl-md-5 text-center text-sm-left artist-description">
                <h2 class="text-uppercase main-title-sub">{{ $user->name }} {{ $user->surname }}</h2>
                <p class="artist-type mb-2">Graphical artist</p>
                <div class="col-12 purchase-beam"></div>
                <div class="artist-description text-justify mt-3">
                    <div class="columns">
                        {!! str_limit($user->description, 800) !!}
                    </div>
                    <div class="text-right">
                        <a class="artshop-button-a p-2 pl-5 pr-5" href="/artists/{{ $user->id }}">READ MORE</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @endforeach

</div>

@endsection