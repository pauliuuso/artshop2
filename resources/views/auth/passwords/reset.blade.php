@extends('layouts.app')

@section('content')

<div class="row mt-7">

    <div class="col-12 mb-2 mb-sm-5 main-title">
        <h1 class="marvel">RESET PASSWORD</h1>
    </div>

    <div class="col-12 mb-5 text-center">
        <form class="form-horizontal" method="POST" action="/password/reset">
            {{ csrf_field() }}

            <input type="hidden" name="token" value="{{ $token }}">

            <div class="form-group col-xl-6 offset-xl-3 {{ $errors->has('email') ? 'has-error' : '' }}">
                <!-- <label for="email">Email address:</label> -->
                <input id="email" type="email" class="form-control" name="email" value="{{ $email or old('email') }}" required placeholder="Email" autofocus>
            </div>

            <div class="form-group col-xl-6 offset-xl-3 {{ $errors->has('password') ? 'has-error' : '' }}">
                <!-- <label for="password">Password:</label> -->
                <input id="password" type="password" class="form-control" name="password" placeholder="Password" required>
            </div>

            <div class="form-group col-xl-6 offset-xl-3 {{ $errors->has('password_confirmation') ? 'has-error' : '' }}">
                <!-- <label for="password-confirm">Confirm password:</label> -->
                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" placeholder="Confirm password" required>
            </div>

            <div class="form-group">
                <button type="submit" class="artshop-button mt-5">
                    Reset Password
                </button>
            </div>
        </form>
    </div>
</div>

@endsection
