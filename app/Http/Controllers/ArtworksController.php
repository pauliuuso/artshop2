<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Auth;
use App\Artwork;
use App\Category;
use App\Background;
use App\User;
use App\Cart;
use App\Order;
use App\Size;
use Stripe\Stripe;
use Stripe\Charge;
use Session;

class ArtworksController extends Controller
{

    public function __construct()
    {
        $this->middleware("auth", ["except" => ["index", "show", "getprice", "getart", "addtocart", "getcart", "checkout", "postcheckout", "removefromcart", "removeallfromcart"]]);
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
        $categories = Category::orderBy("name")->get();
        $authors = $authors = User::orderBy("name")->select("id", "name", "surname")->where("role", "author")->get();
        $artworks = "";

        if($filter == "")
        {
            $artworks = Artwork::with(["getAuthor" => function($query)
            {
                $query->select('id', 'name', 'surname');
            }])->orderBy("created_at", "desc")->paginate($artworksPerPage);
        }
        else if($filter == "kind")
        {
            $categories = Category::orderBy("name")->get();
            $artworks = Artwork::with(["getAuthor" => function($query)
            {
                $query->select('id', 'name', 'surname');
            }])->where("category", $id)->orderBy("created_at", "desc")->paginate($artworksPerPage);
        }
        else if($filter == "artist")
        {
            $authors = User::orderBy("name")->select("id", "name", "surname")->where("role", "author")->get();
            $artworks = Artwork::with(["getAuthor" => function($query)
            {
                $query->select('id', 'name', 'surname');
            }])->where("author_id", $id)->orderBy("created_at", "desc")->paginate($artworksPerPage);
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
            "price" => "required",
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
                $previewNameToStore = "preview-" . $pictureNameToStore;
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
        $artwork->year = $request->input("year");

        if($thumbnailNameToStore != "")
        {
            $artwork->thumbnail_name = $thumbnailNameToStore;
        }
        if($pictureNameToStore != "")
        {
            $artwork->picture_name = $pictureNameToStore;
        }
        $artwork->save();

        $size = new Size();
        $size->width = $request->input("width");
        $size->height = $request->input("height");
        $size->price = $request->input("price");
        $size->artwork_id = $artwork->id;

        if($request->input("background") != null && $previewNameToStore != "")
        {
            $size->preview_name = $previewNameToStore;
        }
        $size->background_id = $request->input("background");

        $size->save();

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
        }])->with("getSizes")->find($id);

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
        $artwork = Artwork::with("getSizes")->find($id);

        if(auth()->user()->role != "admin")
        {
            return redirect("/")->with("error", "Unauthorized");
        }

        $category = $artwork->getOneCategory->id;
        $author = $artwork->getAuthor->id;
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
            "year" => "required",
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

        $artwork->title = $request->input("title");
        $artwork->category = $request->input("category");
        $artwork->author_id = $request->input("author");
        $artwork->description = $request->input("description");
        $artwork->year = $request->input("year");

        // if($request->input("background") != null)
        // {
        //     $artwork->background_id = $request->input("background");
        // }
        if($thumbnailNameToStore != "")
        {
            $artwork->thumbnail_name = $thumbnailNameToStore;
        }
        if($pictureNameToStore != "")
        {
            $artwork->picture_name = $pictureNameToStore;
        }
        // if($request->input("background") != null && $previewNameToStore != "")
        // {
        //     $artwork->preview_name = $previewNameToStore;
        // }
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

        $artwork = Artwork::with("getSizes")->find($id);
        $sizes = $artwork->getSizes;

        foreach($sizes as $size)
        {
            $this->deletesize($size->id);
        }

        Storage::delete("public/artworks/" . $artwork->thumbnail_name);
        Storage::delete("public/artworks/" . $artwork->picture_name);
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

    public function getprice()
    {
        $sizeId = request("sizeId");
        $price = Size::select("price")->find($sizeId)["price"];
        $price *= request("quantity");
        return $price;
    }

    // public function getart(Request $request)
    // {
    //     $id = $request->input("artwork_id");
    //     $count = $request->input("count");
    //     $artworks = Artwork::select("thumbnail_name", "id")->where("author_id", 4)->orderBy("created_at", "desc")->toArray();
    //     return response()->json($artworks);
    // }

    public function addtocart(Request $request)
    {
        $id = $request->input("artwork-id");
        $size = Size::find($request->input("artwork-size"));
        $count = $request->input("count");

        $artwork = Artwork::with(["getAuthor" => function($query)
        {
            $query->select('id', 'name', 'surname');
        }])->find($id);
        
        $oldCart = Session::has("cart") ? Session::get("cart") : null;
        $cart = new Cart($oldCart);
        $cart->add($artwork, $id, $size, $count);

        $request->session()->put("cart", $cart);
        // dd($request->session()->get("cart"));
        return redirect("/artwork/show/" . $id)->with("success", "Artwork added to cart!");
    }

    public function removefromcart($index)
    {
        if(!Session::has("cart"))
        {
            return view("cart/index");
        }

        $oldCart = Session::get("cart");
        $cart = new Cart($oldCart);
        $cart->remove($index);
        if(count($cart->artworks) > 0)
        {
            Session::put("cart", $cart);
        }
        else
        {
            Session::forget("cart");
        }

        return redirect("/get-cart")->with(["success" => "Removed from your basket!"]);
    }

    public function removeallfromcart()
    {
        if(!Session::has("cart"))
        {
            return view("cart/index");
        }

        $oldCart = Session::get("cart");
        $cart = new Cart($oldCart);
        $cart->removeall();
        Session::forget("cart");
        return redirect("/get-cart")->with(["success" => "Removed from your basket!"]);
    }

    public function getcart()
    {
        if(!Session::has("cart"))
        {
            return view("cart/index");
        }
        $oldCart = Session::get("cart");
        $cart = new Cart($oldCart);
        return view("cart/index")->with(["artworks" => $cart->artworks, "totalPrice" => $cart->totalPrice, "totalCount" => $cart->totalCount]);
    }

    public function checkout()
    {
        if(!Session::has("cart"))
        {
            return view("cart/index");
        }

        $oldCart = Session::get("cart");
        $cart = new Cart($oldCart);
        $totalPrice = $cart->totalPrice;

        return view("cart/checkout")->with(["totalPrice" => $totalPrice]);

    }

    public function postcheckout(Request $request)
    {
        if(!Session::has("cart"))
        {
            return view("cart/index");
        }

        $oldCart = Session::get("cart");
        $cart = new Cart($oldCart);

        Stripe::setApiKey("sk_test_JJ7cu8Hrdi0wda1MHgvBSi3i");

        try
        {

            $charge = Charge::create(
            array(
                "amount" => $cart->totalPrice * 100,
                "currency" => "eur",
                "source" => $request->input("stripeToken"),
                "description" => "Payment"
            ));

            $order = new Order();
            $order->cart = serialize($cart);
            $order->address = $request->input("address");
            $order->name = $request->input("name");
            $order->surname = $request->input("surname");
            $order->payment_id = $charge->id;

            if(Auth::user())
            {
                Auth::user()->orders()->save($order);
            }
            else
            {
                $order->save();
            }

        }
        catch(\Exception $exception)
        {
            return redirect("/checkout")->with(["error" => $exception->getMessage()]);
        }

        Session::forget("cart");
        return redirect("/get-cart")->with(["success" => "Purchace succesfull!"]);

    }

    public function orders()
    {
        $orders = Order::orderBy("created_at", "desc")->paginate(24);
        $orders->transform(function($order, $key)
        {
            $order->cart = unserialize($order->cart);
            return $order;
        });

        return view("cart/orders")->with(["orders" => $orders]);
    }

    public function editsize($id)
    {

        if(auth()->user()->role != "admin")
        {
            return redirect("/")->with("error", "Unauthorized");
        }


        $size = Size::find($id);
        $backgrounds = Background::select("title", "id", "background_name")->get();
        $backgroundIdsAndTitles = $backgrounds->pluck("title", "id");
        $selectedBackground = 0;
        if($size->background_id != null)
        {
            $selectedBackground = $size->background_id;
        }
        return view("sizes/edit")->with(["backgrounds" => $backgrounds, "backgroundIdsAndTitles" => $backgroundIdsAndTitles, "selectedBackground" => $selectedBackground, "size" => $size]);
    }

    public function newsize($id)
    {

        if(auth()->user()->role != "admin")
        {
            return redirect("/")->with("error", "Unauthorized");
        }

        $backgrounds = Background::select("title", "id", "background_name")->get();
        $backgroundIdsAndTitles = $backgrounds->pluck("title", "id");
        return view("sizes/index")->with(["backgrounds" => $backgrounds, "backgroundIdsAndTitles" => $backgroundIdsAndTitles, "artworkId" => $id]);
    }

    public function createsize(Request $request, $id)
    {

        if(auth()->user()->role != "admin")
        {
            return redirect("/")->with("error", "Unauthorized");
        }

        $this->validate($request,
        [
            "width" => "required",
            "height" => "required",
            "price" => "required"
        ]);

        $size = new Size();
        $artwork = Artwork::find($id);

        //////////////// Generate preview with interior background //////////////////
        if($request->input("background"))
        {
            $background = Background::find($request->input("background"));
            if(is_null($request->input("background")))
            {
                $background = Background::find($size->background_id);
            }
            $artworksPath = Storage::disk('local')->getDriver()->getAdapter()->getPathPrefix() . "/public/artworks/";
            $backgroundsPath = Storage::disk('local')->getDriver()->getAdapter()->getPathPrefix() . "/public/backgrounds/";
            $uploadedImage = $artworksPath . $artwork->picture_name;
            $extension = pathinfo($artwork->picture_name, PATHINFO_EXTENSION);
            $pictureName = $artwork->picture_name;
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
            $previewNameToStore = "preview-" . substr(md5(openssl_random_pseudo_bytes(5)),-5) . $pictureName;
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

            $size->preview_name = $previewNameToStore;
        }
        ///////////////////////////////////////////////////////////////////////////////////////////////

        $size->width = $request->input("width");
        $size->height = $request->input("height");
        $size->price = $request->input("price");
        $size->artwork_id = $id;
        $size->background_id = $request->input("background");
        $size->save();
        return redirect("/artwork/edit/" . $artwork->id)->with(["success" => "Updated successfully!"]);
    }

    public function updatesize(Request $request, $id)
    {
        if(auth()->user()->role != "admin")
        {
            return redirect("/")->with("error", "Unauthorized");
        }

        $this->validate($request,
        [
            "width" => "required",
            "height" => "required",
            "price" => "required"
        ]);

        $size = Size::find($id);
        $artwork = Artwork::find($size->artwork_id);

        //////////////// Generate preview with interior background //////////////////
        if($request->input("background"))
        {
            $background = Background::find($request->input("background"));
            if(is_null($request->input("background")))
            {
                $background = Background::find($size->background_id);
            }
            $artworksPath = Storage::disk('local')->getDriver()->getAdapter()->getPathPrefix() . "/public/artworks/";
            $backgroundsPath = Storage::disk('local')->getDriver()->getAdapter()->getPathPrefix() . "/public/backgrounds/";
            $uploadedImage = $artworksPath . $artwork->picture_name;
            $extension = pathinfo($artwork->picture_name, PATHINFO_EXTENSION);
            $pictureName = $artwork->picture_name;
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
            $previewNameToStore = "preview-" . substr(md5(openssl_random_pseudo_bytes(5)),-5) . $pictureName;
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

            $size->preview_name = $previewNameToStore;
            $size->background_id = $request->input("background");
        }
        else
        {
            Storage::delete("public/artworks/" . $size->preview_name);
            $size->background_id = null;
            $size->preview_name = "";
        }
        ///////////////////////////////////////////////////////////////////////////////////////////////


        $size->width = $request->input("width");
        $size->height = $request->input("height");
        $size->price = $request->input("price");
        $size->save();
        return redirect("/artwork/edit/" . $artwork->id)->with(["success" => "Updated successfully!"]);
    }

    public function deletesize($id)
    {
        $size = Size::find($id);
        $artwork = Artwork::select("id")->find($size->artwork_id);
        Storage::delete("public/artworks/" . $size->preview_name);
        $size->delete();
        return redirect("/artwork/edit/" . $artwork->id)->with(["success" => "Size deleted!"]);
    }

}
