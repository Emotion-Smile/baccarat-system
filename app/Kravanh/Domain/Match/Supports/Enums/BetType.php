<?php

namespace App\Kravanh\Domain\Match\Supports\Enums;

use BenSampo\Enum\Enum;

class BetType extends Enum
{
    const AUTO_ACCEPT = 1;
    const CHECK = 2; // status -> pending
    const SUSPECT = 3; // status -> pending
}
