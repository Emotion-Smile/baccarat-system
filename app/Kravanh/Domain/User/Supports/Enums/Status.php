<?php

namespace App\Kravanh\Domain\User\Supports\Enums;

use BenSampo\Enum\Enum;

final class Status extends Enum
{
    const LOCK = 'lock';
    const OPEN = 'open';
}
