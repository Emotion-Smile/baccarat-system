<?php

namespace App\Kravanh\Domain\Match\Exceptions;

use App\Kravanh\Support\DomainException;

class MatchBettingFailed extends DomainException
{
    protected $message = 'betting.match_betting_failed';
}
