<?php

namespace App\Data;

use Closure;

class NavigationItem
{
    public function __construct(
        public string   $title,
        public string   $url,
        public ?Closure $isActive = null,
        public bool     $external = false,
    )
    {
    }
}
