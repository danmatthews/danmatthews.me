<?php

namespace Intrfce\Graphein\Contracts;

use Intrfce\Graphein\Data\GrapheinPost;

interface PostProcessor
{
    public function __invoke(GrapheinPost $post, string $content): void;
}
