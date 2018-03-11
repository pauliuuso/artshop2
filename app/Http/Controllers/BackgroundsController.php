<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Background;

class BackgroundsController extends Controller
{

    public function __construct()
    {
        $this->middleware("auth");
    }

    public function index()
    {

        if(auth()->user()->role != "admin")
        {
            return redirect("/")->with("error", "Unauthorized");
        }

        $backgrounds = Background::get();

        return view("backgrounds/index")->with(["backgrounds" => $backgrounds]);

    }

    public function store(Request $request)
    {
        if(auth()->user()->role != "admin")
        {
            return redirect("/")->with("error", "Unauthorized");
        }

        $this->validate($request,
        [
            "title" => "required",
            "width" => "required",
            "height" => "required",
            "picture" => "required|image|max:10000"
        ]);

        $backgroundNameToStore = "";


        if($request->hasFile("picture"))
        {
            $pictureNameWithExt = $request->file("picture")->getClientOriginalName();
            $pictureName = pathinfo($pictureNameWithExt, PATHINFO_FILENAME);
            $extension = $request->file("picture")->getClientOriginalExtension();
            $backgroundNameToStore = $pictureName . "-" . substr(md5(openssl_random_pseudo_bytes(20)),-20) . "." . $extension;
            $path = $request->file("picture")->storeAs("public/backgrounds", $backgroundNameToStore);
        }

        $background = new Background;
        $background->title = $request->input("title");
        $background->width = $request->input("width");
        $background->height = $request->input("height");
        if($backgroundNameToStore != "")
        {
            $background->background_name = $backgroundNameToStore;
        }
        $background->save();
        return redirect("/backgrounds/manage")->with("success", "Background added!");
    }

    public function edit($id)
    {
        if(auth()->user()->role != "admin")
        {
            return redirect("/")->with("error", "Unauthorized");
        }

        $background = Background::find($id);

        return view("backgrounds/edit")->with(["background" => $background]);
    }

    public function update(Request $request, $id)
    {

        if(auth()->user()->role != "admin")
        {
            return redirect("/")->with("error", "Unauthorized");
        }

        $this->validate($request,
        [
            "title" => "required",
            "width" => "required",
            "height" => "required",
            "picture" => "image|max:10000"
        ]);

        $background = Background::find($id);
        $backgroundNameToStore = "";

        if($request->hasFile("picture"))
        {
            $pictureNameWithExt = $request->file("picture")->getClientOriginalName();
            $pictureName = pathinfo($pictureNameWithExt, PATHINFO_FILENAME);
            $extension = $request->file("picture")->getClientOriginalExtension();
            $backgroundNameToStore = $pictureName . "-" . substr(md5(openssl_random_pseudo_bytes(20)),-20) . "." . $extension;
            $path = $request->file("picture")->storeAs("public/backgrounds", $backgroundNameToStore);
            Storage::delete("public/backgrounds/" . $background->background_name);
        }

        $background->title = $request->input("title");
        $background->width = $request->input("width");
        $background->height = $request->input("height");
        if($backgroundNameToStore != "")
        {
            $background->background_name = $backgroundNameToStore;
        }
        $background->save();
        return redirect("backgrounds/manage")->with("success", "Background updated!");
    }

    public function destroy($id)
    {

        if(auth()->user()->role != "admin")
        {
            return redirect("/")->with("error", "Unauthorized");
        }

        $background = Background::find($id);
        Storage::delete("public/backgrounds/" . $background->background_name);
        $background->delete();
        return redirect("backgrounds/manage")->with("success", "Background removed!");
    }

}
