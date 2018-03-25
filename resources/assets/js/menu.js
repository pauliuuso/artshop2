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