<?php

namespace App\Data;

use Spatie\LaravelData\Data;

class ProjectData
{
    public function __construct(
        public string  $title,
        public string  $description,
        public string  $url,
        public ?string $logo = null,
    )
    {
    }
}
