@extends('layouts.app')

@section('content')
<div class="row mt-7">

        <div class="col-12 mb-2 mb-sm-5 main-title">
            <h1 class="marvel">REGISTER</h1>
        </div>
    
        <div class="col-12 mb-5 text-center">
            <form class="form-horizontal" method="POST" action="/register">
                {{ csrf_field() }}

                <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                    <!-- <label for="name" >Name:</label> -->
                    <input id="name" type="text" class="form-control col-12 col-xl-6 offset-xl-3" name="name" value="{{ old('name') }}" required placeholder="Name" autofocus>
                </div>

                <div class="form-group {{ $errors->has('surname') ? 'has-error' : '' }}">
                    <!-- <label for="surname" >Surname:</label> -->
                    <input id="surname" type="text" class="form-control col-12 col-xl-6 offset-xl-3" name="surname" value="{{ old('surname') }}" required placeholder="Surname" autofocus>
                </div>

                <div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
                    <!-- <label for="email">Email Address:</label> -->
                    <input id="email" type="email" class="form-control col-12 col-xl-6 offset-xl-3" name="email" value="{{ old('email') }}" placeholder="Email address" required>
                </div>

                <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                    <!-- <label for="password">Password:</label> -->
                    <input id="password" type="password" class="form-control col-12 col-xl-6 offset-xl-3" name="password" placeholder="Password" required>
                </div>

                <div class="form-group">
                    <!-- <label for="password-confirm" class="col-md-4 control-label">Confirm password:</label> -->
                    <input id="password-confirm" type="password" class="form-control col-12 col-xl-6 offset-xl-3" name="password_confirmation" placeholder="Confirm password" required>
                </div>

                <div class="form-group">
                    <button type="submit" class="artshop-button mt-5">
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
