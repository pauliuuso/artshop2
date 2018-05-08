<?php

namespace App\Http\Controllers;

use App\Artwork;
use App\Cart;
use App\Size;
use Session;
use Illuminate\Http\Request;

class CartController extends Controller
{

    public function __construct()
    {
        $this->middleware("auth", ["except" => ["addtocart", "getcart", "removefromcart", "removeallfromcart", "thankyou"]]);
    }

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

        return view("cart/index")->with(["artworks" => $cart->artworks, "totalPrice" => $cart->getFullPrice(), "totalCount" => $cart->getTotalCount()]);
    }
}
