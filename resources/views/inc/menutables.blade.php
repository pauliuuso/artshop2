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

<div class="desktop-menu d-none uppercase animated">

    <a class="menu-cart"  href="/get-cart">
        <i class="cart-icon {{ Session::get('cart') && Session::get('cart')->totalCount ? 'full' : 'empty' }}"></i>
    </a>

    <a class="pointer menu-close" onclick="ToogleDesktopMenu();">
        <i class="cross-icon"></i>
    </a>

    <div class="mobile-links-container ml-2">
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

            <div class="purchase-beam menu-beam mt-5"></div>

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
        <p>Terms and conditions | FAQ</p>
    </div>

</div>