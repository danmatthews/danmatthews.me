<?php

namespace App\Data;

use Carbon\CarbonInterface;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Data;

class FrontMatter extends Data
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
        public null|string     $excerpt,
        public bool            $published = true,
        public ?array          $tags = [],
        public ?bool           $updated = false,
    )
    {
    }
}
