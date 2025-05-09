<?php

namespace App\OG;

use SimonHamp\TheOg\Interfaces\Font;

class DMSans implements Font
{
    public function path(): string
    {
        return resource_path('fonts/dmsans.ttf');
    }
}