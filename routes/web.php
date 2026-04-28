<?php

use App\Http\Controllers\BlogPostController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\TopicController;
use Illuminate\Support\Facades\Route;

Route::feeds();

Route::get("/", HomeController::class)->name("home");

Route::get("posts", [BlogPostController::class, "index"])->name("posts.index");
Route::get("posts/{blog_post}", [BlogPostController::class, "show"])->name("posts.show");
Route::get("posts/{blog_post}/og", [BlogPostController::class, "ogImage"]);

Route::get("topics/{topic}", [TopicController::class, "show"])->name("topics.show");

Route::get("/about", [PageController::class, "about"])->name("about");
Route::get("/work", [PageController::class, "work"])->name("work");
Route::get("/uses", [PageController::class, "uses"])->name("uses");
