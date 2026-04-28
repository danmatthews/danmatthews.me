<?php

namespace Intrfce\Graphein\Data;

use Carbon\CarbonImmutable;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\DateTimeInterfaceCast;
use Spatie\LaravelData\Data;

class GrapheinPost extends Data
{
    public function __construct(
        public string $id,
        public string $title,
        public string $slug,
        #[WithCast(DateTimeInterfaceCast::class, format: 'Y-m-d H:i:s')]
        public CarbonImmutable $date,
        public ?string $excerpt,
        #[MapInputName('content_url')]
        public string $contentUrl,
        public bool $published = true,
        public bool $updated = false,
        public array $topics = [],
    ) {}
}
