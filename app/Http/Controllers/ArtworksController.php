<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Artwork;
use App\Category;
use App\Background;
use App\User;

class ArtworksController extends Controller
{

    public function __construct()
    {
        $this->middleware("auth", ["except" => ["index", "show"]]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($filter = "", $id = "")
    {
        $artworksPerPage = 1;
        $firstCategory = Category::orderBy("name")->select("id")->first();
        $firstArtist = User::orderBy("name")->select("id")->where("role", "author")->first();
        $categories = "";
        $authors = "";
        $artworks = "";

        if($filter == "")
        {
            $artworks = Artwork::with(["getAuthor" => function($query)
            {
                $query->select('id', 'name', 'surname');
            }])->orderBy("created_at", "asc")->paginate($artworksPerPage);
        }
        else if($filter == "kind")
        {
            $categories = Category::orderBy("name")->get();
            $artworks = Artwork::with(["getAuthor" => function($query)
            {
                $query->select('id', 'name', 'surname');
            }])->where("category", $id)->orderBy("created_at", "asc")->paginate($artworksPerPage);
        }
        else if($filter == "artist")
        {
            $authors = User::orderBy("name")->select("id", "name", "surname")->where("role", "author")->get();
            $artworks = Artwork::with(["getAuthor" => function($query)
            {
                $query->select('id', 'name', 'surname');
            }])->where("author_id", $id)->orderBy("created_at", "asc")->paginate($artworksPerPage);
        }

        return view("gallery/index")->with(["artworks" => $artworks, "filter" => $filter, "firstCategory" => $firstCategory, "firstArtist" => $firstArtist, "categories" => $categories, "authors" => $authors, "sortId" => $id]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        if(auth()->user()->role != "admin")
        {
            return redirect("/")->with("error", "Unauthorized");
        }

        $backgrounds = Background::select("title", "id", "background_name")->get();
        $backgroundIdsAndTitles = $backgrounds->pluck("title", "id");
        $authors = User::all()->where("role", "author");
        $authorNamesAndIds = array();
        foreach($authors as $a)
        {
            $authorNamesAndIds[$a->id] = $a->name . " " . $a->surname;
        }
        $categories = Category::all();
        $categories = $categories->pluck("name", "id");

        return view("gallery/add")->with
        ([
            "categories" => $categories,
            "authors" => $authorNamesAndIds,
            "backgroundIdsAndTitles" => $backgroundIdsAndTitles, 
            "backgrounds" => $backgrounds
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        if(auth()->user()->role != "admin")
        {
            return redirect("/")->with("error", "Unauthorized");
        }

        $this->validate($request,
        [
            "title" => "required",
            "description" => "required",
            "width" => "required",
            "height" => "required",
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

            //////////////// Generate preview with interior background //////////////////
            if($request->input("background") != null)
            {
                $background = Background::find($request->input("background"));
                $artworksPath = Storage::disk('local')->getDriver()->getAdapter()->getPathPrefix() . "/public/artworks/";
                $backgroundsPath = Storage::disk('local')->getDriver()->getAdapter()->getPathPrefix() . "/public/backgrounds/";
                $uploadedImage = $artworksPath . $pictureNameToStore;
                $previewBackground = $backgroundsPath . "/" . $background->background_name;
                $backgroundDimensions = getimagesize($previewBackground);
                $assumedBackgroundWidthRatio = $backgroundDimensions[0]/$background->width;
                $artworkDimensions = getimagesize($uploadedImage);
                $assumedArtworkDimensions = array($request->input("width") * $assumedBackgroundWidthRatio, $request->input("height") * $assumedBackgroundWidthRatio);
    
                $previewImage = imagecreatetruecolor($backgroundDimensions[0], $backgroundDimensions[1]);
    
                $image;
    
                if(strcasecmp($extension, "png") == 0)
                {
                    $image = imagecreatefrompng($uploadedImage);
                }
                else if(strcasecmp($extension, "jpg") == 0 || strcasecmp($extension, "jpeg"))
                {
                    $image = imagecreatefromjpeg($uploadedImage);
                }
    
                $background = imagecreatefromjpeg($previewBackground);
                $previewNameToStore = "/preview-" . $pictureNameToStore;
                imagecopyresampled($previewImage, $background, 0, 0, 0, 0, $backgroundDimensions[0], $backgroundDimensions[1], $backgroundDimensions[0], $backgroundDimensions[1]);
                imagecopyresampled($previewImage, $image, $backgroundDimensions[0]/2 - $assumedArtworkDimensions[0]/2, $backgroundDimensions[1]/2 - $assumedArtworkDimensions[1]/2 - $backgroundDimensions[1]/6, 0, 0, $assumedArtworkDimensions[0], $assumedArtworkDimensions[1], $artworkDimensions[0], $artworkDimensions[1]);
    
                if(strcasecmp($extension, "png") == 0)
                {
                    imagepng($previewImage, $artworksPath . $previewNameToStore);
                }
                else if(strcasecmp($extension, "jpg") == 0 || strcasecmp($extension, "jpeg"))
                {
                    imagejpeg($previewImage, $artworksPath . $previewNameToStore);
                }
            }
            ///////////////////////////////////////////////////////////////////////////////////////////////

        }

        $artwork = new Artwork;
        $artwork->title = $request->input("title");
        $artwork->category = $request->input("category");
        $artwork->author_id = $request->input("author");
        $artwork->description = $request->input("description");
        $artwork->width = $request->input("width");
        $artwork->height = $request->input("height");
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
        if($request->input("background") != null && $previewNameToStore != "")
        {
            $artwork->preview_name = $previewNameToStore;
        }
        $artwork->background_id = $request->input("background");
        $artwork->save();
        return redirect("/")->with("success", "Artwork added!");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $artwork = Artwork::with(["getAuthor" => function($query)
        {
            $query->select("id", "name", "surname", "description");
        }])->find($id);

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
        $backgrounds = Background::select("title", "id", "background_name")->get();
        $backgroundIdsAndTitles = $backgrounds->pluck("title", "id");

        if(auth()->user()->role != "admin")
        {
            return redirect("/")->with("error", "Unauthorized");
        }

        $category = $artwork->getOneCategory->id;
        $author = $artwork->getAuthor->id;
        $selectedBackground = 0;
        if($artwork->background_id != null)
        {
            $selectedBackground = $artwork->getBackground->id;
        }
        $authors = User::all()->where("role", "author");
        $authorNamesAndIds = array();
        foreach($authors as $a)
        {
            $authorNamesAndIds[$a->id] = $a->name . " " . $a->surname;
        }
        $categories = Category::all();
        $categories = $categories->pluck("name", "id");

        return view("gallery/edit")->with(
        [
            "artwork" => $artwork,
            "backgroundIdsAndTitles" => $backgroundIdsAndTitles,
            "backgrounds" => $backgrounds,
            "selectedBackground" => $selectedBackground,
            "categories" => $categories,
            "category" => $category, 
            "author" => $author, 
            "authors" => $authorNamesAndIds
        ]);
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

        if(auth()->user()->role != "admin")
        {
            return redirect("/")->with("error", "Unauthorized");
        }

        $this->validate($request,
        [
            "title" => "required",
            "description" => "required",
            "width" => "required",
            "height" => "required",
            "year" => "required",
            "smallprice" => "required",
            "mediumprice" => "required",
            "bigprice" => "required",
            "thumbnail" => "image|max:10000",
            "picture" => "image|max:10000"
        ]);

        $thumbnailNameToStore = "";
        $pictureNameToStore = "";
        $previewNameToStore = "";
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

        //////////////// Generate preview with interior background //////////////////
        if($request->input("background"))
        {
            $picture = Artwork::find($id);
            $background = Background::find($request->input("background"));
            if(is_null($request->input("background")))
            {
                $background = Background::find($picture->background_id);
            }
            $artworksPath = Storage::disk('local')->getDriver()->getAdapter()->getPathPrefix() . "/public/artworks/";
            $backgroundsPath = Storage::disk('local')->getDriver()->getAdapter()->getPathPrefix() . "/public/backgrounds/";
            $uploadedImage = $artworksPath . $artwork->picture_name;
            $extension = pathinfo($artwork->picture_name, PATHINFO_EXTENSION);
            $pictureName = $picture->picture_name;
            $previewBackground = $backgroundsPath . "/" . $background->background_name;
            $backgroundDimensions = getimagesize($previewBackground);
            $assumedBackgroundWidthRatio = $backgroundDimensions[0]/$background->width;
            $artworkDimensions = getimagesize($uploadedImage);
            $assumedArtworkDimensions = array($request->input("width") * $assumedBackgroundWidthRatio, $request->input("height") * $assumedBackgroundWidthRatio);

            $previewImage = imagecreatetruecolor($backgroundDimensions[0], $backgroundDimensions[1]);

            $image;

            if(strcasecmp($extension, "png") == 0)
            {
                $image = imagecreatefrompng($uploadedImage);
            }
            else if(strcasecmp($extension, "jpg") == 0 || strcasecmp($extension, "jpeg"))
            {
                $image = imagecreatefromjpeg($uploadedImage);
            }

            $background = imagecreatefromjpeg($previewBackground);
            $previewNameToStore = "preview-" . $pictureName;
            imagecopyresampled($previewImage, $background, 0, 0, 0, 0, $backgroundDimensions[0], $backgroundDimensions[1], $backgroundDimensions[0], $backgroundDimensions[1]);
            imagecopyresampled($previewImage, $image, $backgroundDimensions[0]/2 - $assumedArtworkDimensions[0]/2, $backgroundDimensions[1]/2 - $assumedArtworkDimensions[1]/2 - $backgroundDimensions[1]/6, 0, 0, $assumedArtworkDimensions[0], $assumedArtworkDimensions[1], $artworkDimensions[0], $artworkDimensions[1]);

            if(strcasecmp($extension, "png") == 0)
            {
                imagepng($previewImage, $artworksPath . $previewNameToStore);
            }
            else if(strcasecmp($extension, "jpg") == 0 || strcasecmp($extension, "jpeg"))
            {
                imagejpeg($previewImage, $artworksPath . $previewNameToStore);
            }
        }
        else
        {
            Storage::delete("public/artworks/" . $artwork->preview_name);
            $artwork->background_id = null;
            $artwork->preview_name = "";
        }
        ///////////////////////////////////////////////////////////////////////////////////////////////

        $artwork->title = $request->input("title");
        $artwork->category = $request->input("category");
        $artwork->author_id = $request->input("author");
        $artwork->description = $request->input("description");
        $artwork->width = $request->input("width");
        $artwork->height = $request->input("height");
        $artwork->year = $request->input("year");
        $artwork->smallprice = $request->input("smallprice");
        $artwork->mediumprice = $request->input("mediumprice");
        $artwork->bigprice = $request->input("bigprice");
        if($request->input("background") != null)
        {
            $artwork->background_id = $request->input("background");
        }
        if($thumbnailNameToStore != "")
        {
            $artwork->thumbnail_name = $thumbnailNameToStore;
        }
        if($pictureNameToStore != "")
        {
            $artwork->picture_name = $pictureNameToStore;
        }
        if($request->input("background") != null && $previewNameToStore != "")
        {
            $artwork->preview_name = $previewNameToStore;
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

        if(auth()->user()->role != "admin")
        {
            return redirect("/")->with("error", "Unauthorized");
        }

        $artwork = Artwork::find($id);
        Storage::delete("public/artworks/" . $artwork->thumbnail_name);
        Storage::delete("public/artworks/" . $artwork->picture_name);
        Storage::delete("public/artworks/" . $artwork->preview_name);
        $artwork->delete();
        return redirect("/")->with("success", "Artwork removed!");
    }

    public function manage()
    {

        if(auth()->user()->role != "admin")
        {
            return redirect("/")->with("error", "Unauthorized");
        }

        $artworks = Artwork::with(["getAuthor" => function($query)
        {
            $query->select('id', 'name', 'surname');
        }])->orderBy("created_at", "asc")->get();
        return view("gallery/manage")->with("artworks", $artworks);
    }

}
