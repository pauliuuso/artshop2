<?php

namespace App;

class Cart
{
    public $artworks = array();

    public $orderId = 0;

    public function __construct($oldCart)
    {
        if($oldCart)
        {
            $this->artworks = $oldCart->artworks;
            $this->orderId = $oldCart->orderId;
        }
    }

    public function add($artwork, $id, $size, $count)
    {
        $storedArtwork = ["count" => $count, "price" => 0, "size" => "", "artwork" => $artwork];

        $storedArtwork["count"] = $count;
        $storedArtwork["price"] = $size->price;
        $storedArtwork["size"] = $size->width . "x" . $size->height;

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
        unset($this->artworks[$index]);

        if(sizeof($this->artworks) == 0)
        {
            $this->removeall();
        }
    }

    public function removeall()
    {
        $this->artworks = array();
        $this->orderId = 0;
    }

    public function setOrderId($id)
    {
        $this->orderId = $id;
    }

    public function getFullPrice()
    {
        $price = 0;

        foreach($this->artworks as $artwork)
        {
            $price += $artwork["price"] * $artwork["count"];
        }

        return $price;
    }

    public function getTotalCount()
    {
        $count = 0;

        foreach($this->artworks as $artwork)
        {
            $count += $artwork["count"];
        }

        return $count;
    }

}