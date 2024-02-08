<?php

namespace App\Kravanh\Domain\Match\Exceptions;

use App\Kravanh\Support\DomainException;

class MatchBettingClosed extends DomainException
{
    protected $message = 'betting.match_betting_closed';
}
