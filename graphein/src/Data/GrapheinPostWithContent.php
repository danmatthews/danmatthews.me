<?php

namespace Intrfce\Graphein\Data;

use Spatie\LaravelData\Data;

class GrapheinPostWithContent extends Data
{
    public function __construct(
        public GrapheinPost $meta,
        public string $content,
    ) {}
}
