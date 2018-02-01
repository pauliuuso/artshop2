<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Artwork;

class ArtworksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $artworks = Artwork::orderBy("created_at", "asc")->paginate(12);
        return view("gallery/index")->with("artworks", $artworks);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("gallery/add");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,
        [
            "title" => "required",
            "description" => "required",
            "year" => "required",
            "smallprice" => "required",
            "mediumprice" => "required",
            "bigprice" => "required",
            "thumbnail" => "required|image|max:10000",
            "picture" => "required|image|max:10000"
        ]);

        $thumbnailNameToStore = "";
        $pictureNameToStore = "";

        if($request->hasFile("thumbnail"))
        {
            $thumbnailNameWithExt = $request->file("thumbnail")->getClientOriginalName();
            $thumbnailName = pathinfo($thumbnailNameWithExt, PATHINFO_FILENAME);
            $extension = $request->file("thumbnail")->getClientOriginalExtension();
            $thumbnailNameToStore = $thumbnailName . "-" . substr(md5(openssl_random_pseudo_bytes(20)),-20) . "." . $extension;
            $path = $request->file("thumbnail")->storeAs("public/artworks", $thumbnailNameToStore);
        }

        if($request->hasFile("picture"))
        {
            $pictureNameWithExt = $request->file("picture")->getClientOriginalName();
            $pictureName = pathinfo($pictureNameWithExt, PATHINFO_FILENAME);
            $extension = $request->file("picture")->getClientOriginalExtension();
            $pictureNameToStore = $pictureName . "-" . substr(md5(openssl_random_pseudo_bytes(20)),-20) . "." . $extension;
            $path = $request->file("picture")->storeAs("public/artworks", $pictureNameToStore);
        }

        $artwork = new Artwork;
        $artwork->title = $request->input("title");
        $artwork->category = $request->input("category");
        $artwork->author_id = $request->input("author");
        $artwork->description = $request->input("description");
        $artwork->year = $request->input("year");
        $artwork->smallprice = $request->input("smallprice");
        $artwork->mediumprice = $request->input("mediumprice");
        $artwork->bigprice = $request->input("bigprice");
        if($thumbnailNameToStore != "")
        {
            $artwork->thumbnail_name = $thumbnailNameToStore;
        }
        if($pictureNameToStore != "")
        {
            $artwork->picture_name = $pictureNameToStore;
        }
        $artwork->save();

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $artwork = Artwork::find($id);
        return view("gallery/artwork")->with("artwork", $artwork);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $artwork = Artwork::find($id);
        return view("gallery/edit")->with("artwork", $artwork);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request,
        [
            "title" => "required",
            "description" => "required",
            "year" => "required",
            "smallprice" => "required",
            "mediumprice" => "required",
            "bigprice" => "required",
            "thumbnail" => "image|max:10000",
            "picture" => "image|max:10000"
        ]);

        $thumbnailNameToStore = "";
        $pictureNameToStore = "";
        $artwork = Artwork::find($id);

        if($request->hasFile("thumbnail"))
        {
            $thumbnailNameWithExt = $request->file("thumbnail")->getClientOriginalName();
            $thumbnailName = pathinfo($thumbnailNameWithExt, PATHINFO_FILENAME);
            $extension = $request->file("thumbnail")->getClientOriginalExtension();
            $thumbnailNameToStore = $thumbnailName . "-" . substr(md5(openssl_random_pseudo_bytes(20)),-20) . "." . $extension;
            $path = $request->file("thumbnail")->storeAs("public/artworks", $thumbnailNameToStore);
            Storage::delete("public/artworks/" . $artwork->thumbnail_name);
        }

        if($request->hasFile("picture"))
        {
            $pictureNameWithExt = $request->file("picture")->getClientOriginalName();
            $pictureName = pathinfo($pictureNameWithExt, PATHINFO_FILENAME);
            $extension = $request->file("picture")->getClientOriginalExtension();
            $pictureNameToStore = $pictureName . "-" . substr(md5(openssl_random_pseudo_bytes(20)),-20) . "." . $extension;
            $path = $request->file("picture")->storeAs("public/artworks", $pictureNameToStore);
            Storage::delete("public/artworks/" . $artwork->picture_name);
        }

        $artwork->title = $request->input("title");
        $artwork->category = $request->input("category");
        $artwork->author_id = $request->input("author");
        $artwork->description = $request->input("description");
        $artwork->year = $request->input("year");
        $artwork->smallprice = $request->input("smallprice");
        $artwork->mediumprice = $request->input("mediumprice");
        $artwork->bigprice = $request->input("bigprice");
        if($thumbnailNameToStore != "")
        {
            $artwork->thumbnail_name = $thumbnailNameToStore;
        }
        if($pictureNameToStore != "")
        {
            $artwork->picture_name = $pictureNameToStore;
        }
        $artwork->save();
        return redirect("/")->with("success", "Artwork updated!");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $artwork = Artwork::find($id);
        Storage::delete("public/artworks/" . $artwork->thumbnail_name);
        Storage::delete("public/artworks/" . $artwork->picture_name);
        $artwork->delete();
        return redirect("/")->with("success", "Artwork removed!");
    }

}
