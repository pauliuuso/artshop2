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
        $storedArtwork["price"] = $size->price * $count;
        $storedArtwork["size"] = $size->width . "x" . $size->height;

        $this->totalCount += $count;
        $this->totalPrice += $storedArtwork["price"];

        foreach($this->artworks as $index => $artwork)
        {
            if($artwork["size"] == $storedArtwork["size"] && $artwork["artwork"]->id == $id)
            {
                $this->artworks[$index]["count"] += $count;
                return;
            }
        }

        array_push($this->artworks, $storedArtwork);

    }

    public function remove($index)
    {
        $count = $this->artworks[$index]["count"];
        $price = $this->artworks[$index]["price"];
        $this->totalPrice -= $price * $count;
        $this->totalCount -= $count;
        unset($this->artworks[$index]);
    }

    public function removeall()
    {
        $this->artworks = array();
        $this->totalCount = 0;
        $this->totalPrice = 0;
    }

}