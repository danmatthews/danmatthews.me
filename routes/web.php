<?php

use App\Http\Controllers\BlogPostController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\TagController;
use Illuminate\Support\Facades\Route;

Route::feeds();

Route::get("/", HomeController::class)->name("home");

Route::get("posts", [BlogPostController::class, "index"])->name("posts.index");
Route::get("posts/{blog_post}", [BlogPostController::class, "show"])->name("posts.show");
Route::get("posts/{blog_post}/og", [BlogPostController::class, "ogImage"]);

Route::get("/tags", [TagController::class, "index"])->name("tags.index");
Route::get("/tags/{id}", [TagController::class, "show"])->name("tags.show");

Route::get("/about", [PageController::class, "about"])->name("about");
Route::get("/work", [PageController::class, "work"])->name("work");
Route::get("/uses", [PageController::class, "uses"])->name("uses");
