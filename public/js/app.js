function ImageLoaded(image) 
{
    var wrapper = image.parentElement;
    $(image).removeClass("visibility-hidden");
    $(image).addClass("animated fadeIn");

    if(parseInt(image.offsetWidth) >= parseInt(image.offsetHeight))
    {
        $(image).css("width", "100%");
        var topOffset = (wrapper.offsetHeight - image.offsetHeight) / 2;
        $(image).css("margin-top", topOffset + "px");
    }
    else
    {
        $(image).css("height", "100%");
        $(image).css("margin-top", "0");
    }

    if(parseInt(image.offsetHeight) > wrapper.offsetHeight)
    {
        $(image).css("height", "100%");
        $(image).css("margin-top", "0");
        $(image).css("width", "");
    }
    else if(parseInt(image.offsetWidth) > wrapper.offsetWidth && image.offsetHeight < wrapper.offsetHeight)
    {
        $(image).css("width", "100%");
        $(image).css("height", "");

        var topOffset = (wrapper.offsetHeight - image.offsetHeight) / 2;
        $(image).css("margin-top", topOffset + "px");
    }

}

function WrappedImageLoaded(image)
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
        var image = $(".art-image")[index];
        $(image).css("width", "");
        $(image).css("height", "");

        ImageLoaded(image);
    });

    $(".background-image").each(function(index)
    {
        var image = $(".background-image")[index];

        WrappedImageLoaded(image);
    });
}

function ArtworkPreviewLoaded(image)
{
    $(image).removeClass("visibility-hidden fadeIn");
    $(image).addClass("fadeIn");
    var wrapper = image.parentElement;
    $(wrapper).height($(image).height());
}

function SelectPreview(pictureName, element)
{
    $("#artwork-image").attr("src", "/storage/artworks/" + pictureName);

    $('html, body').animate({
        scrollTop: $("#artwork-image").offset().top - 100
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