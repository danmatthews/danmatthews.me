<?php

namespace App\Graphein\Contracts;

use App\Data\GrapheinPost;

interface PostProcessor
{
    public function __invoke(GrapheinPost $post, string $content): void;
}
