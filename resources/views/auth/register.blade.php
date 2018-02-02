@extends('layouts.app')

@section('content')
<div class="row mt-5">

        <div class="col-12 mb-5">
            <h2 class="text-uppercase">Register:</h2>
        </div>
    
        <div class="col-12 col-xl-6 mb-5">
            <form class="form-horizontal" method="POST" action="/register">
                {{ csrf_field() }}

                <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                    <label for="name" >Name:</label>
                    <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" required autofocus>
                </div>

                <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                    <label for="surname" >Surname:</label>
                    <input id="surname" type="text" class="form-control" name="surname" value="{{ old('surname') }}" required autofocus>
                </div>

                <div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
                    <label for="email">Email Address:</label>
                    <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required>
                </div>

                <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                    <label for="password">Password:</label>
                    <input id="password" type="password" class="form-control" name="password" required>
                </div>

                <div class="form-group">
                    <label for="password-confirm" class="col-md-4 control-label">Confirm password:</label>
                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary">
                        Register
                    </button>
                </div>
            </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
