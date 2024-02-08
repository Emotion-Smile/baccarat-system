<?php

namespace App\Kravanh\Domain\DragonTiger\Exceptions;

use App\Kravanh\Support\DomainException;

final class DragonTigerGameUnauthorizedToCreateNewGameException extends DomainException
{
    protected $message = 'Unauthorized to create new game';
}
