<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category;

class CategoriesController extends Controller
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

        $categories = Category::all();
        return view("categories/index")->with("categories", $categories);
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

        if(auth()->user()->role != "admin")
        {
            return redirect("/")->with("error", "Unauthorized");
        }

        $this->validate($request,
        [
            "name" => "required"
        ]);

        $category = new Category;
        $category->name = $request->input("name");
        $category->save();
        return redirect("/categories")->with("success", "Category added!");
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

        $category = Category::find($id);
        return view("categories/edit")->with("category", $category);
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
            "name" => "required"
        ]);

        $category = Category::find($id);
        $category->name = $request->input("name");
        $category->save();
        return redirect("/categories")->with("success", "Category updated!");
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

        $category = Category::find($id);
        $category->delete();
        return redirect("/categories")->with("success", "Category deleted!");
    }
}
