@extends('layouts.app')

@section('content')
<div class="row mt-7">

    <div class="col-12 mb-2 mb-sm-5 main-title">
        <h1 class="marvel">RESET PASSWORD</h1>
    </div>

    <div class="col-12 mb-5 text-center">

        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif

        <form class="form-horizontal" method="POST" action="/password/email">
            {{ csrf_field() }}

            <div class="form-group col-xl-6 offset-xl-3 {{ $errors->has('email') ? 'has-error' : '' }}">
                <!-- <label for="email">Email address:</label> -->
                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" placeholder="Email address" required>
            </div>

            <div class="form-group">
                <button type="submit" class="artshop-button mt-5">
                    Send Password Reset Link
                </button>
            </div>
        </form>

    </div>
</div>
@endsection
