<?php

namespace App\Facades;

use App\Data\GrapheinPostWithContent;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Facade;

/**
 * @method static LengthAwarePaginator getPaginatedPosts()
 * @method static GrapheinPostWithContent loadPostById(string $id)
 *
 * @see \App\Graphein\Graphein
 */
class Graphein extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \App\Graphein\Graphein::class;
    }
}
