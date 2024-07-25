<?php

namespace App\Data;

class NavigationItem
{
    public function __construct(
        public string $title,
        public string $url,
    )
    {
    }
}
