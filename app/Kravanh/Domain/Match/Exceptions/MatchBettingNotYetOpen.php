<?php

namespace App\Kravanh\Domain\Match\Exceptions;

use App\Kravanh\Support\DomainException;

class MatchBettingNotYetOpen extends DomainException
{
    protected $message = 'betting.match_betting_not_yet_open';
}
