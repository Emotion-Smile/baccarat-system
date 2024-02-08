<?php

namespace App\Kravanh\Domain\Match\Supports\Enums;

use BenSampo\Enum\Enum;

class BetStatus extends Enum
{
    const ACCEPTED = 1;
    const PENDING = 2;
    const CANCELLED = 3; //cancelled
}
