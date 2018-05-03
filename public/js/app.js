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

function GetArtistWorks(count, id)
{
    $.ajax({
        type: "GET",
        url: "/artwork/getart",
        data: {_token: $('#_token').val(), artowork_count: count, artwork_id: id},
        success: function(data)
        {
            
        },
        error: function(data)
        {
            // $("#total-price").html("Something went wrong, try again.");
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

function CenterImage(id)
{
    var $element = $(id);
    var imageWidth = $element.width();
    var imageHeight = $element.height();
    var wrapperWidth = $('.intro-wrapper').width();
    var wrapperHeight = $('.intro-wrapper').height();

    $element.css("left", "-" + (imageWidth - wrapperWidth)/2 + "px")
}

function ShowDescription(element)
{
    $element = $(element);
    $description = $('.artwork-description');
    $scroller = $('.artwork-scroller');

    if($element.height() < $description.height())
    {
        $description.css('height', ($element.height() - 50) + 'px');
        $scroller.removeClass('visibility-hidden');
    }
    else
    {
        $description.addClass('unscrollable');
    }

    $description.removeClass('visibility-hidden');
}

$(window).resize(function () 
{
    $(window).trigger("window:resize")
});

$(window).on("window:resize", function (e) 
{
    OnResizeGallery();
    $(".artwork-image-wrapper").height($(".artwork-image-wrapper img").height());
    CenterImage("#intro-video");
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

// vars //

var scrollTop = window.pageYOffset;
var previousScrollTop;
var up = true;
var menuVisible = false;


function FixMobileMenu()
{
    if(!scrollTop)
    {
        scrollTop = window.pageYOffset;
    }

    var $menu = $(".mobile-menu-header");
    var $menuOffset = $menu.offset();

    if(scrollTop <= 10)
    {
        $menu.removeClass("slideOutUp");
        $menu.addClass("slideInDown");
    }
    else if(window.pageYOffset > scrollTop)
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
        // show
        previousScrollTop = $(window).scrollTop();
        $("body").addClass("unscrollable");

        $menu.removeClass("visibility-hidden");
        $menu.removeClass("slideOutLeft");
        $menu.addClass("slideInLeft");

        menuVisible = true;
    }
    else
    {
        // hide
        $("body").removeClass("unscrollable");
        $(window).scrollTop(previousScrollTop);
        $menu.removeClass("slideInLeft");
        $menu.addClass("slideOutLeft");

        menuVisible = false;
    }

}

function ToogleDesktopMenu()
{
    var $menu = $(".desktop-menu");
    var $curtain = $(".curtain");
    var $content = $(".content");

    if(!$curtain.hasClass("curtain-appear"))
    {
        // show
        $curtain.addClass("curtain-appear");
        $content.addClass("blur");
        $menu.removeClass("slideOutRight d-none");
        $menu.addClass("slideInRight");

        menuVisible = true;
    }
    else
    {
        // hide
        $curtain.removeClass("curtain-appear");
        $curtain.removeClass("curtain-appear");
        $content.removeClass("blur");
        $menu.removeClass("slideInRight");
        $menu.addClass("slideOutRight");

        menuVisible = false;
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