<?php

namespace App\Kravanh\Domain\Baccarat\Exceptions;

use App\Kravanh\Support\DomainException;

final class BaccaratGameNoLiveGameException extends DomainException
{
    protected $message = 'No live game.';
}
