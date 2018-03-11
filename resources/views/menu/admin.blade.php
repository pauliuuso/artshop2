@extends("layouts/app")

@section("content")

<div class="row mt-5">

    <div class="col-12">
        <h2 class="mb-5">Welcome to Art Shop administration dashboard!</h2>
    </div>
    
    <a href="/artwork/add" class="col-12 col-sm-6 col-md-4 col-xl-3 mb-4">
        <div class="admin-option bg-success text-center">
            <p class="pt-5">ADD NEW ART</p>
            <i class="fab fa-deviantart"></i>      
        </div>
    </a>

    <a href="/categories" class="col-12 col-sm-6 col-md-4 col-xl-3 mb-4">
        <div class="admin-option bg-success text-center">
            <p class="pt-5">MANAGE CATEGORIES</p>
            <i class="fas fa-list-ul"></i> 
        </div>
    </a>

    <a href="/artworks/manage" class="col-12 col-sm-6 col-md-4 col-xl-3 mb-4">
        <div class="admin-option bg-success text-center">
            <p class="pt-5">MANAGE ARTWORKS</p>
            <i class="fas fa-paint-brush"></i>  
        </div>
    </a>

    <a href="/users/manage" class="col-12 col-sm-6 col-md-4 col-xl-3 mb-4">
        <div class="admin-option bg-success text-center">
            <p class="pt-5">MANAGE USERS</p>
            <i class="fas fa-user"></i>   
        </div>
    </a>

    <a href="/backgrounds/manage" class="col-12 col-sm-6 col-md-4 col-xl-3 mb-4">
        <div class="admin-option bg-success text-center">
            <p class="pt-5">MANAGE BACKGROUNDS</p>
            <i class="fab fa-firstdraft"></i>   
        </div>
    </a>

</div>

@endsection