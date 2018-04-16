<div class="row">

    <div class="col-sm-3">
        <div class="desktop-menu-button d-none d-sm-block">
            <a class="pointer" onclick="ToogleMenu(true);">
                <i class="menu-icon"></i>
            </a>
        </div>
    </div>
    
    {{-- <div class="col-12 col-md-6 d-none d-sm-block">
        <ul class="art-menu mt-3">
            <a href="/"><li>Gallery | </li></a>
            <a href="/artists"><li>Artists | </li></a>
            <a href="/about"><li>About | </li></a>
            <a href="/contacts"><li>Contacts </li></a>
            @if(!Auth::guest() && Auth::user()->role == "admin")
                <a href="/admin"><li>| Admin</li></a>
            @endif
        </ul>
    </div> --}}


    <div class="col-12 col-sm-9 pt-4 d-none d-sm-block">

        <ul class="pl-2 desktop-cart">
            {{-- <a href="#" class="profile">
                <i class="{{Auth::guest() ? 'profile-icon' : 'profile-icon-logged'}} mr-3"></i>
            </a> --}}
            <a href="/get-cart">
                <i class="cart-icon {{ Session::get('cart') && Session::get('cart')->totalCount ? 'full' : 'empty' }}"></i>
            </a>
        </ul>

        <div class="user-dropdown animated p-2 d-none fadeOutFast">
            <div class="dropdown-arrow"></div>
            <ul>
                @if(Auth::guest())
                    <a href="/login">
                        <li><p>Login</p></li>
                    </a>
                    <a href="/register">
                        <li><p>Register</p></li>
                    </a>
                @else
                    <li>
                        <a href="#">
                            <p>Profile</p>
                        </a>
                    </li>
                    <li>
                        <a href="/logout" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <p>Logout</p>
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

    <div class="menu-close" onclick="ToogleMenu()">
        x
    </div>

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

<script>
    $(".profile, .user-dropdown").mouseover(function()
    {  
        // show
        $dropdown = $(".user-dropdown");
        $dropdown.removeClass("fadeOutFast d-none");
        $dropdown.addClass("fadeInFast");
    });

    $(".profile, .user-dropdown").mouseout(function()
    {
        // hide
        $dropdown = $(".user-dropdown");
        $dropdown.addClass("fadeOutFast");
        $dropdown.removeClass("fadeInFast");
    });
</script>