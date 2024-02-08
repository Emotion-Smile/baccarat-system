<?php

namespace App\Kravanh\Domain\Baccarat\Exceptions;

use App\Kravanh\Support\DomainException;

final class BaccaratGameUnauthorizedToBetException extends DomainException
{
    protected $message = 'unauthorized to bet.';
}
