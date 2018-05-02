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
        $this->middleware("auth", ["except" => ["index", "show", "getprice", "getart", "addtocart", "getcart", "checkout", "postcheckoutaddress", "checkoutaddress", "checkoutpayment", "completecheckout", "removefromcart", "removeallfromcart"]]);
    }

    public function intro()
    {
        return view("intro/index");
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

    public function checkoutaddress()
    {
        if(!Session::has("cart"))
        {
            return view("cart/index");
        }

        $oldCart = Session::get("cart");
        $cart = new Cart($oldCart);
        $totalPrice = $cart->totalPrice;
        $countries = [
            "Select" => "Select Country",
            "AF" => "Afghanistan",
            "AX" => "Åland Islands",
            "AL" => "Albania",
            "DZ" => "Algeria",
            "AS" => "American Samoa",
            "AD" => "Andorra",
            "AO" => "Angola",
            "AI" => "Anguilla",
            "AQ" => "Antarctica",
            "AG" => "Antigua and Barbuda",
            "AR" => "Argentina",
            "AM" => "Armenia",
            "AW" => "Aruba",
            "AU" => "Australia",
            "AT" => "Austria",
            "AZ" => "Azerbaijan",
            "BS" => "Bahamas",
            "BH" => "Bahrain",
            "BD" => "Bangladesh",
            "BB" => "Barbados",
            "BY" => "Belarus",
            "BE" => "Belgium",
            "BZ" => "Belize",
            "BJ" => "Benin",
            "BM" => "Bermuda",
            "BT" => "Bhutan",
            "BO" => "Bolivia, Plurinational State of",
            "BQ" => "Bonaire, Sint Eustatius and Saba",
            "BA" => "Bosnia and Herzegovina",
            "BW" => "Botswana",
            "BV" => "Bouvet Island",
            "BR" => "Brazil",
            "IO" => "British Indian Ocean Territory",
            "BN" => "Brunei Darussalam",
            "BG" => "Bulgaria",
            "BF" => "Burkina Faso",
            "BI" => "Burundi",
            "KH" => "Cambodia",
            "CM" => "Cameroon",
            "CA" => "Canada",
            "CV" => "Cape Verde",
            "KY" => "Cayman Islands",
            "CF" => "Central African Republic",
            "TD" => "Chad",
            "CL" => "Chile",
            "CN" => "China",
            "CX" => "Christmas Island",
            "CC" => "Cocos (Keeling) Islands",
            "CO" => "Colombia",
            "KM" => "Comoros",
            "CG" => "Congo",
            "CD" => "Congo, the Democratic Republic of the",
            "CK" => "Cook Islands",
            "CR" => "Costa Rica",
            "CI" => "Côte d'Ivoire",
            "HR" => "Croatia",
            "CU" => "Cuba",
            "CW" => "Curaçao",
            "CY" => "Cyprus",
            "CZ" => "Czech Republic",
            "DK" => "Denmark",
            "DJ" => "Djibouti",
            "DM" => "Dominica",
            "DO" => "Dominican Republic",
            "EC" => "Ecuador",
            "EG" => "Egypt",
            "SV" => "El Salvador",
            "GQ" => "Equatorial Guinea",
            "ER" => "Eritrea",
            "EE" => "Estonia",
            "ET" => "Ethiopia",
            "FK" => "Falkland Islands (Malvinas)",
            "FO" => "Faroe Islands",
            "FJ" => "Fiji",
            "FI" => "Finland",
            "FR" => "France",
            "GF" => "French Guiana",
            "PF" => "French Polynesia",
            "TF" => "French Southern Territories",
            "GA" => "Gabon",
            "GM" => "Gambia",
            "GE" => "Georgia",
            "DE" => "Germany",
            "GH" => "Ghana",
            "GI" => "Gibraltar",
            "GR" => "Greece",
            "GL" => "Greenland",
            "GD" => "Grenada",
            "GP" => "Guadeloupe",
            "GU" => "Guam",
            "GT" => "Guatemala",
            "GG" => "Guernsey",
            "GN" => "Guinea",
            "GW" => "Guinea-Bissau",
            "GY" => "Guyana",
            "HT" => "Haiti",
            "HM" => "Heard Island and McDonald Islands",
            "VA" => "Holy See (Vatican City State)",
            "HN" => "Honduras",
            "HK" => "Hong Kong",
            "HU" => "Hungary",
            "IS" => "Iceland",
            "IN" => "India",
            "ID" => "Indonesia",
            "IR" => "Iran, Islamic Republic of",
            "IQ" => "Iraq",
            "IE" => "Ireland",
            "IM" => "Isle of Man",
            "IL" => "Israel",
            "IT" => "Italy",
            "JM" => "Jamaica",
            "JP" => "Japan",
            "JE" => "Jersey",
            "JO" => "Jordan",
            "KZ" => "Kazakhstan",
            "KE" => "Kenya",
            "KI" => "Kiribati",
            "KP" => "Korea, Democratic People's Republic of",
            "KR" => "Korea, Republic of",
            "KW" => "Kuwait",
            "KG" => "Kyrgyzstan",
            "LA" => "Lao People's Democratic Republic",
            "LV" => "Latvia",
            "LB" => "Lebanon",
            "LS" => "Lesotho",
            "LR" => "Liberia",
            "LY" => "Libya",
            "LI" => "Liechtenstein",
            "LT" => "Lithuania",
            "LU" => "Luxembourg",
            "MO" => "Macao",
            "MK" => "Macedonia, the former Yugoslav Republic of",
            "MG" => "Madagascar",
            "MW" => "Malawi",
            "MY" => "Malaysia",
            "MV" => "Maldives",
            "ML" => "Mali",
            "MT" => "Malta",
            "MH" => "Marshall Islands",
            "MQ" => "Martinique",
            "MR" => "Mauritania",
            "MU" => "Mauritius",
            "YT" => "Mayotte",
            "MX" => "Mexico",
            "FM" => "Micronesia, Federated States of",
            "MD" => "Moldova, Republic of",
            "MC" => "Monaco",
            "MN" => "Mongolia",
            "ME" => "Montenegro",
            "MS" => "Montserrat",
            "MA" => "Morocco",
            "MZ" => "Mozambique",
            "MM" => "Myanmar",
            "NA" => "Namibia",
            "NR" => "Nauru",
            "NP" => "Nepal",
            "NL" => "Netherlands",
            "NC" => "New Caledonia",
            "NZ" => "New Zealand",
            "NI" => "Nicaragua",
            "NE" => "Niger",
            "NG" => "Nigeria",
            "NU" => "Niue",
            "NF" => "Norfolk Island",
            "MP" => "Northern Mariana Islands",
            "NO" => "Norway",
            "OM" => "Oman",
            "PK" => "Pakistan",
            "PW" => "Palau",
            "PS" => "Palestinian Territory, Occupied",
            "PA" => "Panama",
            "PG" => "Papua New Guinea",
            "PY" => "Paraguay",
            "PE" => "Peru",
            "PH" => "Philippines",
            "PN" => "Pitcairn",
            "PL" => "Poland",
            "PT" => "Portugal",
            "PR" => "Puerto Rico",
            "QA" => "Qatar",
            "RE" => "Réunion",
            "RO" => "Romania",
            "RU" => "Russian Federation",
            "RW" => "Rwanda",
            "BL" => "Saint Barthélemy",
            "SH" => "Saint Helena, Ascension and Tristan da Cunha",
            "KN" => "Saint Kitts and Nevis",
            "LC" => "Saint Lucia",
            "MF" => "Saint Martin (French part)",
            "PM" => "Saint Pierre and Miquelon",
            "VC" => "Saint Vincent and the Grenadines",
            "WS" => "Samoa",
            "SM" => "San Marino",
            "ST" => "Sao Tome and Principe",
            "SA" => "Saudi Arabia",
            "SN" => "Senegal",
            "RS" => "Serbia",
            "SC" => "Seychelles",
            "SL" => "Sierra Leone",
            "SG" => "Singapore",
            "SX" => "Sint Maarten (Dutch part)",
            "SK" => "Slovakia",
            "SI" => "Slovenia",
            "SB" => "Solomon Islands",
            "SO" => "Somalia",
            "ZA" => "South Africa",
            "GS" => "South Georgia and the South Sandwich Islands",
            "SS" => "South Sudan",
            "ES" => "Spain",
            "LK" => "Sri Lanka",
            "SD" => "Sudan",
            "SR" => "Suriname",
            "SJ" => "Svalbard and Jan Mayen",
            "SZ" => "Swaziland",
            "SE" => "Sweden",
            "CH" => "Switzerland",
            "SY" => "Syrian Arab Republic",
            "TW" => "Taiwan, Province of China",
            "TJ" => "Tajikistan",
            "TZ" => "Tanzania, United Republic of",
            "TH" => "Thailand",
            "TL" => "Timor-Leste",
            "TG" => "Togo",
            "TK" => "Tokelau",
            "TO" => "Tonga",
            "TT" => "Trinidad and Tobago",
            "TN" => "Tunisia",
            "TR" => "Turkey",
            "TM" => "Turkmenistan",
            "TC" => "Turks and Caicos Islands",
            "TV" => "Tuvalu",
            "UG" => "Uganda",
            "UA" => "Ukraine",
            "AE" => "United Arab Emirates",
            "GB" => "United Kingdom",
            "US" => "United States",
            "UM" => "United States Minor Outlying Islands",
            "UY" => "Uruguay",
            "UZ" => "Uzbekistan",
            "VU" => "Vanuatu",
            "VE" => "Venezuela, Bolivarian Republic of",
            "VN" => "Viet Nam",
            "VG" => "Virgin Islands, British",
            "VI" => "Virgin Islands, U.S.",
            "WF" => "Wallis and Futuna",
            "EH" => "Western Sahara",
            "YE" => "Yemen",
            "ZM" => "Zambia",
            "ZW" => "Zimbabwe"
        ];

        $order = new Order();

        if($cart->orderId != 0)
        {
            $order = $order->find($cart->orderId);
        }

        return view("cart/checkoutaddress")->with(["artworks" => $cart->artworks, "totalPrice" => $totalPrice, "countries" => $countries, "order" => $order]);
    }

    public function checkoutpayment($type)
    {
        if(!Session::has("cart"))
        {
            return view("cart/index");
        }

        $oldCart = Session::get("cart");
        $cart = new Cart($oldCart);
        $totalPrice = $cart->totalPrice;

        return view("cart/checkoutpayment")->with(["artworks" => $cart->artworks, "totalPrice" => $totalPrice, "type" => $type]);
    }

    public function postcheckoutaddress(Request $request)
    {
        if(!Session::has("cart"))
        {
            return view("cart/index");
        }

        $this->validate($request,
        [
            "name" => "required",
            "surname" => "required",
            "email" => "required",
            "name_shipping" => "required",
            "surname_shipping" => "required",
            "address" => "required",
            "apartment" => "required",
            "country" => "required",
            "postal_code" => "required",
            "phone" => "required"
        ]);

        $oldCart = Session::get("cart");
        $cart = new Cart($oldCart);

        if($cart->orderId == 0)
        {
            $order = new Order();
        }
        else
        {
            $order = Order::find($cart->orderId);
        }

        $order->cart = serialize($cart);
        $order->name = $request->input("name");
        $order->surname = $request->input("surname");
        $order->email = $request->input("email");
        $order->name_shipping = $request->input("name_shipping");
        $order->surname_shipping = $request->input("surname_shipping");
        $order->address = $request->input("address");
        $order->apartment = $request->input("apartment");
        $order->country = $request->input("country");
        $order->postal_code = $request->input("postal_code");
        $order->phone = $request->input("phone");

        if(Auth::user())
        {
            Auth::user()->orders()->save($order);
        }
        else
        {
            $order->save();
        }

        $cart->setOrderId($order->id);
        $request->session()->put("cart", $cart);

        return redirect("/checkoutpayment/credit");
    }

    public function completecheckout(Request $request)
    {

        $this->validate($request,
        [
            "card_type" => "required",
            "name_credit" => "required",
            "surname_credit" => "required",
            "card_number" => "required",
            "card_expirity_month" => "required",
            "card_expirity_year" => "required",
            "card_cvc" => "required"
        ]);

        Stripe::setApiKey("sk_test_JJ7cu8Hrdi0wda1MHgvBSi3i");
        $oldCart = Session::get("cart");
        $cart = new Cart($oldCart);

        try
        {

            $charge = Charge::create(
            array(
                "amount" => $cart->totalPrice * 100,
                "currency" => "eur",
                "source" => $request->input("stripeToken"),
                "description" => "Payment"
            ));

            $order = Order::find($cart->orderId);
            $order->cart = serialize($cart);
            $order->card_type = $request->input("card_type");
            $order->name_credit = $request->input("name_credit");
            $order->surname_credit = $request->input("surname_credit");
            $order->card_number = $request->input("card_number");
            $order->payment_type = $request->input("payment_type");
            $order->completed = true;
            $order->price = $cart->totalPrice;
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
            return redirect("/checkoutpayment/" . $request->input("payment_type"))->with(["error" => $exception->getMessage()]);
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
