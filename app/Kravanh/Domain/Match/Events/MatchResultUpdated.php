<?php

namespace App\Kravanh\Domain\Match\Events;

use Illuminate\Foundation\Events\Dispatchable;

class MatchResultUpdated
{
    use Dispatchable;

    public function __construct(public array $match)
    {
    }
}
