@extends('layouts.app')

@section('content')

<div class="row mt-7">

    <div class="col-12 mb-5">
        <h2 class="text-uppercase">Login:</h2>
    </div>

    <div class="col-12 col-xl-6 mb-5">
        <form class="form-horizontal" method="POST" action="/login">
            {{ csrf_field() }}

            <div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
                <label for="email">Email address:</label>
                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required autofocus>
            </div>

            <div class="form-group {{ $errors->has('password') ? 'has-error' : '' }}">
                <label for="password">Password:</label>
                <input id="password" type="password" class="form-control" name="password" required>
            </div>

            <div class="form-group">
                <div class="checkbox">
                    <label>
                        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Remember Me
                    </label>
                </div>
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-primary">
                    Login
                </button>

                <a class="btn btn-link" href="/password/reset">
                    Forgot Your Password?
                </a>
            </div>
        </form>
    </div>

</div>

@endsection
