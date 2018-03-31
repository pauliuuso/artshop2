<div class="row">
    <div class="col-12 col-md-6 offset-md-3 d-none d-sm-block">
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

    <div class="col-12 col-md-3 pt-4 d-none d-sm-block">

        <ul class="pl-2 desktop-cart">
            <a href="#" onclick="ToogleUserDropDown()">
                <i class="profile-icon mr-3"></i>
            </a>
            <a href="/get-cart">
                <i class="cart-icon {{ Session::get('cart') && Session::get('cart')->totalCount ? 'full' : 'empty' }}"></i>
            </a>
        </ul>

        <div class="user-dropdown text-center animated d-none fadeOutFast">
            <div class="dropdown-arrow"></div>
            <ul>
                @if(Auth::guest())
                    <a href="/login">
                        <li><p>LOGIN</p></li>
                    </a>
                    <a href="/register">
                        <li><p>REGISTER</p></li>
                    </a>
                @else
                    <li>
                        <a href="#">
                            <p>PROFILE</p>
                        </a>
                    </li>
                    <li>
                        <a href="/logout" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <p>LOGOUT</p>
                        </a>
                    </li>
                    <form id="logout-form" action="/logout" method="POST">
                        {{ csrf_field() }}
                    </form>
                @endif
            </ul>
        </div>

    </div>

    <div class="d-sm-none mobile-menu-header position-fixed animated">

        <div class="mobile-menu-button">
            <a class="pointer" onclick="ToogleMenu();">
                <i class="menu-icon"></i>
            </a>
        </div>

        <div class="mobile-cart">
            <a class="pointer" href="/get-cart">
                <i class="cart-icon {{ Session::get('cart') && Session::get('cart')->totalCount ? 'full' : 'empty' }}"></i>
            </a>
        </div>

    </div>



</div>

<div class="mobile-menu visibility-hidden uppercase animated">

    <div class="mobile-links-container">
        <ul>
            <a href="/">
                <li class="marvel">Gallery</li>
            </a>
            <a href="/artists">
                <li>Artists</li>
            </a>
            <a href="/about">
                <li>About</li>
            </a>
            <a href="/contacts">
                <li>Contacts</li>
            </a>
            <a href="/admin">
                @if(!Auth::guest() && Auth::user()->role == "admin")
                <li>Admin</li>
                @endif
            </a>
            @if(Auth::guest())
                <a href="/login">
                    <li>Login</li>
                </a>
                <a href="/register">
                    <li>Register</li>
                </a>
            @else
                <a>
                    <li>{{ Auth::user()->email }}</li>
                </a>
                <a href="/logout" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <li>
                        Logout
                    </li>
                </a>
                <form id="logout-form" action="/logout" method="POST">
                    {{ csrf_field() }}
                </form>
            @endif
        </ul>
    </div>

    <div class="mobile-menu-footer text-capitalize text-center">
        <p>Copyright ArtShop</p>
        <p><?php echo date("Y"); ?></p>
    </div>

</div>