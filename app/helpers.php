<?php

if (!function_exists('markdown')) {
    function markdown(string $text): string
    {
        return app(\App\Service\MarkdownRenderer::class)->renderSimple($text);
    }
}
