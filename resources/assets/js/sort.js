function ToogleSort()
{
    var $sort = $("#mobile-sort-links");
    var $offsetTop = $sort.offset().top;
    var $height = $(window).height();
    var $sortHeight = $height - $offsetTop;

    if($sort.hasClass("sort-visible"))
    {
        // hide
        $sort.removeClass("sort-visible");
        $sort.addClass("fadeOut");
        $(".scroll-up").addClass("fadeOutFast");
        $(".scroll-up").removeClass("fadeInFast");
        $(".scroll-down").removeClass("fadeOutFast");
        $(".scroll-down").addClass("fadeInFast");
        $sort.css("height", "0");
    }
    else
    {
        // show
        $sort.removeClass("sort-hidden");
        $(".scroll-up").addClass("fadeInFast");
        $(".scroll-up").removeClass("fadeOutFast");
        $(".scroll-up").removeClass("d-none");
        $(".scroll-down").removeClass("fadeInFast");
        $(".scroll-down").addClass("fadeOutFast");
        $sort.addClass("sort-visible");
        $sort.css("height", $sortHeight + "px");
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
        $element.addClass("opened")
        $element.next("div").css("height", $(element).first().height() + "px");
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