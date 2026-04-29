<?php

namespace Intrfce\Graphein\Facades;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Facade;
use Intrfce\Graphein\Data\GrapheinEntry;
use Intrfce\Graphein\Data\GrapheinPostWithContent;

/**
 * @method static LengthAwarePaginator<int, GrapheinEntry> getPaginatedPosts()
 * @method static LengthAwarePaginator<int, GrapheinEntry> getPaginatedPostsByTopic(string $slug)
 * @method static array|null getTopic(string $slug)
 * @method static array getAllTopics()
 * @method static GrapheinPostWithContent loadPostById(string $id)
 * @method static \Intrfce\Graphein\Graphein postProcessors(array $processors)
 * @method static array getPostProcessors()
 *
 * @see \Intrfce\Graphein\Graphein
 */
class Graphein extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Intrfce\Graphein\Graphein::class;
    }
}
