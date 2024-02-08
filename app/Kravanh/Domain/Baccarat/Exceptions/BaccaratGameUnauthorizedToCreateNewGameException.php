<?php

namespace App\Kravanh\Domain\Baccarat\Exceptions;

use App\Kravanh\Support\DomainException;

final class BaccaratGameUnauthorizedToCreateNewGameException extends DomainException
{
    protected $message = 'Unauthorized to create new game';
}
