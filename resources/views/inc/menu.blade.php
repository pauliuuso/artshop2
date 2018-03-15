<div class="row">
    <div class="col-12">
        <ul class="art-menu mt-3">
            <a href="/"><li>Gallery | </li></a>
            <a href="/artists"><li>Artists | </li></a>
            <a href="/about"><li>About | </li></a>
            <a href="/contacts"><li>Contacts </li></a>
            @if(!Auth::guest() && Auth::user()->role == "admin")
                <a href="/admin"><li>| Admin</li></a>
            @endif
        </ul>

        @if(Auth::guest())
            <ul class="login-buttons">
                <li><a href="/login">Login</a></li>
                <li><a href="/register">Register</a></li>
            </ul>
        @else
            <ul class="login-buttons">
                <li>{{ Auth::user()->email }}</li>
                <li>
                    <a href="/logout" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        Logout
                    </a>
                </li>
                <form id="logout-form" action="/logout" method="POST">
                    {{ csrf_field() }}
                </form>
            </ul>
        @endif

        <div>
            <p>Shopping cart: </p>
            <a href="get-cart">Items: {{ Session::has("cart") ? Session::get("cart")->totalCount : "" }}</a>
        </div>


    </div>
</div>
