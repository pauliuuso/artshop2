<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Artwork extends Model
{
    public $primaryKey = "id";

    public function getOneCategory()
    {
        return $this->hasOne("App\Category", "id", "category");
    }

    public function getAuthor()
    {
        return $this->hasOne("App\User", "id", "author_id");
    }

}
