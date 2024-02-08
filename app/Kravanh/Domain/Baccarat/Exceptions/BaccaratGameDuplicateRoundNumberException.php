<?php

namespace App\Kravanh\Domain\Baccarat\Exceptions;

use App\Kravanh\Support\DomainException;

final class BaccaratGameDuplicateRoundNumberException extends DomainException
{
    protected $message = 'Duplicate round number';
}
