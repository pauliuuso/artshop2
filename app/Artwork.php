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

    public function getBackground()
    {
        return $this->hasOne("App\Background", "id", "background_id");
    }

    public function getSizes()
    {
        return $this->hasMany("App\Size");
    }

}
