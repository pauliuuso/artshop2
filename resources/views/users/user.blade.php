@extends("layouts/app")

@section("content")

<div class="row mt-7">

    <div class="col-12 main-title">
        <h1 class="marvel text-uppercase">{{ $user->name }} {{ $user->surname }}</h1>
        <h4 class="text-uppercase main-title-sub">GRAPHIC ARTIST</h4>
    </div>

    <div class="col-xs-12 col-sm-6 col-lg-4">
        <img class="size-full" src="/storage/users/{{$user->picture_name}}">
    </div>
    <div class="artist-card col-xs-12 col-sm-6 col-lg-8">
        <h4 class="text-uppercase main-title-sub">SHORT BIO</h4>
        <div class="col-12 purchase-beam"></div>
        <div class="artist-description text-justify mt-3 mb-4">
            <div class="columns">
                {!! $user->description !!}
            </div>
        </div>
        <h4 class="text-uppercase main-title-sub">Projects</h4>
        <div class="col-12 purchase-beam"></div>
        <div class="artist-description text-justify mt-3 mb-4">
            <div class="columns">
                <p>Lorem ipsum dolor sit amet, ne mei reque quodsi, laudem dictas ea eum. Eius lorem legere cu eos. Mei eu aliquam tacimates intellegat, ad homero percipit nec. Dicam quodsi mea an, his eu mucius appetere vivendum, cum iudico dolores nominati ut. Pri tale detraxit evertitur ut, docendi facilisi instructior est ex. Qui putant ornatus postulant in, ea probo invidunt pro.Lorem ipsum dolor sit amet, ne mei reque quodsi, laudem dictas ea eum. Eius lorem legere cu eos. Mei eu aliquam tacimates intellegat, ad homero percipit nec. Dicam quodsi mea an, his eu mucius appetere vivendum, cum iudico dolores nominati ut. Pri tale detraxit evertitur ut, docendi facilisi instructior est ex. Qui putant ornatus postulant in, ea probo invidunt pro.Lorem ipsum dolor sit amet, ne mei reque quodsi, laudem dictas ea eum. Eius lorem legere cu eos. Mei eu aliquam tacimates intellegat, ad homero percipit nec. Dicam quodsi mea an, his eu mucius appetere vivendum, cum iudico dolores nominati ut. Pri tale detraxit evertitur ut, docendi facilisi instructior est ex. Qui putant ornatus postulant in, ea probo invidunt pro.Lorem ipsum dolor sit amet, ne mei reque quodsi, laudem dictas ea eum. Eius lorem legere cu eos. Mei eu aliquam tacimates intellegat, ad homero percipit nec. Dicam quodsi mea an, his eu mucius appetere vivendum, cum iudico dolores nominati ut. Pri tale detraxit evertitur ut, docendi facilisi instructior est ex. Qui putant ornatus postulant in, ea probo invidunt pro.</p>
            </div>
        </div>
    </div>

    <div class="col-12">
        <h4 class="text-uppercase main-title-sub mt-5">ARTWORKS</h4>
        <div class="col-12 purchase-beam mb-4"></div>
    </div>

    {{-- <div class="col-12 text-center">
        <div class="row pl-7 pr-7">
            <div class="col-12 artist-artwork-wrapper">
                <div class="carousel slide multi-item-carousel">
                    <div class="carousel-inner">
                        @foreach($user->artworks as $artwork)
                            <div class="item artist-artwork">
                                <div class="col-3">
                                    <img class="size-full" src="/storage/artworks/{{$artwork->thumbnail_name}}">
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div> --}}


    <div class="col-12 slick-slider artist-carousel pl-7 pr-7 mb-5">
        @foreach($user->artworks as $index => $artwork)
            <img class="art-image mr-4" src="/storage/artworks/{{$artwork->thumbnail_name}}">
        @endforeach
    </div>

</div>

<script>
    $('.slick-slider').slick({
        slidesPerRow: 4,
        slidesToShow: 4,
        slidesToScroll: 4,
        dots: true,
        prevArrow: "<p class='slick-previous pointer'><</p>",
        nextArrow: "<p class='slick-nexter pointer'>></p>"
    });
</script>


@endsection