<?php

namespace App\Kravanh\Domain\DragonTiger\Exceptions;

use App\Kravanh\Support\DomainException;

final class DragonTigerGameNoLiveGameException extends DomainException
{
    protected $message = 'No live game.';
}
