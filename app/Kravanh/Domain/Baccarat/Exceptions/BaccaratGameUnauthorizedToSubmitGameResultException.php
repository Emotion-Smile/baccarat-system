<?php

namespace App\Kravanh\Domain\Baccarat\Exceptions;

use App\Kravanh\Support\DomainException;

final class BaccaratGameUnauthorizedToSubmitGameResultException extends DomainException
{
    protected $message = 'Unauthorized to submit game result';
}
