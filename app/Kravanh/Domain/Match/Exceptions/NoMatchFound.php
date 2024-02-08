<?php

namespace App\Kravanh\Domain\Match\Exceptions;

use App\Kravanh\Support\DomainException;

class NoMatchFound extends DomainException
{
    protected $message = 'betting.no_match_found';
}
