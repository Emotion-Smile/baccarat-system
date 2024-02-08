<?php

namespace App\Kravanh\Domain\Baccarat\Exceptions;

use App\Kravanh\Support\DomainException;

final class BaccaratGameResubmitResultOnLiveException extends DomainException
{
    protected $message = 'You cannot resubmit result on a live game.';
}
