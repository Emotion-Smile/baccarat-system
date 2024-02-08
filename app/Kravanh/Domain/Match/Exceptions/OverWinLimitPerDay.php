<?php

namespace App\Kravanh\Domain\Match\Exceptions;

use App\Kravanh\Support\DomainException;

class OverWinLimitPerDay extends DomainException
{
    protected $message = 'betting.over_win_limit_per_day';
}
