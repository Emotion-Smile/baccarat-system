<?php

namespace App\Kravanh\Domain\DragonTiger\Exceptions;

use App\Kravanh\Support\DomainException;

final class DragonTigerGameUnauthorizedToBetException extends DomainException
{
    protected $message = 'unauthorized to bet.';
}
