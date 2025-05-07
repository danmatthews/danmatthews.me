<?php

if (!function_exists('markdown')) {
    function markdown(string $text): string
    {
        return Str::markdown($text);
    }
}
