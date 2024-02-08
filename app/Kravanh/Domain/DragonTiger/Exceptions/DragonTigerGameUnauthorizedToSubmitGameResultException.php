<?php

namespace App\Kravanh\Domain\DragonTiger\Exceptions;

use App\Kravanh\Support\DomainException;

final class DragonTigerGameUnauthorizedToSubmitGameResultException extends DomainException
{
    protected $message = 'Unauthorized to submit game result';
}
