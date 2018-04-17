// vars //

var scrollTop = window.pageYOffset;
var previousScrollTop;
var up = true;
var menuVisible = false;


function FixMobileMenu()
{
    if(!scrollTop)
    {
        scrollTop = window.pageYOffset;
    }

    var $menu = $(".mobile-menu-header");
    var $menuOffset = $menu.offset();

    if(scrollTop <= 10)
    {
        $menu.removeClass("slideOutUp");
        $menu.addClass("slideInDown");
    }
    else if(window.pageYOffset > scrollTop)
    {
        if(up)
        {
            up = !up;
            $menu.removeClass("slideInDown");
            $menu.addClass("slideOutUp");
        }
    }
    else if(window.pageYOffset == scrollTop)
    {
        
    }
    else
    {
        if(!up)
        {
            up = !up;
            $menu.removeClass("slideOutUp");
            $menu.addClass("slideInDown");
        }

    }

    scrollTop = window.pageYOffset;
}

function ToogleMenu()
{
    var $menu = $(".mobile-menu");

    if(!$menu.hasClass("slideInLeft"))
    {
        // show
        previousScrollTop = $(window).scrollTop();
        $("body").addClass("unscrollable");

        $menu.removeClass("visibility-hidden");
        $menu.removeClass("slideOutLeft");
        $menu.addClass("slideInLeft");

        menuVisible = true;
    }
    else
    {
        // hide
        $("body").removeClass("unscrollable");
        $(window).scrollTop(previousScrollTop);
        $menu.removeClass("slideInLeft");
        $menu.addClass("slideOutLeft");

        menuVisible = false;
    }

}

function ToogleDesktopMenu()
{
    var $menu = $(".desktop-menu");
    var $curtain = $(".curtain");
    var $content = $(".content");

    if($curtain.hasClass("d-none"))
    {
        // show
        $curtain.removeClass("d-none");
        $content.addClass("blur");
        $menu.removeClass("slideOutRight d-none");
        $menu.addClass("slideInRight");

        menuVisible = true;
    }
    else
    {
        // hide
        $curtain.addClass("d-none");
        $content.removeClass("blur");
        $menu.removeClass("slideInRight");
        $menu.addClass("slideOutRight");

        menuVisible = false;
    }

}

function Slide()
{
    $(".artwork-list").addClass("animated slideOutLeft");
    location.reload();
}


