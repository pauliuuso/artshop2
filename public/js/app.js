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

function DisplayImage(image)
{
    $(image).removeClass("visibility-hidden");
    $(image).addClass("animated fadeIn");
}

function PreviewSelectorLoaded(image) 
{
    var wrapper = image.parentElement;
    $(image).removeClass("visibility-hidden");
    $(image).addClass("animated fadeIn");
    CenterPreviewSelector(image);
}

function CenterPreviewSelectors()
{

    $(".preview-selector-image").each(function(index)
    {
        CenterPreviewSelector($(".preview-selector-image")[index]);
    });
}

function CenterPreviewSelector(image)
{
    var wrapper = image.parentElement;

    image.style.height = wrapper.offsetHeight + "px";
    if(image.offsetWidth > wrapper.offsetWidth)
    {
        image.style.left = "-" + (image.offsetWidth - wrapper.offsetWidth)/2 + "px";
    }
    else
    {
        var ratio = wrapper.offsetWidth/image.offsetWidth;
        image.style.left = 0;
        image.style.width = 100 + "%";
        image.style.height = "auto";
    }

    if(image.offsetHeight < wrapper.offsetHeight)
    {
        image.style.height = "auto";
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

function ArtworkPreviewLoaded(image)
{
    $(image).removeClass("visibility-hidden animated fadeIn");
    $(image).addClass("animated fadeIn");
    var wrapper = image.parentElement;
    $(wrapper).height($(image).height());
}

function SelectPreview(pictureName, element)
{
    $("#artwork-image").attr("src", "/storage/artworks/" + pictureName);
    $(".artwork-selector").addClass("artwork-selector-inactive");
    $(element).removeClass("artwork-selector-inactive");

    $('html, body').animate({
        scrollTop: $("#artwork-image").offset().top - 20
    }, 500);
}

function SelectArtworkSize(size, element, id)
{
    $("#selected-artwork-size").html(size);
    $(element).parent().find(".customization-select-option").removeClass("artwork-selected-option");
    $(element).addClass("artwork-selected-option");
    $("#artwork-size").val(id);
    GetArtworkPrice();
}

function SelectPaperMaterial(material, element)
{
    $("#selected-material").html(material);
    $(element).parent().find(".customization-select-option").removeClass("artwork-selected-option");
    $(element).addClass("artwork-selected-option");
}

function SelectFrame(frame, element)
{
    $("#frame").html(frame);
    $(element).parent().find(".customization-select-option").removeClass("artwork-selected-option");
    $(element).addClass("artwork-selected-option");
    $("#frame").val(frame);
}

$('#quantity-input').bind('DOMAttrModified textInput input keypress paste focus', function () 
{
    QuantityChanged();
});

function QuantityChanged()
{

    $("#quantity-error").html("");
    if(!$.isNumeric($("#quantity-input").val()))
    {
        $("#quantity-error").html("Please enter a number");
        return;
    }

    var quantity = $("#quantity-input").val();
    $("#quantity").html(quantity);
    $("#count").val(quantity);
    GetArtworkPrice();
}

function QuantityAdd(number)
{
    $('#quantity-input').val(parseInt($('#quantity-input').val()) + number);
    QuantityChanged();
}

/// HTTP CALLS ///
function GetArtworkPrice()
{
    $.ajax({
        type: "POST",
        url: "/artwork/getprice",
        data: {_token: $('#_token').val(), sizeId: $("#artwork-size").val(), quantity: $("#quantity-input").val()},
        success: function(data)
        {
            $("#total-price").html(data);
        },
        error: function(data)
        {
            $("#total-price").html("Something went wrong, try again.");
        }
    });
}
////////////////////


OnResizeGallery();

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


$(window).resize(function () 
{
    $(window).trigger("window:resize")
});

$(window).on("window:resize", function (e) 
{
    OnResizeGallery();
    CenterPreviewSelectors();
    $(".artwork-image-wrapper").height($(".artwork-image-wrapper img").height());
});

window.onscroll = function()
{
    FixMobileMenu();
}

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