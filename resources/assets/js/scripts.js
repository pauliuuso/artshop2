
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
