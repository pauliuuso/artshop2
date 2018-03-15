<?php

namespace App;

class Cart
{
    public $artworks = array();
    public $totalCount = 0;
    public $totalPrice = 0;

    public function __construct($oldCart)
    {
        if($oldCart)
        {
            $this->artworks = $oldCart->artworks;
            $this->totalCount = $oldCart->totalCount;
            $this->totalPrice = $oldCart->totalPrice;
        }
    }

    public function add($artwork, $id, $size, $count)
    {
        $storedArtwork = ["count" => $count, "price" => 0, "size" => "", "artwork" => $artwork];

        $storedArtwork["count"] = $count;

        if($size == "small")
        {
            $storedArtwork["price"] = $artwork->smallprice * $count;
            $storedArtwork["size"] = $artwork->width . "x" . $artwork->height;
        }
        else if($size == "medium")
        {
            $storedArtwork["price"] = $artwork->mediumprice * $count;
            $storedArtwork["size"] = ($artwork->width * 2) . "x" . ($artwork->height * 2);
        }
        else if($size === "big")
        {
            $storedArtwork["price"] = $artwork->bigprice * $count;
            $storedArtwork["size"] = ($artwork->width * 3) . "x" . ($artwork->height * 3);
        }

        $this->totalCount += $count;
        $this->totalPrice += $storedArtwork["price"];
        array_push($this->artworks, $storedArtwork);

    }

}