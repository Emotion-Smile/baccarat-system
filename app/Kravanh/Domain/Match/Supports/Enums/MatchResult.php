<?php

namespace App\Kravanh\Domain\Match\Supports\Enums;

use BenSampo\Enum\Enum;

class MatchResult extends Enum
{
    const MERON = 1;
    const WALA = 2;
    const DRAW = 3;
    const CANCEL = 4;
    const PENDING = 5;
    const NONE = 0;
}
