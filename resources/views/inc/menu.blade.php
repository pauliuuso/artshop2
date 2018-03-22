<div class="row">
    <div class="col-12 col-md-6 offset-md-3">
        <ul class="art-menu mt-3">
            <a href="/"><li>Gallery | </li></a>
            <a href="/artists"><li>Artists | </li></a>
            <a href="/about"><li>About | </li></a>
            <a href="/contacts"><li>Contacts </li></a>
            @if(!Auth::guest() && Auth::user()->role == "admin")
                <a href="/admin"><li>| Admin</li></a>
            @endif
        </ul>
    </div>

    <div class="col-12 col-md-3 pt-4">
        <ul class="login-buttons pl-2">
            <li>
                <a href="/get-cart">Cart: {{ Session::has("cart") ? Session::get("cart")->totalCount : "0" }}</a>
            </li>
        </ul>
        @if(Auth::guest())
            <ul class="login-buttons">
                <li><a href="/login">Login</a></li>
                <li><a href="/register">Register /&nbsp;</a></li>
            </ul>
        @else
            <ul class="login-buttons">
                <li>{{ Auth::user()->email }}</li>
                <li class="ml-1">
                    <a href="/logout" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        / Logout
                    </a>
                </li>
                <form id="logout-form" action="/logout" method="POST">
                    {{ csrf_field() }}
                </form>
            </ul>
        @endif
    </div>

</div>
