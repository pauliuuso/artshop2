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
Route::get("/gallery/{filter}/{id}", "ArtworksController@index");
Route::get("/artwork/show/{id}", "ArtworksController@show");
Route::get("/artwork/add", "ArtworksController@create");
Route::post("/artwork/store", "ArtworksController@store");
Route::get("/artwork/edit/{id}", "ArtworksController@edit");
Route::put("/artwork/update/{id}", "ArtworksController@update");
Route::delete("/artwork/delete/{id}", "ArtworksController@destroy");
Route::get("/artworks/manage", "ArtworksController@manage");
Route::post("/artwork/getprice", "ArtworksController@getprice");
Route::get("/add-to-cart", "ArtworksController@addtocart");
Route::get("/remove-from-cart/{index}", "ArtworksController@removefromcart");
Route::get("/remove-all-from-cart", "ArtworksController@removeallfromcart");
Route::get("/get-cart", "ArtworksController@getcart");
Route::get("/checkout", "ArtworksController@checkout");
Route::post("/checkout", "ArtworksController@postcheckout");
Route::get("/orders", "ArtworksController@orders");
Route::get("/edit-size/{id}", "ArtworksController@editsize");
Route::put("/update-size/{id}", "ArtworksController@updatesize");
Route::get("/new-size/{id}", "ArtworksController@newsize");
Route::post("/create-size/{id}", "ArtworksController@createsize");
Route::delete("/delete-size/{id}", "ArtworksController@deletesize");

Route::get("/backgrounds/manage", "BackgroundsController@index");
Route::post("/backgrounds/store", "BackgroundsController@store");
Route::get("/backgrounds/edit/{id}", "BackgroundsController@edit");
Route::put("/backgrounds/update/{id}", "BackgroundsController@update");
Route::delete("/backgrounds/delete/{id}", "BackgroundsController@destroy");

Route::get("/categories", "CategoriesController@index");
Route::post("/categories/store", "CategoriesController@store");
Route::get("/category/edit/{id}", "CategoriesController@edit");
Route::put("/category/update/{id}", "CategoriesController@update");
Route::delete("/category/delete/{id}", "CategoriesController@destroy");

Route::get("/users/manage", "UsersController@index");
Route::get("/users/edit/{id}", "UsersController@edit");
Route::post("/users/update/{id}", "UsersController@update");
Route::get("/artists", "UsersController@showlist");

Route::get("/contacts", "PagesController@contacts");
Route::get("/about", "PagesController@about");

Route::get("/admin", "PagesController@admin");


Route::get("/login", "Auth\LoginController@showLoginForm");
Route::post("/login", "Auth\LoginController@login");
Route::post("/logout", "Auth\LoginController@logout");
Route::get("/register", "Auth\RegisterController@showRegistrationForm");
Route::post("/register", "Auth\RegisterController@register");
Route::get("/password/reset", "Auth\ForgotPasswordController@showLinkRequestForm");
Route::post("/password/email", "Auth\ForgotPasswordController@sendResetLinkEmail");
Route::post("/password/reset", "Auth\ResetPasswordController@reset");
Route::get("/password/reset/{token}", "Auth\ResetPasswordController@showResetForm")->name("password.reset");

//Auth::routes();

// Route::get('/home', 'HomeController@index')->name('home');
