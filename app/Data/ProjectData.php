<?php

namespace App\Data;

use Spatie\LaravelData\Data;

class ProjectData extends Data
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
