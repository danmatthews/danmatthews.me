<?php

namespace App\Data;

use Carbon\CarbonInterface;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Data;

class LinkFrontMatter extends Data
{
    public function __construct(
        #[Required]
        public string          $id,
        #[Required]
        public string          $title,
        #[Required]
        public CarbonInterface $date,
        #[Required]
        public string          $slug,
        #[Required]
        public string          $url,
        #[Required]
        public null|string     $description,
        public bool            $published = true,
        public ?array          $tags = []
    )
    {
    }
}
