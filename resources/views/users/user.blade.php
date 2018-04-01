@extends("layouts/app")

@section("content")

<div class="row mt-7">

    <div class="col-12 mb-2 mb-sm-5 main-title">
        <h1 class="marvel text-uppercase">{{ $user->name }} {{ $user->surname }}</h1>
    </div>

</div>

@endsection