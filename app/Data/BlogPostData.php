<?php

namespace App\Data;

use Carbon\CarbonInterface;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Data;

class BlogPostData extends Data
{
    public function __construct(
        #[Required]
        public string $id,
        #[Required]
        public string $title,
        #[Required]
        public CarbonInterface $date,
        #[Required]
        public string $slug,
        #[Required]
        public string $content,
        #[Required]
        public null|string $excerpt,
    ) {}
}
