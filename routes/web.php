<?php

use App\Http\Controllers\BlogPostController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MiniPaintingController;
use App\Http\Middleware\RequireMiniPaintingPassword;
use App\Http\Controllers\PageController;
use App\Http\Controllers\TopicController;
use Illuminate\Support\Facades\Route;

Route::feeds();

Route::get("/", HomeController::class)->name("home");

Route::get("posts/{blog_post}", [BlogPostController::class, "show"])->name("posts.show");
Route::get("posts/{blog_post}/og", [BlogPostController::class, "ogImage"]);

Route::get("topics/{topic}", [TopicController::class, "show"])->name("topics.show");

Route::get("/about", [PageController::class, "about"])->name("about");
Route::get("/work", [PageController::class, "work"])->name("work");
Route::get("/uses", [PageController::class, "uses"])->name("uses");
Route::get("/minipainting", [PageController::class, "gallery"])->name("gallery");
Route::get("/minipainting.json", [MiniPaintingController::class, "json"])->name("minipainting.json");

Route::prefix("minipainting")->name("minipainting.")->controller(MiniPaintingController::class)->group(function () {
    Route::get("login", "login")->name("login");
    Route::post("login", "unlock")->middleware("throttle:5,1");
    Route::post("logout", "lock")->name("logout");

    Route::middleware(RequireMiniPaintingPassword::class)->group(function () {
        Route::get("manage", "manage")->name("manage");
        Route::post("photos", "store")->name("photos.store");
        Route::put("photos/order", "reorder")->name("photos.reorder");
        Route::patch("photos/{id}", "update")->name("photos.update");
        Route::delete("photos/{id}", "destroy")->name("photos.destroy");
    });
});
