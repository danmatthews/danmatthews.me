<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Sushi\Sushi;
use Symfony\Component\Finder\SplFileInfo;

class HomeImage extends Model
{
    use HasFactory, Sushi;

    public function getRows(): array
    {
        return collect(File::allFiles(public_path('images/home_images')))
            ->filter(fn(SplFileInfo $c) => $c->getExtension() === 'jpg')
            ->map(fn(SplFileInfo $c) => [
                'id' => $c->getFilename(),
                'url' => asset(Str::of($c->getRealPath())->replace(public_path(), '')->value)
            ])
            ->toArray();
    }
}
