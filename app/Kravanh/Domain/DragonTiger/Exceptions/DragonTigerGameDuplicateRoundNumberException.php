<?php

namespace App\Kravanh\Domain\DragonTiger\Exceptions;

use App\Kravanh\Support\DomainException;

final class DragonTigerGameDuplicateRoundNumberException extends DomainException
{
    protected $message = 'Duplicate round number';
}
