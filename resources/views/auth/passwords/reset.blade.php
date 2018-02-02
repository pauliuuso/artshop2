@extends('layouts.app')

@section('content')

<div class="row mt-5">

    <div class="col-12 mb-5">
        <h2 class="text-uppercase">Reset password:</h2>
    </div>

    <div class="col-12 col-xl-6 mb-5">
        <form class="form-horizontal" method="POST" action="/password/reset">
            {{ csrf_field() }}

            <input type="hidden" name="token" value="{{ $token }}">

            <div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
                <label for="email">Email address:</label>
                <input id="email" type="email" class="form-control" name="email" value="{{ $email or old('email') }}" required autofocus>
            </div>

            <div class="form-group {{ $errors->has('password') ? 'has-error' : '' }}">
                <label for="password">Password:</label>
                <input id="password" type="password" class="form-control" name="password" required>
            </div>

            <div class="form-group {{ $errors->has('password_confirmation') ? 'has-error' : '' }}">
                <label for="password-confirm">Confirm password:</label>
                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-primary">
                    Reset Password
                </button>
            </div>
        </form>
    </div>
</div>

@endsection
