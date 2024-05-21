<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Sushi\Sushi;

class ResumeEntry extends Model
{
    use HasFactory;
    use Sushi;

    public function getRows(): array
    {
        return collect(config('site.job_history'))
            ->map(fn($s) => $s->toArray())
            ->map(fn($s) => [...$s, 'iconPath' => asset($s['iconPath'])])
            ->toArray();
    }
}
