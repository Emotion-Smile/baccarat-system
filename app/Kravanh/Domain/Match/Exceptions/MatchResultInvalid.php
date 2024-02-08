<?php

namespace App\Kravanh\Domain\Match\Exceptions;

use App\Kravanh\Support\DomainException;

class MatchResultInvalid extends DomainException
{
    protected $message = 'betting.match_result_invalid';
}
