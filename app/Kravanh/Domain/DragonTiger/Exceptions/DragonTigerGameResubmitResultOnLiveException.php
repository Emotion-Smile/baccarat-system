<?php

namespace App\Kravanh\Domain\DragonTiger\Exceptions;

use App\Kravanh\Support\DomainException;

final class DragonTigerGameResubmitResultOnLiveException extends DomainException
{
    protected $message = 'You cannot resubmit result on a live game.';
}
