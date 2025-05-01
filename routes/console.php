<?php

use Illuminate\Support\Facades\Schedule;
use App\Actions\BuildAndCachePosts;

Schedule::call(function () {
    (new BuildAndCachePosts)->handle();
})->daily();
