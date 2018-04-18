
$("#background-selector").change(function()
{
    ShowSelectedBackground();
})

function ShowSelectedBackground()
{
    var selectedId = $("#background-selector").find(":selected").val();
    var backgroundList = $("#background-list").find(".background");
    for(var a = 0; a < backgroundList.length; a++)
    {
        if(backgroundList[a].id == "background-" + selectedId)
        {
            backgroundList[a].style.display = "block";
        }
        else
        {
            backgroundList[a].style.display = "none";
        }
    }
}

function ScrollToArt(element)
{
    $('html, body').animate({
        scrollTop: $(element).offset().top - 100
    }, 500);
}

function CalculateTopMargin(element)
{
    var $image = $(element);
    var $wrapper = $image.parent();
    var $offset = ($wrapper.height() - $image.height()) / 2;

    $image.css("margin-top", $offset);
}

function GotoCheckoutStep(previousStep, nextStep)
{
    var $nextStep = $(nextStep);
    var $previousStep = $(previousStep);

    $nextStep.removeClass("d-none");
    $nextStep.addClass("slideInRight");
    $previousStep.addClass("d-none");

    ScrollToArt("html");
}

function GotoCheckoutStepBack(currentStep, previousStep)
{
    var $previousStep = $(previousStep);
    var $currentStep = $(currentStep);

    $previousStep.removeClass("d-none");
    $previousStep.addClass("slideInLeft");
    $currentStep.addClass("d-none");

    ScrollToArt("html");
}

$(window).resize(function () 
{
    $(window).trigger("window:resize")
});

$(window).on("window:resize", function (e) 
{
    OnResizeGallery();
    $(".artwork-image-wrapper").height($(".artwork-image-wrapper img").height());
});

window.onscroll = function()
{
    FixMobileMenu();
}

window.onclick = function(element)
{
    if($(element.target).hasClass("curtain"))
    {
        if(menuVisible)
        {
            ToogleDesktopMenu();
        }
    }
}
