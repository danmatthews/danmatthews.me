<?php

use Illuminate\Support\Facades\Schedule;
use App\Actions\BuildContent;

Schedule::call(function () {
    (new BuildContent)->handle();
})->daily();
