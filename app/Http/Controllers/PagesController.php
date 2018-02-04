<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PagesController extends Controller
{

    public function __construct()
    {
        $this->middleware("auth", ["except" => ["index", "artists", "about", "contacts"]]);
    }

    public function index()
    {
        $title = "Gallery";
        return view("gallery/index")->with("title", $title);
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

    public function admin()
    {
        if(auth()->user()->role != "admin")
        {
            return redirect("/")->with("error", "Unauthorized");
        }

        return view("menu/admin");
    }

}
