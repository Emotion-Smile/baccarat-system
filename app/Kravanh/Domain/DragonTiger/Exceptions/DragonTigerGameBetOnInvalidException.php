<?php

namespace App\Kravanh\Domain\DragonTiger\Exceptions;

use App\Kravanh\Support\DomainException;

final class DragonTigerGameBetOnInvalidException extends DomainException
{
    protected $message = 'Invalid bet option';
}
