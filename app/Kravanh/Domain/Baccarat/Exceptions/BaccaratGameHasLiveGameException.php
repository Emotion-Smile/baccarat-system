<?php

namespace App\Kravanh\Domain\Baccarat\Exceptions;

use App\Kravanh\Support\DomainException;

final class BaccaratGameHasLiveGameException extends DomainException
{
    protected $message = 'Baccarat game has live game';
}
