<?php

namespace App\Kravanh\Domain\Baccarat\Exceptions;

use App\Kravanh\Support\DomainException;

final class BaccaratGameSubmitResultBetOpenException extends DomainException
{
    protected $message = 'You cannot submit the game result while the betting is open.';
}
