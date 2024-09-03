<?php

if (!function_exists('markdown')) {
    function markdown(string $text): string
    {
        return app(\App\Service\MarkdownRenderer::class)->renderSimple($text);
    }
}

function pixels()
{
    $width = 16;
    $height = 240;
    $size = 2;
    $array = [];
    foreach (range(0, ($height/$size)) as $row) {



        $row = [];

        if (rand(0,1) == 1) {

            $num = $width / $size;
            foreach (range(0, $num) as $dot) {
                $bg = rand(0,2) == 2 ? '#FFA132' : 'transparent';
                $row[] = '<div style="width:'.$size.'px; height:'.$size.'px; background: '.$bg.';"></div>';

            }

            $array[] = implode('',$row);
        } else {
            $array[] = '<div style="width:'.$width.'px;height:'.$size.'px;"></div>';
        }
    }

    return implode('', $array);
}
