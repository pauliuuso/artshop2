// vars //

var scrollTop = window.pageYOffset;
var up = true;


function FixMobileMenu()
{
    if(!scrollTop)
    {
        scrollTop = window.pageYOffset;
    }

    var $menu = $(".mobile-menu-header");
    var $menuOffset = $menu.offset();

    if(window.pageYOffset > scrollTop)
    {
        if(up)
        {
            up = !up;
            $menu.removeClass("slideInDown");
            $menu.addClass("slideOutUp");
            console.log("down " + window.pageYOffset + " " + scrollTop);
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
            console.log("up " + window.pageYOffset + " " + scrollTop);
        }

    }

    scrollTop = window.pageYOffset;
}

function ToogleMenu()
{
    var $menu = $(".mobile-menu");
    if(!$menu.hasClass("slideInLeft"))
    {
        $menu.removeClass("visibility-hidden");
        $menu.removeClass("slideOutLeft");
        $menu.addClass("slideInLeft");
    }
    else
    {
        $menu.removeClass("slideInLeft");
        $menu.addClass("slideOutLeft");
    }
}

function Slide()
{
    $(".artwork-list").addClass("animated slideOutLeft");
    location.reload();
}