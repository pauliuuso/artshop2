<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Auth;
use App\User;

class UsersController extends Controller
{

    public function __construct()
    {
        $this->middleware("auth");
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(auth()->user()->role != "admin")
        {
            return redirect("/")->with("error", "Unauthorized");
        }

        $users = User::all()->whereIn("role", ["author", "user"]);
        return view("users/index")->with("users", $users);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if(auth()->user()->role != "admin")
        {
            return redirect("/")->with("error", "Unauthorized");
        }

        $user = User::find($id);
        return view("users/edit")->with("user", $user);
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
            'name' => 'required|string|max:50',
            'surname' => 'required|string|max:50',
            "description" => "max:1000",
            "active" => "required",
            "role" => "required"
        ]);

        $user = User::find($id);
        $pictureNameToStore = "";

        if($request->hasFile("picture"))
        {
            $pictureNameWithExt = $request->file("picture")->getClientOriginalName();
            $pictureName = pathinfo($pictureNameWithExt, PATHINFO_FILENAME);
            $extension = $request->file("picture")->getClientOriginalExtension();
            $pictureNameToStore = $pictureName . "-" . substr(md5(openssl_random_pseudo_bytes(20)),-20) . "." . $extension;
            $path = $request->file("picture")->storeAs("public/users/", $pictureNameToStore);
            if($user->picture_name != null && $user->picture_name != "")
            {
                Storage::delete("public/users/" . $user->picture_name);
            }
        }

        $user->name = $request->input("name");
        $user->surname = $request->input("surname");
        $user->description = $request->input("description");
        $user->role = $request->input("role");
        $user->active = $request->input("active");
        $user->picture_name = $pictureNameToStore;
        $user->save();
        return redirect("/admin")->with("success", "User updated!");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
