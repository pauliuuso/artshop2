@extends('layouts.app')

@section('content')
<div class="row mt-5">

    <div class="col-12 mb-5">
        <h2 class="text-uppercase">Reset password:</h2>
    </div>

    <div class="col-12 col-xl-6 mb-5">

        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif

        <form class="form-horizontal" method="POST" action="/password/email">
            {{ csrf_field() }}

            <div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
                <label for="email">Email address:</label>
                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required>
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-primary">
                    Send Password Reset Link
                </button>
            </div>
        </form>

    </div>
</div>
@endsection
