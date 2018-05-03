@extends("layouts/intro")

@section("content")

    <a href="/gallery" class="intro-link">
    </a>

    <div class="col-12 intro-description main-title text-left">
        <div class="offset-sm-2 intro-text">
            <p class="main-title-sub m-0">WELCOME TO</p>
            <h1 class="marvel intro-title">ARTSHOP</h1>
            <p class="text-justify pt-4 mb-5">Lorem ipsum dolor sit amet, ne mei reque quodsi, laudem dictas ea eum. Eius lorem legere cu eos. Mei eu aliquam tacimates intellegat, ad homero percipit nec. Dicam quodsi mea an, his eu mucius appetere vivendum, cum iudico dolores nominati ut. Pri tale detraxit evertitur ut, docendi facilisi instructior est ex. Qui putant ornatus postulant in, ea probo invidunt pro. Lorem ipsum dolor sit amet, ne mei reque quodsi, laudem dictas ea eum. Eius lorem legere cu eos. Mei eu aliquam tacimates intellegat, ad homero percipit nec. Dicam quodsi mea an, his eu mucius appetere vivendum, cum iudico dolores nominati ut. Pri tale detraxit evertitur ut, docendi facilisi instructior est ex. Qui putant ornatus postulant in, ea probo invidunt pro. Lorem ipsum dolor sit amet, ne mei</p>
            <a href="/gallery" class="intro-continue mt-5">CONTINUE</a>
        </div>
    </div>

    <video id="intro-video" muted loop autoplay poster="/storage/intro/intro.png" class="intro-picture">
        <source src="/storage/intro/intro.webm" type="video/webm">
        <source src="/storage/intro/intro.ogg" type="video/ogg">
        <source src="/storage/intro/intro.mp4" type="video/mp4">
    </video>

@endsection