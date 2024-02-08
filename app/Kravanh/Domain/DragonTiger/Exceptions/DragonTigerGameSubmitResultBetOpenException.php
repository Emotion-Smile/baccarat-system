<?php

namespace App\Kravanh\Domain\DragonTiger\Exceptions;

use App\Kravanh\Support\DomainException;

final class DragonTigerGameSubmitResultBetOpenException extends DomainException
{
    protected $message = 'You cannot submit the game result while the betting is open.';
}
