<?php

use App\Http\Controllers\BlogPostController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ManageController;
use App\Http\Controllers\MiniPaintingController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\TopicController;
use App\Http\Middleware\RequireManagePassword;
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

// Old addresses for the management pages, kept for bookmarks.
Route::redirect("minipainting/login", "/manage/login");
Route::redirect("minipainting/manage", "/manage/minipainting");

Route::prefix("manage")->group(function () {
    Route::get("login", [ManageController::class, "login"])->name("manage.login");
    Route::post("login", [ManageController::class, "unlock"])->middleware("throttle:5,1");
    Route::post("logout", [ManageController::class, "lock"])->name("manage.logout");

    Route::middleware(RequireManagePassword::class)->group(function () {
        Route::get("/", [ManageController::class, "index"])->name("manage");

        Route::prefix("minipainting")->controller(MiniPaintingController::class)->group(function () {
            Route::get("/", "manage")->name("manage.minipainting");
            Route::post("photos", "store");
            Route::put("photos/order", "reorder");
            Route::patch("photos/{id}", "update");
            Route::delete("photos/{id}", "destroy");
        });
    });
});
