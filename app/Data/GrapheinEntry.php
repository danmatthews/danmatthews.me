<?php

namespace App\Data;

use App\Enums\ContentType;
use Spatie\LaravelData\Data;

class GrapheinEntry extends Data
{
    public function __construct(
        public ContentType $type,
        public GrapheinPost|GrapheinLink $data,
    ) {}

    public static function fromPageEntry(array $raw): self
    {
        $type = ContentType::from($raw['type']);

        return new self(
            type: $type,
            data: match ($type) {
                ContentType::POST => GrapheinPost::from($raw['data']),
                ContentType::LINK => GrapheinLink::from($raw['data']),
            },
        );
    }
}
