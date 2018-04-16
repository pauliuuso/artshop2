@extends("layouts/app")

@section("content")

<div class="row mt-7">

    <div class="col-10 col-sm-12 main-title offset-1 offset-sm-0">
        <h1 class="marvel text-uppercase">{{ $user->name }} {{ $user->surname }}</h1>
        <h4 class="text-uppercase main-title-sub">{{ $user->speciality }}</h4>
    </div>

    <div class="col-10 col-sm-6 col-lg-4 offset-1 offset-sm-0 mb-4">
        <img class="size-full" src="/storage/users/{{$user->picture_name}}">
    </div>

    <div class="artist-card col-10 col-sm-6 col-lg-8 offset-1 offset-sm-0">
        <h4 class="text-uppercase main-title-sub">SHORT BIO</h4>
        <div class="col-12 purchase-beam"></div>
        <div class="artist-description text-justify mt-3 mb-4">
            <div class="columns">
                {!! $user->description !!}
            </div>
        </div>
        <h4 class="text-uppercase main-title-sub">Projects</h4>
        <div class="col-12 purchase-beam"></div>
        <div class="artist-description text-justify mt-3 mb-2 mb-sm-4">
            <div class="columns">
                <p>Lorem ipsum dolor sit amet, ne mei reque quodsi, laudem dictas ea eum. Eius lorem legere cu eos. Mei eu aliquam tacimates intellegat, ad homero percipit nec. Dicam quodsi mea an, his eu mucius appetere vivendum, cum iudico dolores nominati ut. Pri tale detraxit evertitur ut, docendi facilisi instructior est ex. Qui putant ornatus postulant in, ea probo invidunt pro.Lorem ipsum dolor sit amet, ne mei reque quodsi, laudem dictas ea eum. Eius lorem legere cu eos. Mei eu aliquam tacimates intellegat, ad homero percipit nec. Dicam quodsi mea an, his eu mucius appetere vivendum, cum iudico dolores nominati ut. Pri tale detraxit evertitur ut, docendi facilisi instructior est ex. Qui putant ornatus postulant in, ea probo invidunt pro.Lorem ipsum dolor sit amet, ne mei reque quodsi, laudem dictas ea eum. Eius lorem legere cu eos. Mei eu aliquam tacimates intellegat, ad homero percipit nec. Dicam quodsi mea an, his eu mucius appetere vivendum, cum iudico dolores nominati ut. Pri tale detraxit evertitur ut, docendi facilisi instructior est ex. Qui putant ornatus postulant in, ea probo invidunt pro.Lorem ipsum dolor sit amet, ne mei reque quodsi, laudem dictas ea eum. Eius lorem legere cu eos. Mei eu aliquam tacimates intellegat, ad homero percipit nec. Dicam quodsi mea an, his eu mucius appetere vivendum, cum iudico dolores nominati ut. Pri tale detraxit evertitur ut, docendi facilisi instructior est ex. Qui putant ornatus postulant in, ea probo invidunt pro.</p>
            </div>
        </div>
    </div>

    <div class="col-10 col-sm-12 offset-1 offset-sm-0">
        <h4 class="text-uppercase main-title-sub mt-5">ARTWORKS</h4>
        <div class="col-12 purchase-beam mb-4"></div>
    </div>

    <div class="col-12 slick-slider artist-carousel pl-7 pr-7 mb-5">
        @foreach($artworks as $index => $artwork)
            <a class="mb-3 m-2 mb-sm-4 m-xl-3" href="/artwork/show/{{$artwork->id}}">
                <img class="margin-auto" src="/storage/artworks/{{$artwork->thumbnail_name}}" onload="CalculateTopMargin(this)">
            </a>
        @endforeach
    </div>

    <div class="col-10 col-sm-12 offset-1 offset-sm-0 text-center text-sm-right mb-5 mt-4">
        <a href="/gallery/artist/{{$user->id}}" class="customization-select-option-low">VIEW ALL</a>
    </div>

</div>

<script>
    $('.slick-slider').slick({
        slidesToShow: 4,
        slidesToScroll: 4,
        dots: true,
        prevArrow: "<p class='slick-previous pointer'><</p>",
        nextArrow: "<p class='slick-nexter pointer'>></p>",
        responsive: 
        [{
            breakpoint: 992,
            settings: 
            {
                slidesToShow: 3,
                slidesToScroll: 3,
                infinite: true,
                dots: true,
                prevArrow: "<p class='slick-previous pointer'><</p>",
                nextArrow: "<p class='slick-nexter pointer'>></p>",
            }
        },
        {
            breakpoint: 768,
            settings: 
            {
                slidesToShow: 2,
                slidesToScroll: 2,
                infinite: true,
                dots: true,
                prevArrow: "<p class='slick-previous pointer'><</p>",
                nextArrow: "<p class='slick-nexter pointer'>></p>",
            }
        },
        {
            breakpoint: 576,
            settings: 
            {
                slidesToShow: 1,
                slidesToScroll: 1,
                infinite: true,
                dots: true,
                prevArrow: "<p class='slick-previous pointer'><</p>",
                nextArrow: "<p class='slick-nexter pointer'>></p>",
            }
        }]
    });

</script>


@endsection