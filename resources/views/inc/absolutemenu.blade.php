<div class="col-12 pt-4 absolute-menu">

        <div class="col-12 text-right">
            <ul class="pl-2 desktop-cart">
                <a href="/get-cart">
                    <i class="cart-icon {{ Session::get('cart') && Session::get('cart')->totalCount ? 'full' : 'empty' }}"></i>
                </a>
                <a class="pointer ml-4" onclick="ToogleDesktopMenu();">
                    <i class="menu-icon"></i>
                </a>
            </ul>
        </div>

</div>

<div class="row">
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