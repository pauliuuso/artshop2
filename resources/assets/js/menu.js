// vars //

var scrollTop = window.pageYOffset;
var previousScrollTop;
var up = true;


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

function ToogleMenu(desktop)
{
    var $menu = $(".mobile-menu");
    var $menuClose = $(".menu-close");

    if(desktop)
    {
        $menu.css("width", "300");
        $menuClose.css("display", "block");
    }
    else
    {
        $menuClose.css("display", "none");
    }

    if(!$menu.hasClass("slideInLeft"))
    {
        // show
        previousScrollTop = $(window).scrollTop();
        $("body").addClass("unscrollable");

        $menu.removeClass("visibility-hidden");
        $menu.removeClass("slideOutLeft");
        $menu.addClass("slideInLeft");
    }
    else
    {
        // hide
        $("body").removeClass("unscrollable");
        $(window).scrollTop(previousScrollTop);
        $menu.removeClass("slideInLeft");
        $menu.addClass("slideOutLeft");
    }

}

function Slide()
{
    $(".artwork-list").addClass("animated slideOutLeft");
    location.reload();
}


