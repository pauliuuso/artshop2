@extends('layouts.app')

@section('content')
<div class="row mt-5">

        <div class="col-12 mb-5">
            <h2 class="text-uppercase">Edit user {{$user->email}}:</h2>
        </div>
    
        <div class="col-12 col-xl-6 mb-5">
            <form class="form-horizontal" method="POST" action="/users/update/{{$user->id}}">
                {{ csrf_field() }}

                <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                    <label for="name" >Name:</label>
                    <input id="name" type="text" class="form-control" name="name" value="{{ $user->name }}" required autofocus>
                </div>

                <div class="form-group {{ $errors->has('surname') ? 'has-error' : '' }}">
                    <label for="surname" >Surname:</label>
                    <input id="surname" type="text" class="form-control" name="surname" value="{{ $user->surname }}" required autofocus>
                </div>

                <div class="form-group {{ $errors->has('role') ? 'has-error' : '' }}">
                    <label for="role" >Role:</label>
                    <select name="role" class="form-control">
                        <option value="user" @if($user->role == "user") selected @endif>User</option>
                        <option value="author" @if($user->role == "author") selected @endif>Author</option>
                    </select>
                </div>

                <div class="form-group {{ $errors->has('active') ? 'has-error' : '' }}">
                    <label for="active" >Active:</label>
                    <select name="active" class="form-control">
                        <option value="0" @if($user->active == 0) selected @endif>No</option>
                        <option value="1" @if($user->active == 1) selected @endif>Yes</option>
                    </select>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary">
                        Update
                    </button>
                </div>
            </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
