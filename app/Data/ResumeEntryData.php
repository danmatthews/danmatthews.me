<?php

namespace App\Data;

use Spatie\LaravelData\Data;

class ResumeEntryData
{
    public function __construct(
        public string $iconPath,
        public string $companyName,
        public string $jobTitle,
        public string $start,
        public string $end,
    )
    {
    }
}
