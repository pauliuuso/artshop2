
function CenterImage()
{
    console.log("center");
    if(this.imageWidth > this.wrapperWidth)
    {
    this.smallerThanWrapper = false;
    this.picture.nativeElement.style.left = "-" + (this.picture.nativeElement.width - this.wrapperWidth)/2 + "px";
    }
    else
    {
    this.smallerThanWrapper = true;
    this.picture.nativeElement.style.left = 0;
    this.picture.nativeElement.style.width = this.wrapperWidth + "px";
    }

    if(this.picture.nativeElement.height > this.wrapperHeight)
    {
    this.picture.nativeElement.style.top = "-" + (this.picture.nativeElement.height - this.wrapperHeight)/2 + "px";
    }
}