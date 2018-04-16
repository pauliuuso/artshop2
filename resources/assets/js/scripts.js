
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

    console.log("offset: " + $offset);

    $image.css("margin-top", $offset);

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

// window.onclick = function(element)
// {
//     $dropdown = $(".user-dropdown");

//     console.log($(element).hasClass("profile"));
//     console.log($(element));

//     if(!$(element).hasClass("user-dropdown") && !$(element).hasClass("profile-icon") && !$(element).hasClass("profile-icon-logged"))
//     {
//         $dropdown.addClass("fadeOutFast");
//         $dropdown.removeClass("fadeInFast");
//     }
// }
