<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PagesController extends Controller
{
    public function index()
    {
        return view("gallery/index");
    }

    public function artists()
    {
        return view("menu/artists");
    }

    public function about()
    {
        return view("menu/about");
    }

    public function contacts()
    {
        return view("menu/contacts");
    }

}
