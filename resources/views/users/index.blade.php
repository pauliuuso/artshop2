@extends("layouts/app")

@section("content")

<div class="row mt-7">
    <div class="col-12 mb-5">
        <h2>Users:</h2>
    </div>

    <div class="col-12 col-lg-6">

        @if(count($users) > 0)

        @foreach($users as $user)
            <div class="category bg-success mb-2">
                {{$user->name}} {{$user->surname}}
                <a href="/users/edit/{{$user->id}}">
                    <i class="fas fa-edit"></i>
                </a>
            </div>
        @endforeach
    
        @else
            <p>Nothing found!</p>
        @endif
    </div>

</div>

@endsection