<?php

namespace App\Kravanh\Domain\Baccarat\Exceptions;

use App\Kravanh\Support\DomainException;

final class BaccaratGameBetOnInvalidException extends DomainException
{
    protected $message = 'Invalid bet option';
}
