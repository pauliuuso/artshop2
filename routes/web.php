<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get("/", "ArtworksController@index");
Route::get("/artwork/show/{id}", "ArtworksController@show");
Route::get("/artwork/add", "ArtworksController@create");
Route::post("/artwork/store", "ArtworksController@store");
Route::get("/artwork/edit/{id}", "ArtworksController@edit");
Route::put("/artwork/update/{id}", "ArtworksController@update");
Route::delete("/artwork/delete/{id}", "ArtworksController@destroy");

Route::get("/categories", "CategoriesController@index");
Route::post("/categories/store", "CategoriesController@store");
Route::get("/category/edit/{id}", "CategoriesController@edit");
Route::put("/category/update/{id}", "CategoriesController@update");
Route::delete("/category/delete/{id}", "CategoriesController@destroy");

Route::get("/contacts", "PagesController@contacts");
Route::get("/about", "PagesController@about");
Route::get("/artists", "PagesController@artists");

Route::get("/artworks", "ArtworksController@index");
//Route::resource("artworks", "ArtworksController");
