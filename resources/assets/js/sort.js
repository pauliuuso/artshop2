function ToogleSort()
{
    var $sort = $("#mobile-sort-links");

    if($sort.hasClass("sort-visible"))
    {
        // hide
        $sort.removeClass("sort-visible");
        $sort.addClass("fadeOut");
        $(".scroll-down").removeClass("d-none");
        $(".scroll-down").addClass("fadeInFast");
        $(".scroll-up").addClass("d-none");
        $sort.css("height", "0");
        $("body").removeClass("unscrollable");
    }
    else
    {
        // show
        $("body").addClass("unscrollable");
        var $offsetTop = $sort.offset().top;
        var $height = $(window).height();
        var $sortHeight = $height - $offsetTop + $(window).scrollTop() + 50;
        var $optionsHeight = $sortHeight - 20;

        $sort.removeClass("sort-hidden");
        $(".scroll-down").removeClass("fadeInFast");
        $(".scroll-down").addClass("d-none");
        $(".scroll-up").removeClass("d-none");
        $(".scroll-up").addClass("fadeInFast");
        $sort.addClass("sort-visible");
        $sort.css("height", $sortHeight + "px");
        $(".mobile-links-area").css("height", $optionsHeight + "px");
    }
}


function ToogleOptions(element)
{
    var $element = $(element);

    if($element.hasClass("opened"))
    {
        $element.removeClass("opened")
        $element.next("div").css("height", "0");
    }
    else
    {
        console.log($element.next("div").find("span"));
        $element.addClass("opened");
        $element.next("div").css("height", $element.next("div").find("span").height() + "px");
    }
}

function ToogleOptionDesktop(name)
{
    var $element = $("." + name);

    if($element.hasClass("d-none"))
    {
        $(".desktop-sort-option").addClass("d-none");
        $element.removeClass("d-none");
        $element.addClass("fadeInFast");
    }
    else
    {
        $element.addClass("d-none");
        $element.removeClass("fadeInFast");
    }
}