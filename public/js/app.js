function ImageLoaded(image) 
{
    var wrapper = image.parentElement;
    $(image).removeClass("visibility-hidden");
    $(image).addClass("animated fadeIn");

    if(image.offsetWidth > wrapper.offsetWidth)
    {
        image.style.left = "-" + (image.offsetWidth - wrapper.offsetWidth)/2 + "px";
    }
    else
    {
        image.style.left = 0;
        image.style.width = wrapper.offsetWidth + "px";
    }

    if(image.offsetHeight > wrapper.offsetHeight)
    {
        image.style.top = "-" + (image.height - wrapper.offsetWidth) / 2 + "px";
    }
}

function OnResizeGallery()
{
    $(".art-image").each(function(index)
    {
        var wrapper = $(".art-image")[index].parentElement;
        var image = $(".art-image")[index];

        if(image.offsetWidth > wrapper.offsetWidth)
        {
            image.style.left = "-" + (image.offsetWidth - wrapper.offsetWidth)/2 + "px";
        }
        else
        {
            image.style.left = 0;
            image.style.width = wrapper.offsetWidth + "px";
        }

        if(image.offsetHeight > wrapper.offsetHeight)
        {
            image.style.top = "-" + (image.height - wrapper.offsetWidth) / 2 + "px";
        }
    });
}
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