<?php

use Illuminate\Support\Facades\Schedule;
use Intrfce\Graphein\Actions\BuildAndCachePosts;

Schedule::call(function () {
    (new BuildAndCachePosts)->handle();
})->daily();
