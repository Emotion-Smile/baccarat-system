<?php

namespace App\Kravanh\Domain\DragonTiger\Exceptions;

use App\Kravanh\Support\DomainException;

final class DragonTigerGameHasLiveGameException extends DomainException
{
    protected $message = 'Dragon Tiger game has live game';
}
